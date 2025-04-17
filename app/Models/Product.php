<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'productName',
        'price',
        'desc',
    ];

    public function imageProduct()
    {
        return $this->hasMany(ImageProduct::class, 'idProduct');
    }
}
