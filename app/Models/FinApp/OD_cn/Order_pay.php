<?php

namespace App\Models\OD_cn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_pay extends Model
{
    use HasFactory;
    protected $connection = 'live_mysql';
    protected $table = 'order_pay';

}
