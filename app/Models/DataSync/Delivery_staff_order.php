<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_staff_order extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'delivery_staff_order';
}
