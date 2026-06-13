<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Journal;
use App\Models\Feedback;
use App\Models\VocabWord;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // List all students
    public function getStudents(Request $request)
    {
        $students = User::where('role', 'student')
            ->with(['student'])
            ->withCount(['journals'])
            ->get();

        return response()->json($students);
    }

    // Single student details
    public function getStudentDetail($id)
    {
        $studentUser = User::where('role', 'student')->with(['student'])->find($id);

        if (!$studentUser) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $journals = Journal::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $recentFeedback = Feedback::where('user_id', $id)
            ->with(['question'])
            ->orderBy('shown_at', 'desc')
            ->take(15)
            ->get();

        return response()->json([
            'user' => $studentUser,
            'journals' => $journals,
            'recent_feedback' => $recentFeedback,
        ]);
    }

    // Analytics dashboard data
    public function getAnalytics(Request $request)
    {
        // 1. Basic counts
        $totalStudents = User::where('role', 'student')->count();
        $avgXp = Student::avg('xp') ?? 0;
        
        // 2. Average Quiz Scores
        // Count total correct feedback vs incorrect feedback
        $correctAnswersCount = Feedback::where('correct', true)->count();
        $totalAnswersCount = Feedback::count();
        $avgQuizScore = $totalAnswersCount > 0 ? round(($correctAnswersCount / $totalAnswersCount) * 100) : 0;

        // 3. Difficult Vocabulary
        // Identify which questions have the most false answers
        $difficultQuestions = Feedback::where('correct', false)
            ->select('question_id', DB::raw('count(*) as incorrect_count'))
            ->groupBy('question_id')
            ->orderBy('incorrect_count', 'desc')
            ->take(5)
            ->get();

        $difficultWords = [];
        foreach ($difficultQuestions as $dq) {
            $question = Question::with('answer')->find($dq->question_id);
            if ($question && $question->answer && $question->answer->related_vocab) {
                // related_vocab is JSON array e.g. [{"word":"collar", "meaning":"..."}]
                $vocabList = $question->answer->related_vocab;
                foreach ($vocabList as $v) {
                    $word = $v['word'] ?? null;
                    if ($word) {
                        if (isset($difficultWords[$word])) {
                            $difficultWords[$word]['incorrect_count'] += $dq->incorrect_count;
                        } else {
                            $difficultWords[$word] = [
                                'word' => $word,
                                'meaning' => $v['meaning'] ?? '',
                                'incorrect_count' => (int)$dq->incorrect_count,
                            ];
                        }
                    }
                }
            }
        }

        // Sort difficult words
        usort($difficultWords, function($a, $b) {
            return $b['incorrect_count'] <=> $a['incorrect_count'];
        });

        return response()->json([
            'total_students' => $totalStudents,
            'avg_xp' => round($avgXp),
            'avg_quiz_score' => $avgQuizScore,
            'difficult_vocab' => array_slice($difficultWords, 0, 5),
        ]);
    }

    // Manage Vocabulary
    public function storeVocab(Request $request)
    {
        $request->validate([
            'module_id' => 'required|integer|exists:modules,id',
            'level' => 'required|in:beginner,intermediate',
            'word' => 'required|string|max:255',
            'meaning' => 'required|string|max:255',
            'example' => 'nullable|string',
            'emoji' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'order' => 'required|integer',
        ]);

        $vocab = VocabWord::create($request->all());

        return response()->json([
            'message' => 'Kosakata berhasil ditambahkan',
            'vocab' => $vocab
        ], 201);
    }

    public function updateVocab($id, Request $request)
    {
        $vocab = VocabWord::find($id);
        if (!$vocab) {
            return response()->json(['message' => 'Kosakata tidak ditemukan'], 404);
        }

        $request->validate([
            'word' => 'string|max:255',
            'meaning' => 'string|max:255',
            'example' => 'nullable|string',
            'emoji' => 'nullable|string|max:255',
            'category' => 'string|max:100',
            'order' => 'integer',
        ]);

        $vocab->update($request->all());

        return response()->json([
            'message' => 'Kosakata berhasil diperbarui',
            'vocab' => $vocab
        ]);
    }

    public function destroyVocab($id)
    {
        $vocab = VocabWord::find($id);
        if (!$vocab) {
            return response()->json(['message' => 'Kosakata tidak ditemukan'], 404);
        }

        $vocab->delete();

        return response()->json(['message' => 'Kosakata berhasil dihapus']);
    }

    // Manage Questions
    public function storePlacementQuestion(Request $request)
    {
        $quiz = Quiz::where('activity_type', 'placement')->first();
        if (!$quiz) {
            return response()->json(['message' => 'Placement quiz tidak ditemukan'], 404);
        }

        return $this->createQuestion($quiz->id, $request);
    }

    public function storeQuestion($quiz_id, Request $request)
    {
        $quiz = Quiz::find($quiz_id);
        if (!$quiz) {
            return response()->json(['message' => 'Kuis tidak ditemukan'], 404);
        }

        return $this->createQuestion($quiz_id, $request);
    }

    private function createQuestion($quiz_id, Request $request)
    {
        $request->validate([
            'type' => 'required|in:vocab,reading,true_false,multiple_choice',
            'prompt' => 'required|string',
            'passage' => 'nullable|string',
            'options' => 'required|array',
            'topic' => 'required|string|max:100',
            'order' => 'required|integer',
            'answer_index' => 'required|integer',
            'explanation_correct' => 'required|string',
            'explanation_wrong' => 'required|string',
            'related_vocab' => 'nullable|array',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz_id,
            'type' => $request->type,
            'prompt' => $request->prompt,
            'passage' => $request->passage,
            'options' => $request->options,
            'topic' => $request->topic,
            'order' => $request->order,
        ]);

        $answer = Answer::create([
            'question_id' => $question->id,
            'answer_index' => $request->answer_index,
            'explanation_correct' => $request->explanation_correct,
            'explanation_wrong' => $request->explanation_wrong,
            'related_vocab' => $request->related_vocab ?? [],
        ]);

        return response()->json([
            'message' => 'Pertanyaan berhasil ditambahkan',
            'question' => $question->load('answer'),
        ], 201);
    }

    public function updateQuestion($id, Request $request)
    {
        $question = Question::with('answer')->find($id);
        if (!$question) {
            return response()->json(['message' => 'Pertanyaan tidak ditemukan'], 404);
        }

        $request->validate([
            'prompt' => 'string',
            'passage' => 'nullable|string',
            'options' => 'array',
            'topic' => 'string|max:100',
            'order' => 'integer',
            'answer_index' => 'integer',
            'explanation_correct' => 'string',
            'explanation_wrong' => 'string',
            'related_vocab' => 'nullable|array',
        ]);

        $question->update($request->only(['prompt', 'passage', 'options', 'topic', 'order']));

        if ($question->answer) {
            $question->answer->update($request->only(['answer_index', 'explanation_correct', 'explanation_wrong', 'related_vocab']));
        }

        return response()->json([
            'message' => 'Pertanyaan berhasil diperbarui',
            'question' => $question->load('answer'),
        ]);
    }

    public function destroyQuestion($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Pertanyaan tidak ditemukan'], 404);
        }

        $question->delete();

        return response()->json(['message' => 'Pertanyaan berhasil dihapus']);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure public/uploads directory exists
            $uploadPath = public_path('uploads');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            
            // Return public URL
            $url = asset('uploads/' . $filename);

            return response()->json([
                'url' => $url,
                'message' => 'Gambar berhasil diunggah'
            ]);
        }

        return response()->json(['message' => 'Berkas gambar tidak ditemukan'], 400);
    }
}
