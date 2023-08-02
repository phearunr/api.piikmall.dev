<?php

namespace App\Models\OD_cn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund_return extends Model
{
    use HasFactory;
    protected $connection = 'live_mysql';
    protected $table = 'refund_return';
}
