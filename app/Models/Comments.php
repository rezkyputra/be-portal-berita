<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Comments extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'comments';

    protected $fillable = ['user_id', 'news_id', 'content'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
