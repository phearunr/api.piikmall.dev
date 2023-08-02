<?php

namespace App\Models\OD_cn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comb_payment_record extends Model
{
    use HasFactory;
    protected $connection = 'live_mysql';
    protected $table = 'comb_payment_record';
}
