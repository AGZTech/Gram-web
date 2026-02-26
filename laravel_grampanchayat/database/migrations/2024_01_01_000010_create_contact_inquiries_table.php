<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied', 'closed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
