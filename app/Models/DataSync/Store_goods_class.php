<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Model;
use App\Models\DataSync\GoodsTranslation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Store_goods_class extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $connection = 'second_mysql';
    protected $table = 'Store_goods_class';
    protected $primaryKey = 'stc_id';
    protected $translationForeignKey = 'stc_id';
    public $translatedAttributes = [
        'stc_name'
    ];
    public $timestamps = false;
    // protected $fillable =[];
}