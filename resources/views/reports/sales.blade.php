@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Sales Report</h5>
                <div>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Reports
                    </a>
                    <button class="btn btn-sm btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.sales') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->invoice_no }}</td>
                                    <td>{{ $sale->date->format('d M Y') }}</td>
                                    <td class="text-end">{{ number_format($sale->grand_total, 2) }} TK</td>
                                    <td class="text-end">{{ number_format($sale->paid_amount, 2) }} TK</td>
                                    <td class="text-end">{{ number_format($sale->due_amount, 2) }} TK</td>
                                    <td>
                                        @if ($sale->due_amount <= 0)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Due</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Totals</th>
                                <th class="text-end">{{ number_format($totalSales, 2) }} TK</th>
                                <th class="text-end">{{ number_format($totalPaid, 2) }} TK</th>
                                <th class="text-end">{{ number_format($totalDue, 2) }} TK</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
