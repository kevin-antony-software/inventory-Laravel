<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    public function invoiceDetails()
    {
        // return $this->hasMany(InvoiceDetails::class, 'invoice_id');
        return $this->hasMany(InvoiceDetails::class, 'invoice_id');
        // return $this->hasMany('App\Models\inventory\invoiceDetails', 'invoice_id', 'invoice_id');
    }
}
