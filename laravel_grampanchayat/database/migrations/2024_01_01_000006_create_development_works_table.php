<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_works', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->decimal('spent_amount', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'on_hold'])->default('planned');
            $table->integer('progress_percentage')->default(0);
            $table->string('contractor_name')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_works');
    }
};
