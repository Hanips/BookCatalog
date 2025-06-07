<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;
use App\Models\Label;
use App\Models\ProductImage;
use App\Models\Pesanan;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'kode',
        'judul',
        'kategori_id',
        'label_id',
        'harga',
        'diskon',
        'foto',
        'long_product',
        'width_product',
        'slug',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}