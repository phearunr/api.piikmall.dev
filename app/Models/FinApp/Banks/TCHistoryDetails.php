<?php

namespace App\Models\Banks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCHistoryDetails extends Model
{
    use HasFactory;
    protected $table = 'tc_history_details';
    public $timestamps = false;

    protected $fillable = [
        'tc_history_id',
        'bank_ref',
        'apv',
        'date',
        'purchase_sn',
        'transaction',
        'details',
        'withdrawal',
        'deposit',
        'balance',
        'user_id',
        'value_dt'
    ];

}
