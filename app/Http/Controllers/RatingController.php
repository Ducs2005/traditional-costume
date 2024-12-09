<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductRating;
use Exception;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        try {
            Log::info($request->order_id);
            $validated = $request->validate([
                'product_id' => 'required|exists:product,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:500',
            ]);
            $validated['user_id'] = auth()->id(); // Assign the logged-in user
    
            ProductRating::create($validated);
    
            return redirect()->back()->with('success', 'Đánh giá đã được gửi!');
        } catch (Exception $e)
        {
            Log::info($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
