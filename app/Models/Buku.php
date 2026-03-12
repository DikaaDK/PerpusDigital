<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $primaryKey = 'BukuID';

    public $timestamps = false;

    protected $fillable = [
        'Judul',
        'Penulis',
        'Penerbit',
        'TahunTerbit',
        'Cover',
    ];

    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'buku_user', 'buku_id', 'user_id')
            ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'BukuID';
    }
}
