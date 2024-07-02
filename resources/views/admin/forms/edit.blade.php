<!DOCTYPE html>
<html>
<head>
    <title>Edit Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Form</h1>
        <form action="{{ route('admin.forms.update', $form->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Form Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $form->name }}" required>
            </div>

            <div id="form-fields">
                @php $fieldCount = $form->fields->count() @endphp
                @if ($fieldCount > 0)
                    @foreach($form->fields as $index => $field)
                        <div class="form-group">
                            <label for="field{{ $index + 1 }}_label">Label</label>
                            <input type="text" class="form-control" id="field{{ $index + 1 }}_label" name="fields[label][]" value="{{ $field->label }}" required>
                        </div>
          
                        <div class="form-group">
                            <label for="field{{ $index + 1 }}_type">HTML Field</label>
                            <select class="form-control" id="field{{ $index + 1 }}_type" name="fields[type][]" onchange="showOptions({{ $index + 1 }})">
                                <option value="text" {{ $field->type === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="number" {{ $field->type === 'number' ? 'selected' : '' }}>Number</option>
                                <option value="dropdown" {{ $field->type === 'dropdown' ? 'selected' : '' }}>Drop Down</option>
                            </select>
                        </div>
                        <div id="field{{ $index + 1 }}_options" class="form-group" style="{{ $field->type === 'dropdown' ? 'display: block;' : 'display: none;' }}">
                            <label for="field{{ $index + 1 }}_options">Options (comma separated)</label>
                            <input type="text" class="form-control" id="field{{ $index + 1 }}_options" name="fields[options][]" value="{{ $field->options ? implode(',', json_decode($field->options)) : '' }}">
                        </div>
                        
                        <hr>
                    @endforeach
                @else
                    <p>No fields found.</p>
                @endif
            </div>

            <button type="button" class="btn btn-primary mb-3" onclick="addField()">Add Field</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        var fieldIndex = <?php echo $fieldCount ?? 0 ?>;

        function addField() {
            fieldIndex++;

            var html = `
                <div class="form-group">
                    <label for="field${fieldIndex}_label">Label</label>
                    <input type="text" class="form-control" id="field${fieldIndex}_label" name="fields[label][]">
                </div>
                <div class="form-group">
                    <label for="field${fieldIndex}_type">HTML Field</label>
                    <select class="form-control" id="field${fieldIndex}_type" name="fields[type][]" onchange="showOptions(${fieldIndex})">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="dropdown">Drop Down</option>
                    </select>
                </div>
                <div id="field${fieldIndex}_options" class="form-group" style="display: none;">
                    <label for="field${fieldIndex}_options">Options (comma separated)</label>
                    <input type="text" class="form-control" id="field${fieldIndex}_options" name="fields[options][]">
                </div>
         
            `;

            var div = document.createElement('div');
            div.innerHTML = html;
            document.getElementById('form-fields').appendChild(div);
        }

        function showOptions(index) {
            var type = document.getElementById(`field${index}_type`).value;
            var optionsDiv = document.getElementById(`field${index}_options`);

            if (type === 'dropdown') {
                optionsDiv.style.display = 'block';
            } else {
                optionsDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>
