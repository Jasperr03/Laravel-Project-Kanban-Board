<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardList extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
