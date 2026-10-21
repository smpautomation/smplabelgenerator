<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    protected $fillable = ['name', 'slug', 'logo_path', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function labelTypes(): HasMany
    {
        return $this->hasMany(CustomerLabelType::class);
    }

    protected function logoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->logo_path ? Storage::url($this->logo_path) : null);
    }
}
