@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit Product</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="code" class="form-label">Product Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                            name="code" value="{{ old('code', $product->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="purchase_price" class="form-label">Purchase Price (TK) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price"
                            name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                        @error('purchase_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="sell_price" class="form-label">Sell Price (TK) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('sell_price') is-invalid @enderror" id="sell_price" name="sell_price"
                            value="{{ old('sell_price', $product->sell_price) }}" required>
                        @error('sell_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="current_stock" class="form-label">Current Stock</label>
                        <input type="number" class="form-control @error('current_stock') is-invalid @enderror"
                            id="current_stock" name="current_stock"
                            value="{{ old('current_stock', $product->current_stock) }}" readonly>
                        @error('current_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="stock_adjustment" class="form-label">Stock Adjustment (+/-)</label>
                        <input type="number" class="form-control @error('stock_adjustment') is-invalid @enderror"
                            id="stock_adjustment" name="stock_adjustment" value="0">
                        <small class="text-muted">Enter positive number to add stock, negative to reduce</small>
                        @error('stock_adjustment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const purchasePrice = document.getElementById('purchase_price');
            const sellPrice = document.getElementById('sell_price');

            sellPrice.addEventListener('change', function() {
                if (parseFloat(sellPrice.value) <= parseFloat(purchasePrice.value)) {
                    alert('Sell price must be higher than purchase price');
                    sellPrice.value = (parseFloat(purchasePrice.value) + 1).toFixed(2);
                }
            });

            const currentStock = document.getElementById('current_stock');
            const stockAdjustment = document.getElementById('stock_adjustment');

            stockAdjustment.addEventListener('change', function() {
                const adjustment = parseInt(stockAdjustment.value);
                const current = parseInt(currentStock.value);

                if (current + adjustment < 0) {
                    alert('Stock cannot be negative');
                    stockAdjustment.value = 0;
                }
            });
        });
    </script>
@endpush
