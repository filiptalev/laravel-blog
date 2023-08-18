<?php

namespace App\Src\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
