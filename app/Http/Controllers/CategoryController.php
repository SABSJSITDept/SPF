<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ✅ Fetch all categories (API) — only Active
    public function index()
    {
        $categories = Category::where('status', 'Active')->get();
        return response()->json($categories);
    }

    // ✅ Fetch sub-categories based on category_id (API) — only Active
    public function getSubCategories($category_id)
    {
        $subCategories = SubCategory::where('category_id', $category_id)
                                    ->where('status', 'Active')
                                    ->get();
        return response()->json($subCategories);
    }

    // ── Web: Show Add Category page ──────────────────────────────────────
    public function showAddCategory()
    {
        $categories = Category::withCount('subCategories')->latest()->paginate(15);
        return view('spf_backend.category.add_category', compact('categories'));
    }

    // ── Web: Store new Category ──────────────────────────────────────────
    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'status'        => 'required|in:Active,Inactive',
        ]);

        Category::create([
            'category_name' => trim($request->category_name),
            'status'        => $request->status,
        ]);

        return redirect()->route('admin.categories.add')
                         ->with('success', 'Category added successfully.');
    }

    // ── Web: Toggle Category Status ──────────────────────────────────────
    public function toggleCategory(Category $category)
    {
        $category->status = $category->status === 'Active' ? 'Inactive' : 'Active';
        $category->save();
        return redirect()->route('admin.categories.add')
                         ->with('success', 'Category status updated.');
    }

    // ── Web: Delete Category ─────────────────────────────────────────────
    public function destroyCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.add')
                         ->with('success', 'Category deleted.');
    }

    // ── Web: Show Add Sub-Category page ─────────────────────────────────
    public function showAddSubCategory()
    {
        $categories    = Category::all();
        $subCategories = SubCategory::with('category')->latest()->paginate(15);
        return view('spf_backend.category.add_sub_category', compact('categories', 'subCategories'));
    }

    // ── Web: Store new Sub-Category ──────────────────────────────────────
    public function storeSubCategory(Request $request)
    {
        $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'sub_category_name' => 'required|string|max:255',
            'status'            => 'required|in:Active,Inactive',
        ]);

        SubCategory::create([
            'category_id'       => $request->category_id,
            'sub_category_name' => trim($request->sub_category_name),
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.sub-categories.add')
                         ->with('success', 'Sub-category added successfully.');
    }

    // ── Web: Toggle Sub-Category Status ─────────────────────────────────
    public function toggleSubCategory(SubCategory $subCategory)
    {
        $subCategory->status = $subCategory->status === 'Active' ? 'Inactive' : 'Active';
        $subCategory->save();
        return redirect()->route('admin.sub-categories.add')
                         ->with('success', 'Sub-category status updated.');
    }

    // ── Web: Delete Sub-Category ─────────────────────────────────────────
    public function destroySubCategory(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('admin.sub-categories.add')
                         ->with('success', 'Sub-category deleted.');
    }
}

