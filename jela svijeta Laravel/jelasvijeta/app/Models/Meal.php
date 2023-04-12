<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tag;

class Meal extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->hasOne(Category::class);
    }
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
    public function ingredients()
    {
        return $this->hasMany(Ingredients::class);
    }
}
