<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; // Assuming you have dompdf installed

class InvoiceController extends Controller
{
    /**
     * Display a listing of user's invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['package', 'package.category', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice (AJAX).
     */
    public function show(Request $request, Invoice $invoice)
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $invoice->load(['package', 'package.category', 'user']);

        if ($request->ajax()) {
            $html = view('invoices.partials.invoice-detail', compact('invoice'))->render();
            
            $actions = '';
            if ($invoice->status === 'pending') {
                $actions .= '<a href="' . route('invoices.pay', $invoice->id) . '" class="btn btn-success">
                    <i class="fas fa-credit-card"></i> Pay Now
                </a>';
            }
            
            if ($invoice->status === 'paid') {
                $actions .= '<button type="button" class="btn btn-info" onclick="downloadInvoice(' . $invoice->id . ')">
                    <i class="fas fa-download"></i> Download PDF
                </button>';
            }
            
            $actions .= '<button type="button" class="btn btn-warning ml-2" onclick="printInvoice()">
                <i class="fas fa-print"></i> Print
            </button>';

            return response()->json([
                'html' => $html,
                'actions' => $actions
            ]);
        }

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Redirect to payment page.
     */
    public function pay(Invoice $invoice)
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if invoice is payable
        if ($invoice->status !== 'pending') {
            return redirect()->route('invoices.index')
                ->with('error', 'This invoice cannot be paid.');
        }

        // Redirect to payment gateway or payment page
        // This depends on your payment implementation
        return redirect()->route('payments.create', ['invoice' => $invoice->id]);
    }

    /**
     * Download invoice as PDF.
     */
    public function download(Invoice $invoice)
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if invoice is paid
        if ($invoice->status !== 'paid') {
            return redirect()->route('invoices.index')
                ->with('error', 'Only paid invoices can be downloaded.');
        }

        $invoice->load(['package', 'package.category', 'user']);

        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Display invoice details (full page view).
     */
    public function showFull(Invoice $invoice)
    {
        // Check if user owns this invoice or user is admin
        if ($invoice->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $invoice->load(['package', 'package.category', 'user']);

        return view('invoices.show', compact('invoice'));
    }
}