<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['listing_id', 'rater_id', 'seller_id', 'stars', 'comment'];

    public function rater()   { return $this->belongsTo(User::class, 'rater_id'); }
    public function seller()  { return $this->belongsTo(User::class, 'seller_id'); }
    public function listing() { return $this->belongsTo(Listing::class); }
}