@extends('layouts.app')

@section('content')
    <h4>Report Summary ({{ request('start_date') }} to {{ request('end_date') }})</h4>

    <ul class="list-group mb-3">
        <li class="list-group-item">Total Sales: <strong>{{ $total_sales }} TK</strong></li>
        <li class="list-group-item">Total Discount: <strong>{{ $total_discount }} TK</strong></li>
        <li class="list-group-item">Total VAT Collected: <strong>{{ $total_vat }} TK</strong></li>
        <li class="list-group-item">Total Paid: <strong>{{ $total_paid }} TK</strong></li>
        <li class="list-group-item">Total Due: <strong>{{ $total_due }} TK</strong></li>
    </ul>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Total</th>
                <th>Discount</th>
                <th>VAT</th>
                <th>Paid</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_date }}</td>
                    <td>{{ $sale->total_amount }}</td>
                    <td>{{ $sale->discount }}</td>
                    <td>{{ $sale->vat_amount }}</td>
                    <td>{{ $sale->paid_amount }}</td>
                    <td>{{ $sale->due_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
