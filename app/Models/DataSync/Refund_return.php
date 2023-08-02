<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund_return extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'refund_return';

}
