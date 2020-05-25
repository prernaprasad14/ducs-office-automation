<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarExaminer;
use App\Types\RequestStatus;
use Illuminate\Http\Request;

class ScholarExaminerController extends Controller
{
    public function apply(Request $request, Scholar $scholar)
    {
        $this->authorize('create', [ScholarExaminer::class, $scholar]);

        $scholar->examiner()->create([
            'status' => RequestStatus::APPLIED,
        ]);

        flash('Applied for Scholar\'s Examiner Successfully!')->success();

        return redirect()->back();
    }

    public function recommend(Request $request, Scholar $scholar, ScholarExaminer $examiner)
    {
        $this->authorize('recommend', [$examiner, $scholar]);

        $examiner->update([
            'status' => RequestStatus::RECOMMENDED,
            'recommended_on' => now(),
        ]);

        flash('Examiner request recommended successfully!')->success();

        return redirect()->back();
    }

    public function approve(Request $request, Scholar $scholar, ScholarExaminer $examiner)
    {
        $this->authorize('approve', [$examiner, $scholar]);

        $examiner->update([
            'status' => RequestStatus::APPROVED,
            'approved_on' => now(),
        ]);

        flash('Examiner request approved successfully!')->success();

        return redirect()->back();
    }
}
