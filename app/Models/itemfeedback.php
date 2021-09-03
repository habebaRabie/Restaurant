<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemfeedback extends Model
{
    use HasFactory;
    protected $table = 'itemfeedback';
    protected $fillable = ['user_id', 'feedback', 'rating', 'item_id'];
}
