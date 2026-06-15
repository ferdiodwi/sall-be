<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Student;
use App\Models\Feedback;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuizController extends Controller
{
    public function showPlacement()
    {
        $quiz = Quiz::with(['questions.answer'])->where('activity_type', 'placement')->first();

        if (!$quiz) {
            return response()->json(['message' => 'Placement quiz tidak ditemukan'], 404);
        }

        return response()->json($quiz);
    }

    public function show($id)
    {
        $quiz = Quiz::with(['questions.answer'])->find($id);

        if (!$quiz) {
            return response()->json(['message' => 'Kuis tidak ditemukan'], 404);
        }

        // Return quiz details
        return response()->json($quiz);
    }

    public function submitPlacement(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $quiz = Quiz::with('questions.answer')->where('activity_type', 'placement')->first();

        if (!$quiz) {
            return response()->json(['message' => 'Placement quiz tidak ditemukan'], 404);
        }

        // Reuse the same submit logic with the resolved quiz ID
        return $this->processSubmit($quiz, $request);
    }

    public function submit($id, Request $request)
    {
        $request->validate([
            'answers' => 'required|array', // key: question_id, value: selected index (integer)
        ]);

        $quiz = Quiz::with('questions.answer')->find($id);

        if (!$quiz) {
            return response()->json(['message' => 'Kuis tidak ditemukan'], 404);
        }

        $user = $request->user();
        $student = Student::find($user->id);

        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $submittedAnswers = $request->answers;
        $totalQuestions = count($quiz->questions);
        $correctCount = 0;
        $results = [];

        foreach ($quiz->questions as $question) {
            $correctIndex = $question->answer->answer_index;
            $submittedIndex = $submittedAnswers[$question->id] ?? null;
            $isCorrect = ($submittedIndex !== null && (int)$submittedIndex === (int)$correctIndex);

            if ($isCorrect) {
                $correctCount++;
            }

            // Save feedback log in feedback table
            Feedback::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'correct' => $isCorrect,
                'shown_at' => Carbon::now(),
            ]);

            $results[] = [
                'question_id' => $question->id,
                'prompt' => $question->prompt,
                'submitted_index' => $submittedIndex,
                'correct_index' => $correctIndex,
                'correct' => $isCorrect,
                'explanation' => $isCorrect ? $question->answer->explanation_correct : $question->answer->explanation_wrong,
                'related_vocab' => $question->answer->related_vocab,
            ];
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Anti-Cheat: Cek apakah siswa sudah pernah submit kuis ini sebelumnya
        $firstAttempt = !Feedback::where('user_id', $user->id)
            ->whereHas('question', function ($q) use ($quiz) {
                $q->where('quiz_id', $quiz->id);
            })
            ->exists();

        $xpEarned = $firstAttempt ? ($correctCount * 10) : 0;

        // Apply XP (hanya jika attempt pertama)
        $student->xp += $xpEarned;

        // Streak logic (tetap jalan di setiap attempt)
        $now = Carbon::now();
        $lastActive = $student->last_active;
        if ($lastActive) {
            $diffInDays = Carbon::parse($lastActive)->startOfDay()->diffInDays($now->startOfDay());
            if ($diffInDays === 1) {
                $student->streak += 1;
            } elseif ($diffInDays > 1) {
                $student->streak = 1;
            }
        } else {
            $student->streak = 1;
        }
        $student->last_active = $now;

        // Badge & bonus XP hanya di attempt pertama
        $currentBadges = $student->badges ?? [];
        if ($firstAttempt) {
            if ($student->streak >= 7 && !in_array('on_fire', $currentBadges)) {
                $currentBadges[] = 'on_fire';
                $student->xp += 50;
            }

            if ($score === 100 && !in_array('quiz_champion', $currentBadges)) {
                $currentBadges[] = 'quiz_champion';
                $student->xp += 50;
            }

            // Module completion bonus
            if ($quiz->module_id && $score >= 60) {
                $completed = $student->modules_completed ?? [];
                $completionKey = $quiz->module_id . '_' . $quiz->level;
                if (!in_array($completionKey, $completed)) {
                    $completed[] = $completionKey;
                    $student->modules_completed = $completed;
                    $student->xp += 100;

                    if (count($completed) >= 3 && !in_array('bookworm', $currentBadges)) {
                        $currentBadges[] = 'bookworm';
                        $student->xp += 150;
                    }
                }
            }
        }

        $student->badges = $currentBadges;
        $student->save();

        // Update Leaderboard
        $weekId = Carbon::now()->format('o-\\WW');
        $leaderboard = Leaderboard::where('user_id', $user->id)->where('week_id', $weekId)->first();
        if ($leaderboard) {
            $leaderboard->xp = $student->xp;
            $leaderboard->save();
        } else {
            Leaderboard::create([
                'class_id' => $user->class_id ?? 'X-Tata Busana 1',
                'user_id' => $user->id,
                'xp' => $student->xp,
                'week_id' => $weekId,
            ]);
        }

        return response()->json([
            'message' => $firstAttempt ? 'Kuis berhasil disubmit' : 'Kuis dikerjakan ulang (XP tidak ditambah)',
            'score' => $score,
            'xp_earned' => $xpEarned,
            'first_attempt' => $firstAttempt,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'streak' => $student->streak,
            'results' => $results,
            'badges' => $student->badges,
        ]);
    }

    private function processSubmit($quiz, $request)
    {
        $submittedAnswers = $request->answers;
        $totalQuestions = count($quiz->questions);
        $correctCount = 0;
        $results = [];

        $user = $request->user();
        $student = Student::find($user->id);

        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        foreach ($quiz->questions as $question) {
            $correctIndex = $question->answer->answer_index;
            $submittedIndex = $submittedAnswers[$question->id] ?? null;
            $isCorrect = ($submittedIndex !== null && (int)$submittedIndex === (int)$correctIndex);

            if ($isCorrect) {
                $correctCount++;
            }

            Feedback::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'correct' => $isCorrect,
                'shown_at' => Carbon::now(),
            ]);

            $results[] = [
                'question_id' => $question->id,
                'prompt' => $question->prompt,
                'submitted_index' => $submittedIndex,
                'correct_index' => $correctIndex,
                'correct' => $isCorrect,
                'explanation' => $isCorrect ? $question->answer->explanation_correct : $question->answer->explanation_wrong,
                'related_vocab' => $question->answer->related_vocab,
            ];
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $xpEarned = $correctCount * 10;

        $student->xp += $xpEarned;

        $now = Carbon::now();
        $lastActive = $student->last_active;
        if ($lastActive) {
            $diffInDays = Carbon::parse($lastActive)->startOfDay()->diffInDays($now->startOfDay());
            if ($diffInDays === 1) {
                $student->streak += 1;
            } elseif ($diffInDays > 1) {
                $student->streak = 1;
            }
        } else {
            $student->streak = 1;
        }
        $student->last_active = $now;

        $currentBadges = $student->badges ?? [];
        if ($student->streak >= 7 && !in_array('on_fire', $currentBadges)) {
            $currentBadges[] = 'on_fire';
            $student->xp += 50;
        }

        if ($score === 100 && !in_array('quiz_champion', $currentBadges)) {
            $currentBadges[] = 'quiz_champion';
            $student->xp += 50;
        }

        if ($quiz->module_id && $score >= 60) {
            $completed = $student->modules_completed ?? [];
            if (!in_array($quiz->module_id, $completed)) {
                $completed[] = $quiz->module_id;
                $student->modules_completed = $completed;
                $student->xp += 100;

                if (count($completed) >= 3 && !in_array('bookworm', $currentBadges)) {
                    $currentBadges[] = 'bookworm';
                    $student->xp += 150;
                }
            }
        }

        $student->badges = $currentBadges;
        $student->save();

        $weekId = Carbon::now()->format('o-\WW');
        $leaderboard = Leaderboard::where('user_id', $user->id)->where('week_id', $weekId)->first();
        if ($leaderboard) {
            $leaderboard->xp = $student->xp;
            $leaderboard->save();
        } else {
            Leaderboard::create([
                'class_id' => $user->class_id ?? 'X-Tata Busana 1',
                'user_id' => $user->id,
                'xp' => $student->xp,
                'week_id' => $weekId,
            ]);
        }

        return response()->json([
            'message' => 'Kuis berhasil disubmit',
            'score' => $score,
            'xp_earned' => $xpEarned,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'streak' => $student->streak,
            'results' => $results,
            'badges' => $student->badges,
        ]);
    }
}
