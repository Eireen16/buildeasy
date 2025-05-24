<x-guest-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Account Type</title>
</head>
<body>
    <h2>Register as:</h2>
    <a href="{{ url('register/customer') }}">
        <button>Customer</button>
    </a>
    <a href="{{ url('register/supplier') }}">
        <button>Supplier</button>
    </a>
</body>
</html>
</x-guest-layout>
