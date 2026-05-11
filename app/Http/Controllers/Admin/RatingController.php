<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['order', 'table'])
            ->latest()
            ->paginate(15);

        // Calculate averages
        $avgFood = Rating::avg('food_rating') ?: 0;
        $avgService = Rating::avg('service_rating') ?: 0;
        $avgAmbiance = Rating::avg('ambiance_rating') ?: 0;
        $overallAvg = ($avgFood + $avgService + $avgAmbiance) / 3;

        return view('admin.ratings.index', compact('ratings', 'avgFood', 'avgService', 'avgAmbiance', 'overallAvg'));
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->back()->with('success', 'Rating deleted successfully');
    }
}
