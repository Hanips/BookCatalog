<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    protected $table = 'kategori'; // Assuming table name remains 'kategori' as per typical Laravel conventions unless specified otherwise
    protected $fillable = ['nama'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}