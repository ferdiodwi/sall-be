<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. STUDENTS
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('xp')->default(0);
            $table->integer('streak')->default(0);
            $table->timestamp('last_active')->nullable();
            $table->string('level')->nullable(); // beginner, intermediate
            $table->integer('placement_score')->nullable();
            $table->timestamp('placement_date')->nullable();
            $table->json('modules_completed')->nullable(); // array of module_ids/numbers
            $table->integer('vocab_mastered')->default(0);
            $table->json('badges')->nullable(); // array of badge names
            $table->timestamps();
        });

        // 2. TEACHERS
        Schema::create('teachers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->json('subjects')->nullable();
            $table->json('classes')->nullable();
            $table->timestamps();
        });

        // 3. MODULES
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('number')->unique();
            $table->string('title');
            $table->string('tagline')->nullable();
            $table->string('emoji')->nullable();
            $table->integer('order');
            $table->boolean('published')->default(false);
            $table->timestamps();
        });

        // 4. LEVELS
        Schema::create('levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('level'); // beginner, intermediate
            $table->longText('content_html')->nullable();
            $table->timestamps();
            $table->unique(['module_id', 'level']);
        });

        // 5. VOCAB WORDS
        Schema::create('vocab_words', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('level'); // beginner, intermediate
            $table->string('word');
            $table->string('meaning');
            $table->text('example')->nullable();
            $table->string('emoji')->default('✨');
            $table->string('category')->default('General');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 6. QUIZZES
        Schema::create('quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->string('level'); // beginner, intermediate, placement
            $table->string('title');
            $table->string('activity_type');
            $table->timestamps();
        });

        // 7. QUESTIONS
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->string('type'); // vocab, reading, true_false, fill_blank, matching
            $table->text('prompt');
            $table->longText('passage')->nullable();
            $table->json('options')->nullable();
            $table->string('topic');
            $table->integer('order')->nullable();
            $table->timestamps();
        });

        // 8. ANSWERS
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('answer_index');
            $table->text('explanation_correct');
            $table->text('explanation_wrong');
            $table->json('related_vocab')->nullable();
            $table->string('review_activity')->nullable();
            $table->timestamps();
        });

        // 9. FEEDBACK
        Schema::create('feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('question_id')->constrained('questions')->onDelete('cascade');
            $table->boolean('correct');
            $table->timestamp('shown_at')->useCurrent();
        });

        // 10. REVIEWS
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignUuid('author_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->string('emoji')->nullable();
            $table->boolean('pinned')->default(false);
            $table->text('teacher_reply')->nullable();
            $table->timestamps();
            $table->unique(['module_id', 'author_id']);
        });

        // 11. JOURNALS
        Schema::create('journals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->text('learned')->nullable();
            $table->text('difficult')->nullable();
            $table->text('goal')->nullable();
            $table->timestamps();
        });

        // 12. WORD WALL
        Schema::create('word_walls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('word');
            $table->string('meaning');
            $table->text('example')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->default('baru'); // baru, sedang dipelajari, dikuasai
            $table->json('review_history')->nullable();
            $table->timestamps();
        });

        // 13. BADGES
        Schema::create('badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('emoji');
            $table->text('description');
            $table->json('requirement');
            $table->timestamps();
        });

        // 14. LEADERBOARDS
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('class_id');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('xp')->default(0);
            $table->string('week_id'); // e.g. 2026-W24
            $table->timestamps();
            $table->unique(['user_id', 'week_id']);
        });

        // 15. RESOURCES
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('type'); // video, audio, worksheet, reading, pdf, docx, pptx
            $table->string('title');
            $table->string('url');
            $table->string('format')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        // 16. WORKSHEETS
        Schema::create('worksheets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('title');
            $table->string('file_url')->nullable();
            $table->string('format')->nullable(); // PDF, DOCX, PPTX, HTML
            $table->boolean('interactive')->default(false);
            $table->timestamps();
        });

        // 17. WORKSHEET SUBMISSIONS
        Schema::create('worksheet_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('worksheet_id')->constrained('worksheets')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_url')->nullable();
            $table->longText('html_content')->nullable();
            $table->decimal('grade', 5, 2)->nullable();
            $table->text('teacher_note')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['worksheet_id', 'user_id']);
        });

        // 18. AI FEEDBACK
        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('weak_topic');
            $table->text('message');
            $table->string('recommended_activity')->nullable();
            $table->integer('est_time_minutes')->nullable();
            $table->timestamps();
        });

        // 19. ANALYTICS
        Schema::create('analytics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('class_id');
            $table->foreignUuid('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->decimal('completion_rate', 5, 2)->nullable();
            $table->decimal('avg_score', 5, 2)->nullable();
            $table->json('hard_vocab')->nullable();
            $table->json('hard_texts')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
        });

        // 20. NOTIFICATIONS
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->boolean('read')->default(false);
            $table->timestamps();
        });

        // 21. CHALLENGES
        Schema::create('challenges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->string('target_type'); // modules_complete, quiz_score, etc.
            $table->integer('target_value');
            $table->integer('bonus_xp')->default(50);
            $table->string('week_id'); // e.g. 2026-W24
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('analytics');
        Schema::dropIfExists('ai_feedback');
        Schema::dropIfExists('worksheet_submissions');
        Schema::dropIfExists('worksheets');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('leaderboards');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('word_walls');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('vocab_words');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('students');
    }
};
