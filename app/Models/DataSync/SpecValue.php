<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecValue extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'spec_value';
    protected $primaryKey = 'sp_value_id';

    protected $hidden = [];
    public function spec_value_lang(){
       return $this->hasOne(Spec_value_lang::class, 'sp_value_id', 'sp_value_id');
    }
}
