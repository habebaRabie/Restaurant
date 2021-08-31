<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ItemsController;

class cart extends Model
{

    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];
}
