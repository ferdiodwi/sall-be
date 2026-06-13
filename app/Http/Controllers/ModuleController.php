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
        ]);

        $level = Level::where('module_id', $id)->where('level', $request->level)->first();
        
        if (!$level) {
            $level = Level::create([
                'module_id' => $id,
                'level' => $request->level,
                'content_html' => $request->content_html,
            ]);
        } else {
            $level->update(['content_html' => $request->content_html]);
        }

        return response()->json([
            'message' => 'Konten level berhasil disimpan',
            'level' => $level,
        ]);
    }
}
