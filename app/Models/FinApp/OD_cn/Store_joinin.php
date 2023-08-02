<?php

namespace App\Models\OD_cn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store_joinin extends Model
{
    use HasFactory;
    protected $connection = 'live_mysql';
    protected $table = 'store_joinin';
}
