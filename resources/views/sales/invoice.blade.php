<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->invoice_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .invoice-header {
            margin-bottom: 20px;
        }

        .company-info {
            margin-bottom: 30px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            margin-top: 20px;
        }

        .footer {
            margin-top: 50px;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Invoice #: {{ $sale->invoice_no }}</p>
            <p>Date: {{ $sale->date->format('d M Y') }}</p>
        </div>

        <div class="company-info">
            <h3>{{ $company['name'] }}</h3>
            <p>{{ $company['address'] }}</p>
            <p>Phone: {{ $company['phone'] }}</p>
            <p>Email: {{ $company['email'] }}</p>
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th class="text-right">Unit Price (TK)</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Total (TK)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->details as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <table>
                <tr>
                    <td class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right">{{ number_format($sale->total_amount, 2) }} TK</td>
                </tr>
                @if ($sale->discount > 0)
                    <tr>
                        <td class="text-right"><strong>Discount:</strong></td>
                        <td class="text-right">-{{ number_format($sale->discount, 2) }} TK</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-right"><strong>VAT (5%):</strong></td>
                    <td class="text-right">{{ number_format($sale->vat, 2) }} TK</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Grand Total:</strong></td>
                    <td class="text-right">{{ number_format($sale->grand_total, 2) }} TK</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Paid Amount:</strong></td>
                    <td class="text-right">{{ number_format($sale->paid_amount, 2) }} TK</td>
                </tr>
                @if ($sale->due_amount > 0)
                    <tr>
                        <td class="text-right"><strong>Due Amount:</strong></td>
                        <td class="text-right">{{ number_format($sale->due_amount, 2) }} TK</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Payment Terms: Due on receipt</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
