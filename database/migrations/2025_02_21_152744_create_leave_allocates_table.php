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
        Schema::create('leave_allocates', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->comment('employee_id=user_id');
            $table->integer('supervisor_id')->comment('supervisor_id=user_id');
            $table->integer('leavetype_id')->comment('leavetype_id=leavetype_id');
            $table->integer('leavegroup_id')->comment('leavegroup_id=leavegroup_id');
            $table->date('effective_date')->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_allocates');
    }
};
