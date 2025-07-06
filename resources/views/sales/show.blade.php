@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sale Invoice #{{ $sale->invoice_no }}</h5>
                <div>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Sales
                    </a>
                    <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-sm btn-primary" target="_blank">
                        <i class="bi bi-printer"></i> Print Invoice
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Sale Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $sale->date->format('d M Y') }}</p>
                        <p><strong>Status:</strong>
                            @if ($sale->due_amount <= 0)
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning text-dark">Due: {{ number_format($sale->due_amount, 2) }}
                                    TK</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Subtotal:</strong> {{ number_format($sale->total_amount, 2) }} TK</p>
                        <p><strong>Discount:</strong> {{ number_format($sale->discount, 2) }} TK</p>
                        <p><strong>VAT (5%):</strong> {{ number_format($sale->vat, 2) }} TK</p>
                        <h5><strong>Grand Total:</strong> {{ number_format($sale->grand_total, 2) }} TK</h5>
                    </div>
                </div>

                <!-- Sale Items -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->details as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }} TK</td>
                                    <td class="text-end">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->total_price, 2) }} TK</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Payment Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Payment Summary</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Paid Amount:</strong> {{ number_format($sale->paid_amount, 2) }} TK</p>
                                <p><strong>Due Amount:</strong> {{ number_format($sale->due_amount, 2) }} TK</p>
                                @if ($sale->notes)
                                    <p><strong>Notes:</strong> {{ $sale->notes }}</p>
                                @endif

                                <!-- Payment History -->
                                @if ($sale->payments->count() > 0)
                                    <div class="mt-3">
                                        <h6>Payment History</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Method</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sale->payments as $payment)
                                                        <tr>
                                                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                                            <td>{{ number_format($payment->amount, 2) }} TK</td>
                                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($sale->due_amount > 0)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Receive Payment</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('sales.payment', $sale->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Amount (TK)</label>
                                            <input type="number" name="amount" class="form-control"
                                                value="{{ number_format(min($sale->due_amount, $sale->grand_total), 2) }}"
                                                max="{{ $sale->due_amount }}" step="0.01" min="0.01" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Payment Date</label>
                                            <input type="date" name="payment_date" class="form-control"
                                                value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select name="payment_method" class="form-select" required>
                                                <option value="cash">Cash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="check">Check</option>
                                                <option value="mobile_payment">Mobile Payment</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" rows="2"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-cash"></i> Record Payment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-header h5,
        .card-header h6 {
            margin-bottom: 0;
        }

        .table th {
            white-space: nowrap;
        }

        .table-sm {
            font-size: 0.875rem;
        }
    </style>
@endpush
