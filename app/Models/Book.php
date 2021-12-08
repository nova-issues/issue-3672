<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * Get all of the puchases that belong to the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchasers()
    {
        return $this->belongsToMany(User::class, 'book_purchases')
                    ->using(BookPurchase::class)
                    ->withPivot('id', 'price', 'type', 'purchased_at')
                    ->withTimestamps();
    }
}
