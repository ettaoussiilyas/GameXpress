<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'status',
        'category_id',
        'stock'
    ];

    protected $casts = [
        'stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

}
