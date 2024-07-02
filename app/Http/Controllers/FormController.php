<?php



namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendFormCreatedNotification;

class FormController extends Controller
{

    public function public_index()
    {
        // Fetch all forms using DB facade
        $forms = DB::table('forms')->get();

        // Pass the forms to the view
        return view('public.forms.index', compact('forms'));
    }

    public function index()
    {
          
        $forms = Form::all();
        return view('admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fields.label.*' => 'required|string|max:255',
            'fields.sample.*' => 'nullable|string|max:255',
            'fields.type.*' => 'required|in:text,number,dropdown',
            'fields.options.*' => 'nullable|string|max:255',
            'fields.comments.*' => 'nullable|string',
        ]);

        // Use DB transaction for atomicity
        DB::beginTransaction();

        try {
            // Insert into 'forms' table to create a new form and retrieve the ID
            $formId = DB::table('forms')->insertGetId([
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Prepare an array to hold form fields data for bulk insertion
            $formFields = [];

            // Process each field from the request and prepare for bulk insertion
            foreach ($request->fields['label'] as $index => $label) {
                $formFields[] = [
                    'form_id' => $formId,
                    'label' => $label,
                    'type' => $request->fields['type'][$index],
                    'options' => $request->fields['options'][$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert all form fields data into 'form_fields' table
            DB::table('form_fields')->insert($formFields);

            // Commit the transaction
            DB::commit();

            SendFormCreatedNotification::dispatch()->onQueue('emails');

            // Redirect back with success message upon successful creation
            return redirect()->route('admin.forms.index')
                             ->with('success', 'Form created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            // Log the error
            Log::error('Error creating form: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                             ->with('error', 'Failed to create form. Please try again.');
        }
    }

    public function edit(Form $form)
    {

        echo "<pre>";
        print_r($form);

        exit;

        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fields.label.*' => 'required|string|max:255',
            'fields.sample.*' => 'nullable|string|max:255',
            'fields.type.*' => 'required|in:text,number,dropdown',
            'fields.options.*' => 'nullable|string|max:255',
            'fields.comments.*' => 'nullable|string',
        ]);

        // Use DB transaction for atomicity
        DB::beginTransaction();

        try {
            // Update the 'forms' table to update the form
            DB::table('forms')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'updated_at' => now(),
                ]);

            // Delete existing form fields associated with the form
            DB::table('form_fields')
                ->where('form_id', $id)
                ->delete();

            // Prepare an array to hold form fields data for bulk insertion
            $formFields = [];

            // Process each field from the request and prepare for bulk insertion
            foreach ($request->fields['label'] as $index => $label) {
                $formFields[] = [
                    'form_id' => $id,
                    'label' => $label,
                    'sample' => $request->fields['sample'][$index] ?? null,
                    'type' => $request->fields['type'][$index],
                    'options' => $request->fields['options'][$index] ?? null,
                    'comments' => $request->fields['comments'][$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert all form fields data into 'form_fields' table
            DB::table('form_fields')->insert($formFields);

            // Commit the transaction
            DB::commit();

            // Redirect back with success message upon successful update
            return redirect()->route('admin.forms.index')
                             ->with('success', 'Form updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            // Log the error
            Log::error('Error updating form: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                             ->with('error', 'Failed to update form. Please try again.');
        }
    }

    public function destroy($formId)
    {
      // Start a database transaction for atomicity
    DB::beginTransaction();

    try {
        // Delete form fields associated with the form
        DB::table('form_fields')->where('form_id', $formId)->delete();

        // Delete the form itself
        DB::table('forms')->where('id', $formId)->delete();

        // Commit the transaction
        DB::commit();

        return redirect()->route('admin.forms.index')->with('success', 'Form and form fields deleted successfully.');
    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollback();

        // Log the error
        Log::error('Error deleting form and form fields: ' . $e->getMessage());

        // Redirect back with error message
        return redirect()->back()->with('error', 'Failed to delete form and form fields. Please try again.');
    }
    }




    public function show($id)
    {
        // Fetch the form details
        $form = DB::table('forms')->where('id', $id)->first();
        // Fetch the form fields
        $fields = DB::table('form_fields')->where('form_id', $id)->get();
    
        // Check if form exists
        if (!$form) {
            abort(404, 'Form not found');
        }
    
        // Pass the form and its fields to the view
        return view('public.forms.show', compact('form', 'fields'));
    }

    public function submit($id, Request $request)
    {
        // Fetch form fields from the database using the DB facade
        $formFields = DB::table('form_fields')->where('form_id', $id)->get();
    
        // Validate the request fields
        $request->validate([
            'fields.*' => 'required',  // Add more specific validation rules as needed
        ]);
    
        // Begin a database transaction
        DB::beginTransaction();
    
        try {
            $formSubmissions = [];
    
            // Process each form field from the request
            foreach ($formFields as $field) {
                // Retrieve the field value from the request
                $fieldValue = $request->input('fields.' . $field->id);
    
                // Handle the field value if it's an array (e.g., for dropdowns)
                if (is_array($fieldValue)) {
                    $fieldValue = json_encode($fieldValue);
                }
    
                // Add the field data to the submissions array
                $formSubmissions[] = [
                    'form_id' => $id,
                    'field_name' => $field->label,
                    'field_value' => $fieldValue,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
    
            // Insert the form submissions into the database
            DB::table('form_submissions')->insert($formSubmissions);
    
            // Commit the transaction
            DB::commit();
    
            // Redirect back with success message upon successful creation
            return redirect()->route('public.forms.show', ['id' => $id])
                             ->with('success', 'Form submitted successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
    
            // Log the error
            Log::error('Error submitting form: ' . $e->getMessage());
    
            // Redirect back with error message
            return redirect()->back()
                             ->with('error', 'Failed to submit form. Please try again.');
        }
    }
}

