

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Admin Dashboard</h1>
        <p>You are logged in as {{ Auth::user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <a href="{{ route('admin.forms.index') }}" class="btn btn-primary mt-3">Manage Forms</a>
    </div>
</body>
</html>
