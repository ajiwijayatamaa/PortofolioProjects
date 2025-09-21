<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomFurniture extends Model
{
    use HasFactory;
    protected $table = 'custom_furnitures';
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'type',
        'size',
        'finishing',
        'deadline',
        'description',
    ];
}
