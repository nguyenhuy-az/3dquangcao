<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function product($articleAlias = null)
    {
        return view('product.index');
    }

    public function detail($articleAlias = null)
    {
        return view('product.detail.detail');
    }
}
