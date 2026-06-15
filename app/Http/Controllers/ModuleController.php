<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Level;
use App\Models\VocabWord;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Module::orderBy('order', 'asc');
        
        // If student, only show published modules
        if ($user && $user->role === 'student') {
            $query->where('published', true);
        }

        $modules = $query->withCount(['vocabWords', 'quizzes'])->get();

        return response()->json($modules);
    }

    public function show($id, Request $request)
    {
        $module = Module::with(['levels', 'vocabWords', 'quizzes.questions.answer'])->find($id);

        if (!$module) {
            return response()->json(['message' => 'Modul tidak ditemukan.'], 404);
        }

        return response()->json($module);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer',
            'title' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'emoji' => 'required|string|max:255',
            'order' => 'required|integer',
            'published' => 'boolean',
            'game_type' => 'nullable|string|in:card_flip,word_blast,drag_drop,fashion_runner',
        ]);

        $module = Module::create($request->all());

        // Create empty beginner & intermediate levels automatically
        Level::create([
            'module_id' => $module->id,
            'level' => 'beginner',
            'content_html' => '<h3>Materi Beginner</h3><p>Edit materi di sini...</p>',
        ]);

        Level::create([
            'module_id' => $module->id,
            'level' => 'intermediate',
            'content_html' => '<h3>Materi Intermediate</h3><p>Edit materi di sini...</p>',
        ]);

        return response()->json([
            'message' => 'Modul berhasil dibuat',
            'module' => $module,
        ], 210);
    }

    public function update($id, Request $request)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Modul tidak ditemukan'], 404);
        }

        $request->validate([
            'number' => 'integer',
            'title' => 'string|max:255',
            'tagline' => 'string|max:255',
            'emoji' => 'string|max:255',
            'order' => 'integer',
            'published' => 'boolean',
            'game_type' => 'nullable|string|in:card_flip,word_blast,drag_drop,fashion_runner',
        ]);

        $module->update($request->all());

        return response()->json([
            'message' => 'Modul berhasil diperbarui',
            'module' => $module,
        ]);
    }

    public function destroy($id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Modul tidak ditemukan'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Modul berhasil dihapus']);
    }

    public function togglePublish($id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Modul tidak ditemukan'], 404);
        }

        $module->published = !$module->published;
        $module->save();

        return response()->json([
            'message' => $module->published ? 'Modul berhasil dipublikasikan' : 'Modul berhasil disembunyikan',
            'published' => $module->published,
        ]);
    }

    // Save level HTML contents
    public function updateLevelContent($id, Request $request)
    {
        $request->validate([
            'level' => 'required|in:beginner,intermediate',
            'content_html' => 'required|string',
            'visual_guide_image' => 'nullable|string',
            'visual_guide_desc' => 'nullable|string',
        ]);

        $level = Level::where('module_id', $id)->where('level', $request->level)->first();
        
        $data = [
            'content_html' => $request->content_html,
            'visual_guide_image' => $request->visual_guide_image,
            'visual_guide_desc' => $request->visual_guide_desc,
        ];

        if (!$level) {
            $data['module_id'] = $id;
            $data['level'] = $request->level;
            $level = Level::create($data);
        } else {
            $level->update($data);
        }

        return response()->json([
            'message' => 'Konten level berhasil disimpan',
            'level' => $level,
        ]);
    }

    public function complete(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $level = $request->input('level', 'beginner'); // 'beginner' or 'intermediate'
        $student = $user->student;
        $completed = $student->modules_completed ?? [];
        
        $completionKey = $id . '_' . $level;

        if (!in_array($completionKey, $completed)) {
            $completed[] = $completionKey;
            $student->modules_completed = $completed;
            $student->xp += 50; // Bonus XP for completing a module explicitly
            
            // Check for bookworm badge
            $currentBadges = $student->badges ?? [];
            if (count($completed) >= 3 && !in_array('bookworm', $currentBadges)) {
                $currentBadges[] = 'bookworm';
                $student->badges = $currentBadges;
                $student->xp += 150;
            }
            
            $student->save();
            
            // Update Leaderboard
            $weekId = \Carbon\Carbon::now()->format('o-\WW');
            $leaderboard = \App\Models\Leaderboard::where('user_id', $user->id)->where('week_id', $weekId)->first();
            if ($leaderboard) {
                $leaderboard->xp += 50;
                $leaderboard->save();
            } else {
                \App\Models\Leaderboard::create([
                    'user_id' => $user->id,
                    'class_id' => $user->class_id,
                    'xp' => 50,
                    'week_id' => $weekId,
                ]);
            }
        }

        return response()->json([
            'message' => 'Module marked as completed',
            'modules_completed' => $completed
        ]);
    }
}
