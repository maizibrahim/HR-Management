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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_requested');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comments')->nullable();
            $table->string('documentation_path')->nullable();
              $table->string('pdf_report_path')->nullable();
               $table->boolean('documentation_uploaded_later')->default(false)->after('documentation_path');

            // Add field to track documentation upload date
            $table->timestamp('documentation_uploaded_at')->nullable()->after('documentation_uploaded_later');

            // Add field to track who uploaded documentation (employee or admin)
            $table->foreignId('documentation_uploaded_by')->nullable()
                  ->references('id')->on('users')
                  ->after('documentation_uploaded_at');



            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['documentation_uploaded_by']);
            $table->dropColumn([
                'documentation_uploaded_later',
                'documentation_uploaded_at',
                'documentation_uploaded_by'
            ]);
        });


    }

};
