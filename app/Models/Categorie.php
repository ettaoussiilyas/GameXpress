<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class Categorie extends Model
{
    public function product(){
        return $this->hasMany(Product::class);
    }
}
