<?php

namespace App\Providers;

use App\Models\leave\LeaveRequest;
use App\Policies\LeaveRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        LeaveRequest::class => LeaveRequestPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public function generateApprovalReport(LeaveRequest $leaveRequest): string
    {
        // Generate PDF content
        $pdf = PDF::loadView('pdf.leave-approval-report', [
            'leaveRequest' => $leaveRequest->load(['user', 'leaveType', 'reviewer'])
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = $this->generateFilename($leaveRequest);
        $path = 'leave-reports/' . $filename;

        // Save PDF to storage
        Storage::disk('public')->put($path, $pdf->output());

        // Update leave request with PDF path
        $leaveRequest->update(['pdf_report_path' => $path]);

        return $path;
    }

    private function generateFilename(LeaveRequest $leaveRequest): string
    {
        $employeeName = str_replace(' ', '_', strtolower($leaveRequest->user->name));
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $requestId = $leaveRequest->id;

        return "leave_approval_{$employeeName}_{$requestId}_{$date}.pdf";
    }

    public function regenerateReport(LeaveRequest $leaveRequest): string
    {
        // Delete existing PDF if it exists
        if ($leaveRequest->pdf_report_path && Storage::disk('public')->exists($leaveRequest->pdf_report_path)) {
            Storage::disk('public')->delete($leaveRequest->pdf_report_path);
        }

        return $this->generateApprovalReport($leaveRequest);
    }


}
