<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class HousePriceController extends Controller
{
    public function index()
    {
        return view('house-price');
    }

    public function predict(Request $request)
    {
        // Step 1: Validate the form inputs
        $data = $request->validate([
            'location' => ['required', 'string', 'max:100'],
            'area' => ['required', 'numeric', 'min:100', 'max:100000'],
            'bedrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'age' => ['required', 'integer', 'min:0', 'max:200'],
        ]);

        try {
            // Step 2: Send house details to the Python API
            $response = Http::timeout(10)->post(
                rtrim(config('services.house_price_api.url'), '/') . '/predict',
                [
                    'area' => (float) $data['area'],
                    'bedrooms' => (int) $data['bedrooms'],
                    'age' => (int) $data['age'],
                ]
            );

            // Step 3: Handle errors returned by Python
            if (!$response->successful()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'prediction' => 'Python could not calculate the price. Please try again.',
                    ]);
            }

            // Step 4: Read the predicted price from Python
            $estimatedPrice = $response->json('predicted_price');

            if (!is_numeric($estimatedPrice)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'prediction' => 'Python returned an invalid prediction.',
                    ]);
            }

            // Step 5: Display the prediction on the existing page
            return view('house-price', [
                'data' => $data,
                'estimatedPrice' => round((float) $estimatedPrice),
            ]);

        } catch (ConnectionException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'prediction' => 'Cannot connect to Python. Please check that the Python API is running.',
                ]);
        }
    }
}
