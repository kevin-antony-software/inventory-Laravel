<?php

namespace App\Models\financials;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }
}
