<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'invoices');
        
        $data = [
            'tab' => $tab,
            'stats' => $this->getPaymentStats(),
        ];

        switch ($tab) {
            case 'invoices':
                $data['invoices'] = $this->getInvoices($request);
                break;
            case 'payments':
                $data['payments'] = $this->getPayments($request);
                break;
            case 'pending':
                $data['pendingPayments'] = $this->getPendingPayments($request);
                break;
            case 'reports':
                $data['reports'] = $this->getReports($request);
                break;
        }

        return view('admin.payments.index', $data);
    }

    public function create()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.payments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,success,failed,cancelled'
        ]);

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'status' => $request->status,
            'transaction_id' => 'TXN-' . time() . '-' . rand(1000, 9999),
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment berhasil dibuat');
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'invoice']);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.payments.edit', compact('payment', 'users'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,success,failed,cancelled'
        ]);

        $payment->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment berhasil diupdate');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment berhasil dihapus');
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed,cancelled'
        ]);

        $payment->status = $request->status;
        
        if ($request->status === 'success') {
            $payment->processed_at = now();
            if ($payment->invoice) {
                $payment->invoice->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);
            }
        }
        
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diubah'
        ]);
    }

    public function pending()
    {
        $payments = Payment::where('status', 'pending')->with(['user', 'invoice'])->latest()->paginate(15);
        return view('admin.payments.pending', compact('payments'));
    }

    public function completed()
    {
        $payments = Payment::where('status', 'success')->with(['user', 'invoice'])->latest()->paginate(15);
        return view('admin.payments.completed', compact('payments'));
    }

    public function failed()
    {
        $payments = Payment::where('status', 'failed')->with(['user', 'invoice'])->latest()->paginate(15);
        return view('admin.payments.failed', compact('payments'));
    }

    public function reports()
    {
        $data = $this->getReports(request());
        return view('admin.payments.reports', $data);
    }

    public function export(Request $request)
    {
        $payments = $this->getPayments($request);
        return response()->streamDownload(function() use ($payments) {
            echo "Date,Transaction ID,User,Amount,Method,Status\n";
            foreach ($payments as $payment) {
                echo implode(',', [
                    $payment->created_at->format('Y-m-d'),
                    $payment->transaction_id,
                    $payment->user->name ?? 'N/A',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->status
                ]) . "\n";
            }
        }, 'payments-export.csv');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        Payment::whereIn('id', $request->payment_ids)->update([
            'status' => 'success',
            'processed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payments berhasil disetujui'
        ]);
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        Payment::whereIn('id', $request->payment_ids)->update([
            'status' => 'failed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payments berhasil ditolak'
        ]);
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $year = $request->get('year', now()->year);

        if ($period === 'monthly') {
            $data = $this->getMonthlyRevenueChart($year);
        } else {
            $data = $this->getYearlyRevenueChart();
        }

        return response()->json($data);
    }

    private function getPaymentStats()
    {
        return [
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
            'failed_invoices' => Invoice::where('status', 'failed')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_amount' => Invoice::where('status', 'pending')->sum('total_amount'),
            'monthly_revenue' => Invoice::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('total_amount'),
        ];
    }

    private function getInvoices(Request $request)
    {
        $query = Invoice::with(['user', 'package']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->latest()->paginate(15);
    }

    private function getPayments(Request $request)
    {
        $query = Payment::with(['user', 'invoice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->latest()->paginate(15);
    }

    private function getPendingPayments(Request $request)
    {
        $query = Invoice::with(['user', 'package'])
                       ->where('status', 'pending');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('overdue')) {
            if ($request->overdue === 'yes') {
                $query->where('due_date', '<', now());
            } else {
                $query->where('due_date', '>=', now());
            }
        }

        return $query->orderBy('due_date', 'asc')->paginate(15);
    }

    private function getReports(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $data = [];

        if ($period === 'monthly') {
            $data['monthly_chart'] = $this->getMonthlyRevenueChart($year);
            $data['monthly_stats'] = $this->getMonthlyStats($year, $month);
        } elseif ($period === 'yearly') {
            $data['yearly_chart'] = $this->getYearlyRevenueChart();
            $data['yearly_stats'] = $this->getYearlyStats($year);
        }

        $data['top_packages'] = $this->getTopPackages($year, $month);
        $data['payment_methods'] = $this->getPaymentMethodStats($year, $month);
        
        return $data;
    }

    private function getMonthlyRevenueChart($year)
    {
        $monthlyData = Invoice::where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->select(
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyData->where('month', $i)->first();
            $chartData[] = [
                'month' => Carbon::create($year, $i)->format('M'),
                'revenue' => $monthData ? $monthData->total : 0,
                'count' => $monthData ? $monthData->count : 0,
            ];
        }

        return $chartData;
    }

    private function getYearlyRevenueChart()
    {
        return Invoice::where('status', 'paid')
            ->select(
                DB::raw('YEAR(paid_at) as year'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->limit(5)
            ->get();
    }

    private function getMonthlyStats($year, $month)
    {
        $currentMonth = Invoice::where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month);

        $previousMonth = Invoice::where('status', 'paid')
            ->whereYear('paid_at', $month == 1 ? $year - 1 : $year)
            ->whereMonth('paid_at', $month == 1 ? 12 : $month - 1);

        $currentRevenue = $currentMonth->sum('total_amount');
        $previousRevenue = $previousMonth->sum('total_amount');
        $currentCount = $currentMonth->count();
        $previousCount = $previousMonth->count();

        return [
            'current_revenue' => $currentRevenue,
            'previous_revenue' => $previousRevenue,
            'revenue_growth' => $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0,
            'current_count' => $currentCount,
            'previous_count' => $previousCount,
            'count_growth' => $previousCount > 0 ? (($currentCount - $previousCount) / $previousCount) * 100 : 0,
        ];
    }

    private function getYearlyStats($year)
    {
        $currentYear = Invoice::where('status', 'paid')
            ->whereYear('paid_at', $year);

        $previousYear = Invoice::where('status', 'paid')
            ->whereYear('paid_at', $year - 1);

        $currentRevenue = $currentYear->sum('total_amount');
        $previousRevenue = $previousYear->sum('total_amount');
        $currentCount = $currentYear->count();
        $previousCount = $previousYear->count();

        return [
            'current_revenue' => $currentRevenue,
            'previous_revenue' => $previousRevenue,
            'revenue_growth' => $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0,
            'current_count' => $currentCount,
            'previous_count' => $previousCount,
            'count_growth' => $previousCount > 0 ? (($currentCount - $previousCount) / $previousCount) * 100 : 0,
        ];
    }

    private function getTopPackages($year, $month)
    {
        return Invoice::with('package')
            ->where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month)
            ->whereNotNull('package_id')
            ->select('package_id', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('package_id')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();
    }

    private function getPaymentMethodStats($year, $month)
    {
        return Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->orderBy('count', 'desc')
            ->get();
    }

    public function updateInvoiceStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed,cancelled,refunded',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $invoice->status;
        $invoice->status = $request->status;
        
        if ($request->status === 'paid' && $oldStatus !== 'paid') {
            $invoice->paid_at = now();
        }
        
        if ($request->filled('notes')) {
            $invoice->notes = $request->notes;
        }
        
        $invoice->save();

        return response()->json([
            'success' => true,
            'message' => 'Status invoice berhasil diubah'
        ]);
    }

    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed,cancelled'
        ]);

        $payment->status = $request->status;
        
        if ($request->status === 'success') {
            $payment->processed_at = now();
            $payment->invoice->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);
        }
        
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diubah'
        ]);
    }
}