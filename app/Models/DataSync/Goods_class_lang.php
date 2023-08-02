<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods_class_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'goods_class_lang';
   protected $primaryKey = 'goods_commonid';
    protected $translationForeignKey = 'gc_id';
    protected $fillable = [
        'gc_name'
    ];
    protected $hidden = [
       'language_id',
       'type_name',
       'gc_title',
       'gc_keywords',
        'gc_description',
        'third_keyword'
    ];
}
