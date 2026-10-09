<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HousePriceController extends Controller
{
    public function index()
    {
        return view('house-price');
    }

    public function predict(Request $request)
    {
        $data = $request->validate([
            'location' => ['required', 'string', 'max:100'],
            'area' => ['required', 'numeric', 'min:100', 'max:100000'],
            'bedrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'age' => ['required', 'integer', 'min:0', 'max:200'],
        ]);

        // Temporary educational formula, not a trained ML model.
        $basePricePerSqFt = 8000;

        $bedroomFactor = 1 + (($data['bedrooms'] - 2) * 0.04);
        $ageFactor = max(0.70, 1 - ($data['age'] * 0.01));

        $estimatedPrice =
            $data['area']
            * $basePricePerSqFt
            * $bedroomFactor
            * $ageFactor;

        return view('house-price', [
            'data' => $data,
            'estimatedPrice' => round($estimatedPrice),
        ]);
    }
}
