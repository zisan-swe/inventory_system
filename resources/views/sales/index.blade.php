@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sales List</h5>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> New Sale
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
                                <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                                <td class="text-end">{{ number_format($sale->grand_total, 2) }}</td>
                                <td class="text-end">{{ number_format($sale->paid_amount, 2) }}</td>
                                <td class="text-end {{ $sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($sale->due_amount, 2) }}
                                </td>
                                <td>
                                    @if ($sale->due_amount <= 0)
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Due</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info"
                                        title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-sm btn-secondary"
                                        title="Invoice">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    @if ($sale->due_amount > 0)
                                        <button class="btn btn-sm btn-success payment-btn" data-bs-toggle="modal"
                                            data-bs-target="#paymentModal" data-sale-id="{{ $sale->id }}"
                                            data-due-amount="{{ $sale->due_amount }}" title="Receive Payment">
                                            <i class="bi bi-cash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No sales records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($sales->count() > 0)
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Totals:</th>
                                <th class="text-end">{{ number_format($sales->sum('grand_total'), 2) }}</th>
                                <th class="text-end">{{ number_format($sales->sum('paid_amount'), 2) }}</th>
                                <th class="text-end">{{ number_format($sales->sum('due_amount'), 2) }}</th>
                                <th colspan="2"></th>
                            </tr>

                        </tfoot>
                    @endif
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Receive Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="paymentAmount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="paymentAmount" name="amount" step="0.01"
                                min="0.01" required>
                            <div class="form-text">Due Amount: <span id="dueAmountText">0.00</span></div>
                        </div>
                        <div class="mb-3">
                            <label for="paymentDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="paymentDate" name="date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="paymentNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="paymentNotes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Payment modal handling
                const paymentModal = document.getElementById('paymentModal');
                const paymentForm = document.getElementById('paymentForm');
                const paymentAmount = document.getElementById('paymentAmount');
                const dueAmountText = document.getElementById('dueAmountText');

                paymentModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const saleId = button.getAttribute('data-sale-id');
                    const dueAmount = button.getAttribute('data-due-amount');

                    paymentForm.action = `/sales/${saleId}/payment`;
                    paymentAmount.max = dueAmount;
                    paymentAmount.value = dueAmount;
                    dueAmountText.textContent = dueAmount;
                });

                // Initialize DataTable if needed
                // $('.table').DataTable();
            });
        </script>
    @endpush
@endsection
