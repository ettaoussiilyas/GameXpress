<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class Categorie extends Model
{

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function products(){

        return $this->hasMany(Product::class);
    }
}
