<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('created_at')->get();

        $openPeriod = $periods->where('status', 'open')->first();

        $elapsedTime = null;
        if ($openPeriod) {
            $startTime = Carbon::parse($openPeriod->start_date);
            $elapsedTime = $startTime->diffInSeconds(Carbon::now());
        }

        return Inertia::render('periods/Index', [
            'periods' => $periods,
            'isRunning' => $openPeriod !== null,
            'elapsedTime' => $elapsedTime,
            'openPeriodId' => $openPeriod ? $openPeriod->id : null,
        ]);
    }

    /**
     * Get current elapsed time for open period
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getElapsedTime()
    {
        $openPeriod = Period::where('status', 'open')->first();
        
        if (!$openPeriod) {
            return response()->json(['elapsedTime' => 0, 'isRunning' => false]);
        }
        
        $startTime = Carbon::parse($openPeriod->start_date);
        $elapsedTime = $startTime->diffInSeconds(Carbon::now());
        
        return response()->json([
            'elapsedTime' => $elapsedTime,
            'isRunning' => true,
            'openPeriodId' => $openPeriod->id
        ]);
    }


    /**
     * Open a new EWD period
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function open()
    {
        $openPeriode = Period::where('status', 'open')->first();

        if ($openPeriode) {
            return redirect()->route('periods.index')->with('error', 'Tidak dapat membuka periode baru karena masih ada periode yang terbuka.');
        }

        Period::create([
            'start_date' => now(),
            'created_by' => Auth::id() ?? '1',
            'status' => 'open',
        ]);

        return redirect()->route('periods.index')->with('success', 'Period has been opened successfully.');
    }

    /**
     * Close an existing EWD period
     *
     * @param Period $period
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close(Period $period)
    {
        if ($period->status === 'closed') {
            return redirect()->route('periods.index')->with('error', 'Periode ini sudah ditutup sebelumnya.');
        }

        $period->update([
            'end_date' => now(),
            'status' => 'closed',
        ]);

        return redirect()->route('periods.index')->with('success', 'Period has been closed successfully.');
    }
}
