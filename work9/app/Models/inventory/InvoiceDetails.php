<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;
    protected $table = 'invoice_details';
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
        // return $this->belongsTo('App\Models\inventory\Invoice', 'id', 'id');
    }

}
