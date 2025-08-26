<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Name;
use App\Models\Submit;
use App\Models\Transaction;
use App\RequestStatusEnum;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $successfulPayments = Transaction::where('status', 'success')->count();
        $totalAmount = Transaction::where('status', 'success')->sum('amount');
        $doneSubmits = Submit::where('request_status', RequestStatusEnum::DONE)->count();
        $requestedSubmits = Submit::where('request_status', RequestStatusEnum::REQUESTED)->count();
        $prepareSubmits = Submit::where('request_status', RequestStatusEnum::PREPARE)->count();
        
        // Get payment statistics for the current month (Persian calendar)
        $currentMonthPayments = $this->getCurrentMonthPayments();
        
        // Get recent requested submits
        $recentRequestedSubmits = Submit::where('request_status', RequestStatusEnum::REQUESTED)
            ->latest()
            ->take(10)
            ->get();
        
        // Get names with pagination
        $names = Name::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.dashboard', compact(
            'successfulPayments',
            'totalAmount',
            'doneSubmits',
            'requestedSubmits',
            'prepareSubmits',
            'recentRequestedSubmits',
            'names',
            'currentMonthPayments'
        ));
    }
    
    /**
     * Get payment statistics for the current month (Persian calendar)
     */
    private function getCurrentMonthPayments()
    {
        try {
            // Get current Persian date
            $currentPersianDate = \Morilog\Jalali\Jalalian::now();
            
            // Get start and end of current month in Gregorian
            $startOfCurrentMonth = $currentPersianDate->toCarbon()->startOfMonth();
            $endOfCurrentMonth = $currentPersianDate->toCarbon()->endOfMonth();
            
            // Get daily payment counts for current month
            $dailyPayments = Transaction::where('status', 'success')
                ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
            
            // Get Persian month names
            $persianMonths = [
                1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
                4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
                7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
                10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
            ];
            
            // Prepare data for chart
            $chartData = [
                'labels' => [],
                'data' => [],
                'monthName' => $persianMonths[$currentPersianDate->getMonth()]
            ];
            
            // Fill in data for each day of the month
            $currentDate = $startOfCurrentMonth->copy();
            while ($currentDate <= $endOfCurrentMonth) {
                $dateString = $currentDate->format('Y-m-d');
                $chartData['labels'][] = $currentDate->format('j'); // Day of month
                $chartData['data'][] = $dailyPayments->get($dateString)?->count ?? 0;
                $currentDate->addDay();
            }
            
            return $chartData;
        } catch (\Exception $e) {
            // Fallback to static data if Jalali package fails
            \Illuminate\Support\Facades\Log::warning('Failed to get Persian date data: ' . $e->getMessage());
            
            return [
                'labels' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
                'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                'monthName' => 'ماه جاری'
            ];
        }
    }
}
