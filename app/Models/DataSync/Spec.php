<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spec extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'spec';
    protected $primaryKey = 'sp_id';

    protected $hidden = [
        'sp_name_bak',
        'sp_sort'
    ];

    public function spec_name(){
        return $this->belongsToMany(Spec_lang::class, 'sp_id', 'sp_id');
    }

}
