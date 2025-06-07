<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product; // Ensure Product model is imported

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';
    public $timestamps = false;
    protected $fillable = [
        'product_id', // Changed from buku_id
        'user_id',
        'ket'
    ];

    public function product(): BelongsTo // Renamed from buku()
    {
        return $this->belongsTo(Product::class, 'product_id'); // Updated to Product::class and foreign key
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}