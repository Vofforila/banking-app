<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
</head>
<body>

<h1>Transactions</h1>

@foreach($transactions as $transaction)
    <div>
        {{ $transaction->amount }} -
        {{ $transaction->description }} -
        {{ $transaction->transaction_date }}
    </div>
@endforeach

</body>
</html>
