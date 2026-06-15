<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\WordWall;
use App\Models\Journal;
use App\Models\Leaderboard;
use App\Models\Badge;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
    // Dashboard Stats
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $student = Student::find($user->id);

        if (!$student) {
            return response()->json(['message' => 'Detail siswa tidak ditemukan'], 404);
        }

        // Auto check / refresh streak if last active is older than 24 hours
        // (For simplicity, we just return the current streak)
        $weeklyChallenge = Challenge::where('week_id', Carbon::now()->format('o-\\WW'))->first();
        if (!$weeklyChallenge) {
            // fallback to any challenge
            $weeklyChallenge = Challenge::first();
        }

        return response()->json([
            'student' => $student,
            'user' => $user,
            'weekly_challenge' => $weeklyChallenge,
        ]);
    }

    // Submit Placement Quiz
    public function submitPlacement(Request $request)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $user = $request->user();
        $student = Student::find($user->id);

        if (!$student) {
            return response()->json(['message' => 'Detail siswa tidak ditemukan'], 404);
        }

        // Determine level
        $level = $request->score >= 60 ? 'intermediate' : 'beginner';

        // Update student & user
        $student->placement_score = $request->score;
        $student->placement_date = Carbon::now();
        $student->level = $level;
        
        // Add first_step badge if not already added
        $currentBadges = $student->badges ?? [];
        if (!in_array('first_step', $currentBadges)) {
            $currentBadges[] = 'first_step';
            $student->badges = $currentBadges;
            $student->xp += 20; // 20 XP bonus
        }
        $student->save();

        $user->level = $level;
        $user->save();

        // Record leaderboard
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
            'message' => 'Placement quiz berhasil dikirim. Level kamu disetel ke: ' . $level,
            'level' => $level,
            'student' => $student,
        ]);
    }

    // Word Wall Endpoints
    public function getWordWall(Request $request)
    {
        $words = WordWall::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($words);
    }

    public function addWordWall(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255',
            'meaning' => 'required|string|max:255',
            'example' => 'nullable|string',
            'emoji' => 'nullable|string|max:255',
        ]);

        $userId = $request->user()->id;

        // Cek duplikat: kata yang sama sudah ada di Word Wall user ini
        $existing = WordWall::where('user_id', $userId)
            ->whereRaw('LOWER(word) = ?', [strtolower($request->word)])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Kosakata ini sudah ada di Word Wall kamu.',
                'word' => $existing,
            ], 409);
        }

        $word = WordWall::create([
            'user_id' => $userId,
            'word' => $request->word,
            'meaning' => $request->meaning,
            'example' => $request->example,
            'emoji' => $request->emoji ?? '🏷️',
            'status' => 'learning',
            'review_history' => [],
        ]);

        return response()->json([
            'message' => 'Kata berhasil ditambahkan ke Word Wall',
            'word' => $word,
        ], 201);
    }

    public function updateWordWall($id, Request $request)
    {
        $word = WordWall::where('user_id', $request->user()->id)->find($id);
        if (!$word) {
            return response()->json(['message' => 'Kata tidak ditemukan'], 404);
        }

        $request->validate([
            'status' => 'required|in:learning,mastered',
        ]);

        $word->status = $request->status;

        // Add history record
        $history = $word->review_history ?? [];
        $history[] = [
            'reviewed_at' => Carbon::now()->toIso8601String(),
            'status' => $request->status,
        ];
        $word->review_history = $history;
        $word->save();

        if ($request->status === 'mastered') {
            $student = Student::find($request->user()->id);
            if ($student) {
                $student->xp += 10; // 10 XP bonus for mastering a word
                $student->vocab_mastered += 1;
                $student->save();
            }
        }

        return response()->json([
            'message' => 'Status kata berhasil diperbarui',
            'word' => $word,
        ]);
    }

    public function deleteWordWall($id, Request $request)
    {
        $word = WordWall::where('user_id', $request->user()->id)->find($id);
        if (!$word) {
            return response()->json(['message' => 'Kata tidak ditemukan'], 404);
        }

        $word->delete();
        return response()->json(['message' => 'Kata berhasil dihapus dari Word Wall']);
    }

    // Journal Endpoints
    public function getJournals(Request $request)
    {
        $journals = Journal::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($journals);
    }

    public function addJournal(Request $request)
    {
        $request->validate([
            'learned' => 'required|string',
            'difficult' => 'required|string',
            'goal' => 'required|string',
        ]);

        $journal = Journal::create([
            'user_id' => $request->user()->id,
            'learned' => $request->learned,
            'difficult' => $request->difficult,
            'goal' => $request->goal,
        ]);

        // Award 15 XP for journaling
        $student = Student::find($request->user()->id);
        if ($student) {
            $student->xp += 15;

            // Check badge journaling_pro
            $journalCount = Journal::where('user_id', $request->user()->id)->count();
            $currentBadges = $student->badges ?? [];
            if ($journalCount >= 10 && !in_array('journaling_pro', $currentBadges)) {
                $currentBadges[] = 'journaling_pro';
                $student->badges = $currentBadges;
                $student->xp += 100; // Bonus 100 XP
            }
            $student->save();
        }

        return response()->json([
            'message' => 'Jurnal berhasil disimpan',
            'journal' => $journal,
        ], 201);
    }

    // Leaderboard
    public function getLeaderboard(Request $request)
    {
        $weekId = Carbon::now()->format('o-\\WW');

        $leaderboard = Leaderboard::where('week_id', $weekId)
            ->with(['user' => function($q) {
                $q->select('id', 'name', 'photo_url', 'class_id');
            }])
            ->orderBy('xp', 'desc')
            ->get();

        return response()->json($leaderboard);
    }
}
