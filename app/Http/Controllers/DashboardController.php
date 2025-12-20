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
        // ======================
        // BASIC STATS
        // ======================
        $stats = [
            'total_students'   => Student::count(),
            'total_formations' => Formation::count(),
        ];

        // ======================
        // FINANCIAL STATS
        // ======================
        $financial = [
            'total_revenue'   => Student::sum('payment_done'),
            'total_remaining' => Student::sum('payment_remaining'),
            'expected_total'  => Student::sum('payment_done') + Student::sum('payment_remaining'),
            'students_paid'   => Student::where('payment_remaining', '<=', 0)->count(),
            'students_unpaid' => Student::where('payment_remaining', '>', 0)->count(),
        ];

        // ======================
        // MONTHLY REVENUE (12 MONTHS)
        // ======================
        $monthlyRevenue = Student::selectRaw(
                'YEAR(created_at) as year, MONTH(created_at) as month, SUM(payment_done) as total'
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $revenueLabels = $monthlyRevenue
            ->map(fn ($r) => $r->month . '/' . $r->year)
            ->toArray();

        $revenueTotals = $monthlyRevenue
            ->pluck('total')
            ->toArray();

        // ======================
        // RECENT DATA
        // ======================
        $recentFormations = Formation::latest()->take(3)->get();
        $recentStudents   = Student::latest()->take(3)->get();

        // ======================
        // LAST 7 DAYS (STUDENTS)
        // ======================
        $end   = Carbon::today();
        $start = $end->copy()->subDays(6);

        $rawPerDay = Student::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $days = [];
        $dayTotals = [];

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $days[] = $d->format('M j');
            $dayTotals[] = $rawPerDay[$key] ?? 0;
        }

        // ======================
        // LAST 12 MONTHS (START DATE)
        // ======================
        $monthEnd   = Carbon::now();
        $monthStart = $monthEnd->copy()->subMonths(11)->startOfMonth();

        $rawPerMonth = Student::select(
                DB::raw('YEAR(start_date) as year'),
                DB::raw('MONTH(start_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('start_date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString()
            ])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $mapMonth = [];
        foreach ($rawPerMonth as $r) {
            $mapMonth[
                $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT)
            ] = (int) $r->total;
        }

        $months = [];
        $monthTotals = [];
        $cursor = $monthStart->copy();

        while ($cursor->lte($monthEnd)) {
            $key = $cursor->format('Y-m');
            $months[] = $cursor->format('M Y');
            $monthTotals[] = $mapMonth[$key] ?? 0;
            $cursor->addMonth();
        }

        // ======================
        // BY CITY
        // ======================
        $rawCity = Student::select('city', DB::raw('COUNT(*) as total'))
            ->groupBy('city')
            ->orderByDesc('total')
            ->pluck('total', 'city')
            ->toArray();

        $cityLabels = [];
        $cityTotals = [];
        foreach ($rawCity as $city => $total) {
            $cityLabels[] = $city ?: 'Unknown';
            $cityTotals[] = (int) $total;
        }

        // ======================
        // BY STATUS
        // ======================
        $rawStatus = Student::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusLabels = [];
        $statusTotals = [];
        foreach ($rawStatus as $status => $total) {
            $statusLabels[] = ucfirst($status);
            $statusTotals[] = (int) $total;
        }

        // ======================
        // BY YEAR
        // ======================
        $rawYear = Student::select(
                DB::raw('YEAR(start_date) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year')
            ->toArray();

        $yearLabels = array_keys($rawYear);
        $yearTotals = array_values($rawYear);

        // ======================
        // ALERTS
        // ======================
        $alerts = [];

        $unpaidCount = Student::where('payment_remaining', '>', 0)->count();
        if ($unpaidCount > 0) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => 'money',
                'message' => "$unpaidCount étudiant(s) ont des paiements en attente",
                'link'    => route('students.index', ['payment_status' => 'unpaid']),
            ];
        }

        $lowEnrollment = Formation::withCount('students')
            ->having('students_count', '<', 5)
            ->get();

        foreach ($lowEnrollment as $formation) {
            $alerts[] = [
                'type'    => 'info',
                'icon'    => 'users',
                'message' => "Formation '{$formation->name}' a seulement {$formation->students_count} étudiant(s)",
                'link'    => route('formations.index'),
            ];
        }

        return view('dashboard', compact(
            'stats',
            'financial',
            'alerts',
            'recentFormations',
            'recentStudents',
            'days',
            'dayTotals',
            'months',
            'monthTotals',
            'cityLabels',
            'cityTotals',
            'statusLabels',
            'statusTotals',
            'yearLabels',
            'yearTotals',
            'revenueLabels',
            'revenueTotals'
        ));
    }
}
