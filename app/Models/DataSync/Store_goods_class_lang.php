<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store_goods_class_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'store_goods_class_lang';
    protected $fillable =[
        'stc_id',
        'language_id',
        'stc_name'
    ];
}
