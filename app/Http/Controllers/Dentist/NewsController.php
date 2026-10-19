<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
           public function show()
    {
        // dd('sdsdd');die();
        $news = News::latest()->get(); // Fetch all news, latest first
        return view('parklane.dentist.news', compact('news'));
    }

    public function showSingle($id)
    {
        $news = News::findOrFail($id);
        // dd($news);
        return view('parklane.dentist.single-news', compact('news'));
    }
}
