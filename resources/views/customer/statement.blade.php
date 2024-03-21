<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Customer Statement</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="" rel="stylesheet" />
    </head>
    <body class="antialiased">
        <h1>{{ $customer->name }}</h1>
        @foreach ($customer->rentals as $rental)
            <div>{{ $rental->movie->title }}: {{ $rental->amount }}</div>
        @endforeach
        <p style="font-style: italic;">Amount owed is {{$customer->rentals_sum_amount}}</p>
        <p style="font-style: italic;">You earned {{ $customer->rentals_sum_frequent_points }} frequent renter points</p>
    </body>
</html>
