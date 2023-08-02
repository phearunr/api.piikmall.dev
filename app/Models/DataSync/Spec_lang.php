<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spec_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'spec_lang';
}
