<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Module;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Fetch all reviews for a module, with author details
    public function index($moduleId)
    {
        $reviews = Review::where('module_id', $moduleId)
            ->with(['author' => function($query) {
                $query->select('id', 'name', 'photo_url');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reviews);
    }

    // Submit or update a review
    public function store(Request $request, $moduleId)
    {
        $user = $request->user();
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'emoji' => 'nullable|string|max:50',
        ]);

        $module = Module::find($moduleId);
        if (!$module) {
            return response()->json(['message' => 'Modul tidak ditemukan.'], 404);
        }

        // Upsert review (since one user can only review a module once)
        $review = Review::updateOrCreate(
            [
                'module_id' => $moduleId,
                'author_id' => $user->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'emoji' => $request->emoji,
            ]
        );

        // Load author info for the response
        $review->load(['author' => function($query) {
            $query->select('id', 'name', 'photo_url');
        }]);

        return response()->json([
            'message' => 'Ulasan berhasil disimpan.',
            'review' => $review
        ], 200);
    }

    // Post or update a teacher reply
    public function reply(Request $request, $reviewId)
    {
        $user = $request->user();
        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Hanya guru yang dapat membalas ulasan.'], 403);
        }

        $request->validate([
            'teacher_reply' => 'required|string|max:1000',
        ]);

        $review = Review::find($reviewId);
        if (!$review) {
            return response()->json(['message' => 'Ulasan tidak ditemukan.'], 404);
        }

        $review->teacher_reply = $request->teacher_reply;
        $review->save();

        return response()->json([
            'message' => 'Balasan berhasil disimpan.',
            'review' => $review
        ]);
    }

    // Delete a review (by student who created it, or by a teacher)
    public function destroy(Request $request, $reviewId)
    {
        $user = $request->user();
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Ulasan tidak ditemukan.'], 404);
        }

        // Only author or a teacher can delete
        if ($user->role !== 'teacher' && $review->author_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki hak untuk menghapus ulasan ini.'], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Ulasan berhasil dihapus.'
        ]);
    }
}
