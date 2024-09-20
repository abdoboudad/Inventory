<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'sku',
        'quantity',
        'price',
        'supplier',
        'category',
        'status',
    ];
    public function categories(){
        return $this->hasMany(Category::class,'inventory_id');
    }
}
