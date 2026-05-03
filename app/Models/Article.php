<?php

namespace App\Models;

use App\Traits\HybridSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, HybridSync;

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

    /**
     * Get the full image URL.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/default-article.png'); 
        }
        
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        
        return asset('storage/' . $this->image_path);
    }
}
