<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
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

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }



}
