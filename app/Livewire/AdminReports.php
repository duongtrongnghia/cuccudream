<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;

class AdminReports extends Component
{
    public function dismiss(int $id): void
    {
        Report::findOrFail($id)->update(['status' => 'dismissed']);
    }

    public function reviewed(int $id): void
    {
        Report::findOrFail($id)->update(['status' => 'reviewed']);
    }

    public function deleteReportable(int $id): void
    {
        $report = Report::findOrFail($id);
        $reportable = $report->reportable;
        if ($reportable) {
            $reportable->delete();
        }
        $report->update(['status' => 'reviewed']);
    }

    public function render()
    {
        $reports = Report::with(['user', 'reportable'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('livewire.admin-reports', ['reports' => $reports])
            ->layout('layouts.app', ['title' => 'Báo cáo — Admin']);
    }
}
