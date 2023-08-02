<?php

namespace App\Models\DataSync;

use App\Helper\Asset;
use App\Models\DataSync\Daddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order_common extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'order_common';
    
    protected $fillable = [
        'reciver_name','reciver_info', 'order_id'
     ];

    public function getReciverInfoAttribute($value)
    {
        return @Asset::pro_unserialize($value) !== false ? Asset::pro_unserialize($value): null;
    }

    public function setReciverInfoAttribute($value)
    {
        $this->attributes['reciver_info'] = serialize($value);
    }

    public function store_address(){
      return $this->belongsTo(Daddress::class, 'store_id', 'store_id')
      ->where(['is_default' => '1']);
    }

}
