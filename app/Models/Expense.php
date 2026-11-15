<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['reference', 'description', 'category', 'amount', 'expense_date', 'notes'])]
class Expense extends Model
{
    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'expense_date' => 'date'];
    }

    public static function categories(): array
    {
        return [
            'operational' => 'Operasional',
            'shipping' => 'Pengiriman',
            'marketing' => 'Pemasaran',
            'inventory' => 'Pembelian stok',
            'other' => 'Lainnya',
        ];
    }
}
