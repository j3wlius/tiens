<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'amount',
        'period_type',
        'start_date',
        'end_date',
        'description',
        'category_limits',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'category_limits' => 'array',
        'is_active' => 'boolean',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('amount');
    }

    public function getRemainingBudgetAttribute()
    {
        return $this->amount - $this->total_expenses;
    }

    public function getSpentPercentageAttribute()
    {
        return $this->amount > 0 ? ($this->total_expenses / $this->amount) * 100 : 0;
    }
}
