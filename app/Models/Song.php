<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author','verses', 'category'
    ];

    public function lyrics () {
        return $this->hasMany(Lyric::class, 'song_id', 'id');
    }

    public static function boot() {
        parent::boot();

        self::deleting(function($song) {
            $song->lyrics()->each(function($lyric) {
                $lyric->delete();
            });
        });
    }
}
