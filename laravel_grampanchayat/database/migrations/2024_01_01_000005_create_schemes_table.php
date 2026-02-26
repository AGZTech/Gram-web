<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('eligibility')->nullable();
            $table->longText('benefits')->nullable();
            $table->longText('documents_required')->nullable();
            $table->longText('how_to_apply')->nullable();
            $table->string('gr_link')->nullable();
            $table->string('pdf_file')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
