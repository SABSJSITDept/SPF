<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'status'];

    // ✅ Relationship: A category has many sub-categories
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}

