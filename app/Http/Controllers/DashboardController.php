<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $stats = [
            'total_students' => Student::count(),
            'total_formations' => Formation::count(),
        ];

        // Recent lists
        $recentFormations = Formation::latest()->take(3)->get();
        $recentStudents   = Student::latest()->take(3)->get();

        // --- Last 7 days (daily counts) ---
        $end = Carbon::today();
        $start = $end->copy()->subDays(6); // 7 days total

        $rawPerDay = Student::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total','date') // ['2025-11-30' => 3, ...]
            ->toArray();

        $days = [];
        $dayTotals = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $days[] = $d->format('M j'); // label friendly
            $dayTotals[] = isset($rawPerDay[$key]) ? (int)$rawPerDay[$key] : 0;
        }

        // --- Last 12 months (by start_date) ---
        $monthEnd = Carbon::now();
        $monthStart = $monthEnd->copy()->subMonths(11)->startOfMonth();

        $rawPerMonth = Student::select(
                DB::raw('YEAR(start_date) as year'),
                DB::raw('MONTH(start_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('start_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->groupBy('year','month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $monthTotals = [];
        $mapMonth = [];
        foreach ($rawPerMonth as $r) {
            $mapMonth[$r->year . '-' . str_pad($r->month,2,'0',STR_PAD_LEFT)] = (int)$r->total;
        }

        $cursor = $monthStart->copy();
        while ($cursor->lte($monthEnd)) {
            $key = $cursor->format('Y-m');
            $months[] = $cursor->format('M Y');
            $monthTotals[] = $mapMonth[$key] ?? 0;
            $cursor->addMonth();
        }

        // --- By city ---
        $rawCity = Student::select('city', DB::raw('COUNT(*) as total'))
            ->groupBy('city')
            ->orderByDesc('total')
            ->pluck('total','city')
            ->toArray();

        $cityLabels = [];
        $cityTotals = [];
        foreach ($rawCity as $city => $total) {
            $label = $city ?: 'Unknown';
            $cityLabels[] = $label;
            $cityTotals[] = (int)$total;
        }

        // --- By status ---
        $rawStatus = Student::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total','status')
            ->toArray();

        $statusLabels = [];
        $statusTotals = [];
        foreach ($rawStatus as $status => $total) {
            $statusLabels[] = ucfirst($status);
            $statusTotals[] = (int)$total;
        }

        // --- By year ---
        $rawYear = Student::select(DB::raw('YEAR(start_date) as year'), DB::raw('COUNT(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total','year')
            ->toArray();

        $yearLabels = array_keys($rawYear);
        $yearTotals = array_values($rawYear);

        return view('dashboard', compact(
            'stats',
            'recentFormations',
            'recentStudents',
            'days','dayTotals',
            'months','monthTotals',
            'cityLabels','cityTotals',
            'statusLabels','statusTotals',
            'yearLabels','yearTotals'
        ));
    }
}
