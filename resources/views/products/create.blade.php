@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Create New Sale</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" class="form-control" value="INV-{{ time() }}"
                            readonly>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="saleItems">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="products[]" class="form-select product-select">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}"
                                                data-stock="{{ $product->current_stock }}">
                                                {{ $product->name }} ({{ $product->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="quantities[]" class="form-control quantity" min="1"
                                        value="1"></td>
                                <td><input type="text" name="prices[]" class="form-control price" readonly></td>
                                <td><input type="text" name="totals[]" class="form-control total" readonly></td>
                                <td><button type="button" class="btn btn-danger remove-row"><i
                                            class="bi bi-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="addRow" class="btn btn-secondary">
                        <i class="bi bi-plus-circle me-1"></i> Add Item
                    </button>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Subtotal</label>
                        <input type="text" name="subtotal" id="subtotal" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Discount</label>
                        <input type="number" name="discount" id="discount" class="form-control" value="0"
                            min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">VAT (5%)</label>
                        <input type="text" name="vat" id="vat" class="form-control" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Grand Total</label>
                        <input type="text" name="grand_total" id="grandTotal" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" name="paid_amount" id="paidAmount" class="form-control" value="0"
                            min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Due Amount</label>
                    <input type="text" name="due_amount" id="dueAmount" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Sale</button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#addRow').click(function() {
                    const newRow = `
                <tr>
                    <td>
                        <select name="products[]" class="form-select product-select">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" 
                                data-price="{{ $product->sell_price }}"
                                data-stock="{{ $product->current_stock }}">
                                {{ $product->name }} ({{ $product->code }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="quantities[]" class="form-control quantity" min="1" value="1"></td>
                    <td><input type="text" name="prices[]" class="form-control price" readonly></td>
                    <td><input type="text" name="totals[]" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row"><i class="bi bi-trash"></i></button></td>
                </tr>
            `;
                    $('#saleItems tbody').append(newRow);
                });


                $(document).on('click', '.remove-row', function() {
                    if ($('#saleItems tbody tr').length > 1) {
                        $(this).closest('tr').remove();
                        calculateTotals();
                    }
                });


                $(document).on('change', '.product-select', function() {
                    const price = $(this).find(':selected').data('price');
                    const stock = $(this).find(':selected').data('stock');
                    const row = $(this).closest('tr');

                    row.find('.price').val(price);
                    row.find('.quantity').attr('max', stock);
                    calculateRowTotal(row);
                    calculateTotals();
                });


                $(document).on('change', '.quantity', function() {
                    const row = $(this).closest('tr');
                    calculateRowTotal(row);
                    calculateTotals();
                });


                $(document).on('change', '#discount', function() {
                    calculateTotals();
                });


                $(document).on('change', '#paidAmount', function() {
                    calculateTotals();
                });

                function calculateRowTotal(row) {
                    const quantity = parseFloat(row.find('.quantity').val()) || 0;
                    const price = parseFloat(row.find('.price').val()) || 0;
                    const total = quantity * price;
                    row.find('.total').val(total.toFixed(2));
                }

                function calculateTotals() {
                    let subtotal = 0;
                    $('.total').each(function() {
                        subtotal += parseFloat($(this).val()) || 0;
                    });

                    const discount = parseFloat($('#discount').val()) || 0;
                    const vat = (subtotal - discount) * 0.05;
                    const grandTotal = (subtotal - discount) + vat;
                    const paidAmount = parseFloat($('#paidAmount').val()) || 0;
                    const dueAmount = grandTotal - paidAmount;

                    $('#subtotal').val(subtotal.toFixed(2));
                    $('#vat').val(vat.toFixed(2));
                    $('#grandTotal').val(grandTotal.toFixed(2));
                    $('#dueAmount').val(dueAmount.toFixed(2));
                }
            });
        </script>
    @endpush
@endsection
