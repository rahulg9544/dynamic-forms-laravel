<!-- resources/views/admin/forms/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Forms</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Forms</h1>
        <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">Create New Form</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($forms as $form)
                    <tr>
                        <td>{{ $form->name }}</td>
                        <td>
                            <!-- <a href="{{ route('admin.forms.edit', $form) }}" class="btn btn-warning">Edit</a> -->
                            <form action="{{ route('admin.forms.destroy', $form->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
