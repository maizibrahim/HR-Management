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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_group_id')->constrained()->onDelete('cascade');
            $table->string('leave_code');
            $table->string('leave_name');
            $table->integer('days_allowed')->nullable();
            $table->boolean('requires_documentation')->default(false);
            $table->timestamps();

            $table->unique(['leave_group_id','leave_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
