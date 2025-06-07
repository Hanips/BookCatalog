<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Label extends Model
{
    use HasFactory;

    protected $table = 'labels';

    protected $fillable = [
        'name',
        'type',
        'desc',
        'size',
        'unit',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
