<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable =[
        'item_name',
        'category_id',
        'price',
        'offer',
        'file_path',
        'offer_end_date',
    ];
}
