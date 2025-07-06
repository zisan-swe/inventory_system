@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-2">Total Sales</h6>
                                <h2 class="mb-0">{{ number_format($totalSales, 2) }} TK</h2>
                            </div>
                            <div class="icon-shape bg-white text-primary rounded-circle p-3">
                                <i class="bi bi-cart-check fs-3"></i>
                            </div>
                        </div>
                        <p class="mt-3 mb-0">
                            <span class="me-2">{{ $salesCount }} transactions</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-2">Total Revenue</h6>
                                <h2 class="mb-0">{{ number_format($totalRevenue, 2) }} TK</h2>
                            </div>
                            <div class="icon-shape bg-white text-success rounded-circle p-3">
                                <i class="bi bi-currency-dollar fs-3"></i>
                            </div>
                        </div>
                        <p class="mt-3 mb-0">
                            <span class="me-2">{{ $paidPercentage }}% paid</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-2">Outstanding</h6>
                                <h2 class="mb-0">{{ number_format($totalDue, 2) }} TK</h2>
                            </div>
                            <div class="icon-shape bg-white text-warning rounded-circle p-3">
                                <i class="bi bi-clock-history fs-3"></i>
                            </div>
                        </div>
                        <p class="mt-3 mb-0">
                            <span class="me-2">{{ $dueCount }} unpaid invoices</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-2">Inventory Value</h6>
                                <h2 class="mb-0">{{ number_format($inventoryValue, 2) }} TK</h2>
                            </div>
                            <div class="icon-shape bg-white text-info rounded-circle p-3">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                        </div>
                        <p class="mt-3 mb-0">
                            <span class="me-2">{{ $productCount }} products</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sales Chart -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">Sales Performance (Last 30 Days)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">Top Selling Products</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach ($topProducts as $product)
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $product->name }}</h6>
                                            <small class="text-muted">{{ $product->code }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold">{{ $product->total_sold }} sold</span><br>
                                            <small>{{ number_format($product->total_revenue, 2) }} TK</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Sales -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Recent Sales</h6>
                        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSales as $sale)
                                        <tr>
                                            <td>
                                                <a href="{{ route('sales.show', $sale->id) }}">{{ $sale->invoice_no }}</a>
                                            </td>
                                            <td>{{ $sale->date->format('M d') }}</td>
                                            <td>{{ number_format($sale->grand_total, 2) }} TK</td>
                                            <td>
                                                @if ($sale->due_amount <= 0)
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning">Due</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Low Stock Alert</h6>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Code</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->code }}</td>
                                            <td>
                                                <span
                                                    class="fw-bold {{ $product->current_stock < 5 ? 'text-danger' : 'text-warning' }}">
                                                    {{ $product->current_stock }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Restock
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($salesChart['labels']) !!},
                    datasets: [{
                        label: 'Daily Sales',
                        data: {!! json_encode($salesChart['data']) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: ' + context.parsed.y + ' TK';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' TK';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
