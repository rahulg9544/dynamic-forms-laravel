<?php

namespace App\Models;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name'
    ];

    public function formFields()
    {
        return $this->hasMany(FormField::class);
    }
}
