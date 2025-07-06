@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Inventory Report</h5>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Reports
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Code</th>
                                <th>Current Stock</th>
                                <th>Purchase Price</th>
                                <th>Sell Price</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->code }}</td>
                                    <td class="text-end">{{ $product->current_stock }}</td>
                                    <td class="text-end">{{ number_format($product->purchase_price, 2) }} TK</td>
                                    <td class="text-end">{{ number_format($product->sell_price, 2) }} TK</td>
                                    <td class="text-end">
                                        {{ number_format($product->current_stock * $product->purchase_price, 2) }} TK</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
