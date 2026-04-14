<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
</head>
<body>

<h1>Transactions</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Date</th>
        <th>Category</th>
    </tr>

    @foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction->id }}</td>
            <td>{{ $transaction->amount }}</td>
            <td>{{ $transaction->description }}</td>
            <td>{{ $transaction->transaction_date }}</td>
            <td>{{ $transaction->category }}</td>
        </tr>
    @endforeach

</table>

</body>
</html>
