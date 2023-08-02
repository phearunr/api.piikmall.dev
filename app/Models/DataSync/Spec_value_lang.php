<?php

namespace App\Models\DataSync;

use App\Models\DataSync\Spec;
use App\Models\DataSync\Spec_value;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spec_value_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'spec_value_lang';
    // protected $primaryKey = 'sp_value_id';
    protected $fillable = [
        'sp_value_id',
        'sp_value_name'
    ];

    // public function spec_value(){
    //    return $this->belongsTo(Spec_value::class, 'sp_value_id', 'sp_value_id');
    // }

     
}
