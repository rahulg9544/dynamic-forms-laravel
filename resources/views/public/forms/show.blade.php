<!DOCTYPE html>
<html>
<head>
    <title>{{ $form->name }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>{{ $form->name }}</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('public.forms.submit', ['id' => $form->id]) }}" method="POST">
            @csrf
            @if($fields && $fields->isNotEmpty())
                @foreach($fields as $field)
                    <div class="form-group">
                        <label for="{{ $field->id }}">{{ $field->label }}</label>
                        @if($field->type === 'text')
                            <input type="text" class="form-control" id="{{ $field->id }}" name="fields[{{ $field->id }}]" required>
                        @elseif($field->type === 'number')
                            <input type="number" class="form-control" id="{{ $field->id }}" name="fields[{{ $field->id }}]" required>
                        @elseif($field->type === 'dropdown')
                            <select class="form-control" id="{{ $field->id }}" name="fields[{{ $field->id }}]" required>
                                @foreach(json_decode($field->options) as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                @endforeach
            @else
                <p>No fields available for this form.</p>
            @endif
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
