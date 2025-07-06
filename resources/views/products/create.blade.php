@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Add New Product</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.store') }}">
                            @csrf


                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="code" class="form-label">Product Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" name="code" value="{{ old('code') }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="purchase_price" class="form-label">Purchase Price (TK) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('purchase_price') is-invalid @enderror"
                                        id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}"
                                        required>
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="sell_price" class="form-label">Selling Price (TK) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('sell_price') is-invalid @enderror" id="sell_price"
                                        name="sell_price" value="{{ old('sell_price') }}" required>
                                    @error('sell_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="opening_stock" class="form-label">Opening Stock <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0"
                                        class="form-control @error('opening_stock') is-invalid @enderror" id="opening_stock"
                                        name="opening_stock" value="{{ old('opening_stock', 0) }}" required>
                                    @error('opening_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="current_stock" class="form-label">Current Stock</label>
                                    <input type="number" min="0" class="form-control" id="current_stock"
                                        name="current_stock" value="{{ old('current_stock', 0) }}" readonly>
                                    <small class="text-muted">Automatically calculated</small>
                                </div>
                            </div>


                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Save Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const openingStock = document.getElementById('opening_stock');
                const currentStock = document.getElementById('current_stock');

                openingStock.addEventListener('change', function() {
                    currentStock.value = this.value;
                });


                const purchasePrice = document.getElementById('purchase_price');
                const sellPrice = document.getElementById('sell_price');

                sellPrice.addEventListener('change', function() {
                    if (parseFloat(this.value) <= parseFloat(purchasePrice.value)) {
                        alert('Selling price must be higher than purchase price');
                        this.value = (parseFloat(purchasePrice.value) + 1).toFixed(2);
                    }
                });
            });
        </script>
    @endpush
@endsection
