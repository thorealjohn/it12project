<!-- resources/views/reports/exports/customers_pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            margin-top: 0;
            color: #00B8D4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            margin-bottom: 5px;
        }
        .summary {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
                    }
        .summary-item {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .badge-success {
            background-color: #d1e7dd;
            color: #0a5226;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #41464b;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water Refilling Station</h1>
        <h2>Customer Report</h2>
        <p>Period: {{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }}</p>
        <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
    
    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-item">
            <strong>Total Customers:</strong> {{ $customers->count() }}
        </div>
        <div class="summary-item">
            <strong>Regular Customers:</strong> {{ $customers->where('is_regular', true)->count() }}
        </div>
        <div class="summary-item">
            <strong>Non-Regular Customers:</strong> {{ $customers->where('is_regular', false)->count() }}
        </div>
    </div>
    
    <h3>Customer Details</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Avg. Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->is_regular ? 'Regular' : 'Non-Regular' }}</td>
                <td>{{ $customer->orders_count }}</td>
                <td>₱{{ number_format($customer->total_spent, 2) }}</td>
                <td>₱{{ number_format($customer->orders_count > 0 ? $customer->total_spent / $customer->orders_count : 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Prepared by: {{ Auth::user()->name }}</p>
        <p>© {{ date('Y') }} <span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water Refilling Station. All rights reserved.</p>
    </div>
</body>
</html>