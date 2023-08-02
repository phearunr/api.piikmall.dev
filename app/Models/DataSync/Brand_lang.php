<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'brand_lang';
    protected $translationForeignKey = 'brand_id';
}
