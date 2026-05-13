<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Report - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-returned {
            color: #17a2b8;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 5px;">
            🖨️ Print Report
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="header">
        <h1>School Equipment Borrowing System</h1>
        <h2>Borrowing Report</h2>
        <p>Generated on: {{ date('F d, Y h:i A') }}</p>
        @if(request('date_from') || request('date_to'))
            <p>
                Period: 
                {{ request('date_from') ? date('M d, Y', strtotime(request('date_from'))) : 'Start' }} 
                - 
                {{ request('date_to') ? date('M d, Y', strtotime(request('date_to'))) : 'End' }}
            </p>
        @endif
        @if(request('category'))
            <p>Category: {{ ucfirst(request('category')) }}</p>
        @endif
        @if(request('status'))
            <p>Status: {{ ucfirst(request('status')) }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Borrower</th>
                <th>Email</th>
                <th>Equipment</th>
                <th>Category</th>
                <th>Serial No.</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->id }}</td>
                <td>{{ $borrowing->created_at->format('M d, Y') }}</td>
                <td>{{ $borrowing->user->name }}</td>
                <td>{{ $borrowing->user->email }}</td>
                <td>{{ $borrowing->equipment->name }}</td>
                <td>{{ ucfirst($borrowing->equipment->category) }}</td>
                <td>{{ $borrowing->equipment->serial_number }}</td>
                <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                <td>{{ $borrowing->return_date->format('M d, Y') }}</td>
                <td class="status-{{ $borrowing->status }}">
                    {{ ucfirst($borrowing->status) }}
                </td>
                <td>{{ $borrowing->remarks ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 20px;">
                    No borrowing records found for the selected filters.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; display: flex; justify-content: space-between;">
        <div>
            <strong>Total Records:</strong> {{ $borrowings->count() }}
        </div>
        <div>
            <strong>Approved:</strong> {{ $borrowings->where('status', 'approved')->count() }} | 
            <strong>Pending:</strong> {{ $borrowings->where('status', 'pending')->count() }} | 
            <strong>Rejected:</strong> {{ $borrowings->where('status', 'rejected')->count() }} | 
            <strong>Returned:</strong> {{ $borrowings->where('status', 'returned')->count() }}
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated report.</p>
        <p>School Equipment Borrowing System &copy; {{ date('Y') }}</p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Uncomment the line below to auto-print
            // window.print();
        }
    </script>
</body>
</html>