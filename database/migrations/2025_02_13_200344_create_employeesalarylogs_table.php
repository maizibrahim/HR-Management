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
        Schema::create('employeesalarylogs', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->comment('employee_id=user_id');
            $table->integer('basic_salary')->nullable();
            $table->integer('job_allowance')->nullable();
            $table->integer('service_allowance')->nullable();
            $table->integer('attendance_allowance')->nullable();
            $table->date('effected_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeesalarylogs');
    }
};
