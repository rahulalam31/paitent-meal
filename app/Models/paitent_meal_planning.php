<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paitent_meal_planning extends Model
{
    use HasFactory;

    public function patient()
    {
        return $this->belongsTo(patient::class, 'paitent_id', 'id');
    }
}
