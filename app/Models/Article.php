<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'image_path',
        'author_id',
    ];

    /**
     * Get the author of the article.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
