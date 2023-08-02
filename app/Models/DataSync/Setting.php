<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'setting';
   protected $primaryKey = 'name';
}
