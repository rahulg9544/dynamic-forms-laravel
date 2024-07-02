<!DOCTYPE html>
<html>
<head>
    <title>Create Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Create Form</h1>
        <form action="{{ route('admin.forms.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Form Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div id="form-fields">
                <div class="form-group">
                    <label for="field1_label">Label</label>
                    <input type="text" class="form-control" id="field1_label" name="fields[label][]" required>
                </div>
             
                <div class="form-group">
                    <label for="field1_type">HTML Field</label>
                    <select class="form-control" id="field1_type" name="fields[type][]" required>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="dropdown">Drop Down</option>
                    </select>
                </div>
                <div id="field1_options" class="form-group" style="display: none;">
                    <label for="field1_options">Options (comma separated)</label>
                    <input type="text" class="form-control" id="field1_options" name="fields[options][]" placeholder="Option1,Option2,Option3">
                </div>
             
            </div>
            <button type="button" class="btn btn-primary mb-3" onclick="addField()">Add Field</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>

    <script>
        var fieldIndex = 1;

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

