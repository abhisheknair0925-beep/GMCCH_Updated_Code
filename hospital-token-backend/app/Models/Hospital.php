<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $table = 'tbl_hospital';

    protected $fillable = [
        'name',
        'address',
        'contact_number',
    ];
}
