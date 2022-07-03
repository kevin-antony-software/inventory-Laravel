<?php

namespace App\Models;

use App\Models\financials\Cheque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public function cheque()
    {
        return $this->hasMany(Cheque::class);
    }
}
