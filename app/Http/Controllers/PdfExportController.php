<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\PayrollRecord;
use App\Models\Invoice;
use App\Models\School;

class PdfExportController extends Controller
{
    private function getSchool()
    {
        return School::first() ?? (object)[
            'name' => 'School Management System',
            'email' => 'info@school.com',
            'phone' => '+255 000 000 000',
            'address' => 'Institutional Address',
            'region' => 'N/A',
            'district' => 'N/A'
        ];
    }

    public function index()
    {
        return view('pages.finance.pdf-export.index');
    }

    public function financialReport(Request $request)
    {
        $school = $this->getSchool();
        $startDate = $request->query('start_date') ? \Carbon\Carbon::parse($request->query('start_date')) : now()->startOfMonth();
        $endDate = $request->query('end_date') ? \Carbon\Carbon::parse($request->query('end_date')) : now()->endOfMonth();

        $totalIncome = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        
        $totalPayroll = PayrollRecord::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('net_salary');
            
        $netIncome = $totalIncome - $totalExpenses - $totalPayroll;

        $pdf = Pdf::loadView('pages.finance.pdf-export.financial-report', compact('school', 'startDate', 'endDate', 'totalIncome', 'totalExpenses', 'totalPayroll', 'netIncome'));

        return $pdf->download('finance-summary-' . now()->format('Ymd-His') . '.pdf');
    }

    public function expenseReport(Request $request)
    {
        $school = $this->getSchool();
        $expenses = Expense::latest()->get();
        $pdf = Pdf::loadView('pages.finance.pdf-export.expense-report', compact('school', 'expenses'));

        return $pdf->download('expense-report-' . now()->format('Ymd-His') . '.pdf');
    }

    public function payrollReport(Request $request)
    {
        $school = $this->getSchool();
        $payrollRecords = PayrollRecord::where('status', 'paid')->latest()->get();
        $pdf = Pdf::loadView('pages.finance.pdf-export.payroll-report', compact('school', 'payrollRecords'));

        return $pdf->download('payroll-report-' . now()->format('Ymd-His') . '.pdf');
    }

    public function yearEndReport(Request $request)
    {
        $school = $this->getSchool();
        $year = $request->query('year', date('Y'));
        
        $summary = [
            'income' => Payment::where('status', 'completed')->whereYear('created_at', $year)->sum('amount'),
            'expenses' => Expense::whereYear('expense_date', $year)->sum('amount'),
            'payroll' => PayrollRecord::where('status', 'paid')->whereYear('payment_date', $year)->sum('net_salary'),
            'outstanding' => Invoice::where('balance', '>', 0)->sum('balance'),
        ];

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => \Carbon\Carbon::create()->month($m)->format('F'),
                'income' => Payment::where('status', 'completed')->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('amount'),
                'expense' => Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('amount') + 
                             PayrollRecord::where('status', 'paid')->whereYear('payment_date', $year)->whereMonth('payment_date', $m)->sum('net_salary')
            ];
        }

        $pdf = Pdf::loadView('pages.finance.pdf-export.year-end-report', compact('school', 'summary', 'monthlyData', 'year'));

        return $pdf->download('year-end-summary-' . $year . '.pdf');
    }
}
