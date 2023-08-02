<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Goods_class extends Model
{
    use HasFactory;
    use Translatable;
    protected $connection = 'second_mysql';
    protected $table = 'goods_class';
    protected $primaryKey = 'gc_id';
    protected $translationForeignKey = 'gc_id';
    public $translatedAttributes = [
        'gd_name'
    ];

    protected $visible = array(
        'gc_id',
       // 'IsEnable',
        'translations',
        'parent'
        
    );
    protected $hidden = [
        'gd_name',
        'gc_id'
    ];
    public function parent(){
       return $this->hasOne(GoodsClass::class, 'gc_id', 'gc_parent_id')
       ->with('parent');
    }

    public function scopeRoot(Builder $builder)
    {
        $builder->where('gc_parent_id', 0);
        return $builder;
    }

    
}
