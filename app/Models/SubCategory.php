<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'sub_category_name', 'status'];

    // ✅ Relationship: A sub-category belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

