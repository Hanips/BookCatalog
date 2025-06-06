<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengarang extends Model
{
    use HasFactory;
    protected $table = 'pengarang';
    protected $fillable = ['nama_pengarang', 'slug', 'biografi', 'foto'];

    public function pengarang(): HasMany
    {
        return $this->hasMany(Buku::class);
    }
}
