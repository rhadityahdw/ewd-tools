<?php

namespace App\Services;

use App\Models\ReportSummary;
use Illuminate\Database\Eloquent\Collection;

class ReportSummaryService
{
    public function getAllReportSummaries(): Collection
    {
        return ReportSummary::with([
            'report.borrower',
            'report.period',
        ])->get();
    }

    public function getReportSummaryById(int $id): ?ReportSummary
    {
        return ReportSummary::with([
           'report.borrower',
           'report.period',
        ])->find($id);
    }

    public function createReportSummary(array $data)
    {
        return ReportSummary::create($data);
    }

    


}

