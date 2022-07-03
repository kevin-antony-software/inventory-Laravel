<?php

namespace App\Models\financials;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    use HasFactory;
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
