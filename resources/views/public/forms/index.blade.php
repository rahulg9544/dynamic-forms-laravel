<!DOCTYPE html>
<html>
<head>
    <title>List of Forms</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>List of Forms</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($forms->isEmpty())
            <p>No forms available.</p>
        @else
            <ul class="list-group">
                @foreach($forms as $form)
                    <li class="list-group-item">
                        <a href="{{ route('public.forms.show', ['id' => $form->id]) }}">{{ $form->name }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
</html>
