<?php

namespace App\Http\Controllers;

use App\Models\Catagory;
use App\Models\Subcatagory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function getSubcategories($categoryId)
    {
        $subcategories = Subcatagory::where('categ_id', $categoryId)->get();

        return response()->json($subcategories);
    }
}