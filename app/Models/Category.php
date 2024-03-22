<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'additional_amount_statement' => 'array',
        'frequent_points_bonus_statement' => 'array',
    ];

    protected $hidden = [
        'amount_expression',
        'additional_amount_expression',
        'additional_amount_statement',
        'frequent_points_bonus',
        'frequent_points_bonus_statement'
    ];

    /**
     * Get all of the movies for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }
}
