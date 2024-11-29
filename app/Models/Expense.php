<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ExpenseType;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'expense_type_id',
        'received_by',
        'description',
        'amount',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }
}
