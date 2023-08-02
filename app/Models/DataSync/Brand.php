<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Brand extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $connection = 'second_mysql';
    protected $table = 'brand';
    protected $primaryKey = 'brand_id';
    protected $translationForeignKey = 'brand_id';
    public $translatedAttributes = [
        'brand_name'
    ];
    protected $fillable =[
        'brand_id'
    ];
    protected $visible = [
        'translations'
    ];
    
    protected $hidden = [
       // 'brand_name'
    ];

}
