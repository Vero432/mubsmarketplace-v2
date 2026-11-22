<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    // all reports made about this listing
public function reports()
{
    return $this->hasMany(Report::class);
}

// true while an administrator has not finished reviewing a report
public function isUnderReview(): bool
{
    return $this->reports()->whereIn('status', ['open', 'reviewing'])->exists();
}
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'price', 'condition', 'image', 'status', 'expires_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
