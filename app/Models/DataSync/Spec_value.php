<?php

namespace App\Models\DataSync;

use App\Models\DataSync\Spec;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spec_value extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'spec_value';
  
    protected $fillable = [
        'sp_value_id',
        'sp_id'
    ];

    protected $hidden =[
        'sp_value_name_bak',
        'sp_value_color',
        'sp_value_sort',
        'sp_image',
        'store_id'
    ];

    public function spec(){ 
       return $this->belongsTo(Spec_lang::class, 'sp_id', 'sp_id');
    }
}
