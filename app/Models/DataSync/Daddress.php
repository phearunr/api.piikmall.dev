<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daddress extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'daddress';
    protected $primaryKey = 'address_id';
}
