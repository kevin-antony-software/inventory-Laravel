<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
