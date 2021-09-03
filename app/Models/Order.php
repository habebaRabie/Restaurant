<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $fillable = [
        'price',
        'type_of_delivery',
        'rating',
        'Feedback',
        'additional_comment'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
