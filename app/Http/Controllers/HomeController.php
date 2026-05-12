<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        return view('home', [
            'latestAlbums' => Album::with('artist')->latest()->take(6)->get(),
            'featuredArtists' => Artist::withCount(['albums', 'followers'])->orderBy('name')->take(6)->get(),
            'stats' => [
                'albums' => Album::count(),
                'artists' => Artist::count(),
                'published' => Album::where('status', 'published')->count(),
            ],
        ]);
    }
}
