<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
     public function show()
    {
        // dd('sdsdd');die();
        $news = News::latest()->get(); // Fetch all news, latest first
        return view('parklane.doctor.news', compact('news'));
    }

    public function showSingle($id)
    {
        $news = News::findOrFail($id);
        // dd($news);
        return view('parklane.doctor.single-news', compact('news'));
    }
}
