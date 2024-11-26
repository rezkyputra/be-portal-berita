<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class News extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'news';

    protected $fillable = ['title', 'content', 'category_id', 'image_url'];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function listComments()
    {
        return $this->hasMany(Comments::class, 'news_id');
    }
}
