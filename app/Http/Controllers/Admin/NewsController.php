<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NewsController extends Controller
{
   public function create()
    {
        return view('parklane.add_news');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hospital_id' => 'required|exists:hospitals,id',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'hospital_id' => $request->hospital_id,
            'user_id' => Auth::id(),  
        ]);

        return redirect()->back()->with('success', 'News added successfully.');
    }


   public function show()
    {
        $news = News::latest()->get(); // Fetch all news, latest first
        return view('parklane.news', compact('news'));
    }

    public function showSingle($id)
    {
        $news = News::findOrFail($id);
        // dd($news);
        return view('parklane.single-news', compact('news'));
    }


}
