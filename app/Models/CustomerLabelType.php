<?php

namespace App\Models;

use App\Enums\LabelType;
use App\Enums\PrintMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLabelType extends Model
{
    protected $fillable = ['customer_id', 'label_type', 'print_method', 'is_enabled'];

    protected $casts = [
        'label_type' => LabelType::class,
        'print_method' => PrintMethod::class,
        'is_enabled' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
