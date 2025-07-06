<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SaleController extends Controller
{
    public function index()
    {

        $sales = Sale::with('details')->latest()->paginate(20);
        // $sales = Sale::with('customer')->latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('current_stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'invoice_no' => 'required|string|unique:sales',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $sale = Sale::create([
                'invoice_no' => $request->invoice_no,
                'date' => $request->date,
                'total_amount' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'vat' => $request->vat,
                'grand_total' => $request->grand_total,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->due_amount,
                'notes' => $request->notes,
            ]);


            foreach ($request->products as $key => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = $request->quantities[$key];

                if ($product->current_stock < $quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->current_stock}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $product->sell_price,
                    'total_price' => $quantity * $product->sell_price,
                ]);

                $product->decrement('current_stock', $quantity);
            }

            $this->createJournalEntries($sale);

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create sale: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('details.product');
        return view('sales.show', compact('sale'));
    }

    protected function createJournalEntries(Sale $sale)
    {
        $accounts = [
            'sales' => Account::where('name', 'Sales')->first(),
            'vat' => Account::where('name', 'VAT Payable')->first(),
            'discount' => Account::where('name', 'Discount Allowed')->first(),
            'cash' => Account::where('name', 'Cash')->first(),
            'receivable' => Account::where('name', 'Accounts Receivable')->first(),
        ];

        JournalEntry::create([
            'date' => $sale->date,
            'account_id' => $accounts['sales']->id,
            'credit' => $sale->total_amount,
            'reference_type' => Sale::class,
            'reference_id' => $sale->id,
            'description' => 'Sales revenue for invoice #' . $sale->invoice_no,
        ]);

        if ($sale->vat > 0) {
            JournalEntry::create([
                'date' => $sale->date,
                'account_id' => $accounts['vat']->id,
                'credit' => $sale->vat,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'VAT for invoice #' . $sale->invoice_no,
            ]);
        }

        if ($sale->discount > 0) {
            JournalEntry::create([
                'date' => $sale->date,
                'account_id' => $accounts['discount']->id,
                'debit' => $sale->discount,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Discount for invoice #' . $sale->invoice_no,
            ]);
        }

        if ($sale->paid_amount > 0) {
            JournalEntry::create([
                'date' => $sale->date,
                'account_id' => $accounts['cash']->id,
                'debit' => $sale->paid_amount,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Cash received for invoice #' . $sale->invoice_no,
            ]);
        }

        if ($sale->due_amount > 0) {
            JournalEntry::create([
                'date' => $sale->date,
                'account_id' => $accounts['receivable']->id,
                'debit' => $sale->due_amount,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Due amount for invoice #' . $sale->invoice_no,
            ]);
        }
    }


    public function recordPayment(Request $request, Sale $sale)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $sale->due_amount],
            'payment_date' => ['required', 'date']
        ]);

        DB::beginTransaction();
        try {
            $sale->increment('paid_amount', $request->amount);
            $sale->decrement('due_amount', $request->amount);
            $sale->save();

            $cashAccount = Account::where('name', 'Cash')->firstOrFail();
            $receivableAccount = Account::where('name', 'Accounts Receivable')->firstOrFail();

            JournalEntry::create([
                'date' => $request->payment_date,
                'account_id' => $cashAccount->id,
                'debit' => $request->amount,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Payment received for invoice #' . $sale->invoice_no,
            ]);

            JournalEntry::create([
                'date' => $request->payment_date,
                'account_id' => $receivableAccount->id,
                'credit' => $request->amount,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Payment received for invoice #' . $sale->invoice_no,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Payment of ' . number_format($request->amount, 2) . ' TK recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }


    public function invoice(Sale $sale)
    {
        $sale->load(['details.product']);

        return view('sales.invoice', [
            'sale' => $sale,
            'company' => [
                'name' => 'Your Company Name',
                'address' => '123 Business Street, City',
                'phone' => '+880 1234 567890',
                'email' => 'info@yourcompany.com'
            ]
        ]);
    }


    public function storePayment(Request $request, Sale $sale)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $sale->due_amount],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string']
        ]);

        DB::beginTransaction();
        try {
            $payment = $sale->payments()->create([
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            $sale->increment('paid_amount', $request->amount);
            $sale->decrement('due_amount', $request->amount);

            if ($sale->due_amount <= 0) {
                $sale->update(['status' => 'paid']);
            }

            $cashAccount = Account::where('name', 'Cash')->firstOrFail();

            JournalEntry::create([
                'date' => $request->payment_date,
                'account_id' => $cashAccount->id,
                'debit' => $request->amount,
                'reference_type' => Payment::class,
                'reference_id' => $payment->id,
                'description' => 'Payment received for invoice #' . $sale->invoice_no,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment of ' . number_format($request->amount, 2) . ' TK recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
}
