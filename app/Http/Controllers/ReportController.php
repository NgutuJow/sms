<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\PayrollRecord;
use App\Models\FeeStructure;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function financialReports()
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::now()->startOfYear();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::now()->endOfMonth();

        // Income: Sum of all successful payments
        $totalIncome = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Expenses: Sum of operational expenses
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        // Payroll: Sum of all net salaries paid
        $totalPayroll = PayrollRecord::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('net_salary');

        $netIncome = $totalIncome - $totalExpenses - $totalPayroll;

        // Income Trends (Last 6 Months)
        $incomeTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $incomeTrends[] = [
                'month' => $month->format('M Y'),
                'total' => Payment::where('status', 'completed')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount')
            ];
        }

        // Expenses by Category
        $expensesByCategory = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        // Collection Rate
        $totalInvoiced = Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $collectionRate = $totalInvoiced > 0 ? round(($totalIncome / $totalInvoiced) * 100, 1) : 0;

        return view('pages.finance.reports.financial-reports', compact(
            'totalIncome', 'totalExpenses', 'totalPayroll', 'netIncome',
            'incomeTrends', 'expensesByCategory', 'startDate', 'endDate',
            'collectionRate', 'totalInvoiced'
        ));
    }

    public function yearEndSummary()
    {
        $year = request('year', date('Y'));
        
        $summary = [
            'income' => Payment::where('status', 'completed')->whereYear('created_at', $year)->sum('amount'),
            'expenses' => Expense::whereYear('expense_date', $year)->sum('amount'),
            'payroll' => PayrollRecord::where('status', 'paid')->whereYear('payment_date', $year)->sum('net_salary'),
            'outstanding' => Invoice::where('balance', '>', 0)->sum('balance'),
        ];

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => Carbon::create()->month($m)->format('F'),
                'income' => Payment::where('status', 'completed')->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('amount'),
                'expense' => Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('amount') + 
                             PayrollRecord::where('status', 'paid')->whereYear('payment_date', $year)->whereMonth('payment_date', $m)->sum('net_salary')
            ];
        }

        return view('pages.finance.reports.year-end', compact('summary', 'monthlyData', 'year'));
    }
}
