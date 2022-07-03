<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_ID');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_ID');
    }
    public function purchasedetails()
    {
        return $this->hasMany(PurchaseDetails::class);
    }
}
