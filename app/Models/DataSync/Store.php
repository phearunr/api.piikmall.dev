<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'store';
    protected $primaryKey = 'store_id';
}
