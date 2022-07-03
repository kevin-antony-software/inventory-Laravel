<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetails::class);
    }
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}
