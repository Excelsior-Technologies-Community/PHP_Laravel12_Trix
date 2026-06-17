<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class Post extends Model
{
    use HasTrixRichText;

    protected $guarded = [];

    protected $appends = [
        'featured_image_url'
    ];

    public function getFeaturedImageUrlAttribute()
    {
        if (
            $this->featured_image &&
            Storage::disk('public')->exists($this->featured_image)
        ) {
            return Storage::disk('public')->url($this->featured_image);
        }

        return null;
    }
}