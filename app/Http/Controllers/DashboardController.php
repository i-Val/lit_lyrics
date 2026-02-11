<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSongs = Song::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        
        // Group songs by category
        // Assuming 'category' field in songs table matches 'name' or 'id' in categories, 
        // but for display we just group by whatever is in the column.
        $songsByCategory = Song::select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();
            
        $recentSongs = Song::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.index', compact('totalSongs', 'totalUsers', 'totalCategories', 'songsByCategory', 'recentSongs'));
    }
}
