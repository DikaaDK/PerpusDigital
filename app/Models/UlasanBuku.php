<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Buku;
use App\Models\User;

class UlasanBuku extends Model
{
    use HasFactory;

    protected $table = 'ulasanbuku';
    protected $primaryKey = 'UlasanID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'BukuID',
        'Ulasan',
        'Rating',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'BukuID', 'BukuID');
    }
}
