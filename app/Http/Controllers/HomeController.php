<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $news = News::latest()->take(5)->get();
        $dailyIronPrice = Setting::where('key', 'daily_iron_price')->first();

        return view('welcome', [
            'news' => $news,
            'dailyIronPrice' => $dailyIronPrice->value ?? null,
        ]);
    }
}