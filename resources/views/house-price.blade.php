
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Price Predictor</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 32px 16px;
            background: #f1f5f9;
            color: #172033;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 680px;
            margin: auto;
        }

        .panel {
            background: white;
            padding: 28px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px #0f172a0d;
        }

        h1 { margin-top: 0; }
        .subtitle { color: #64748b; line-height: 1.6; }

        label {
            display: block;
            font-weight: bold;
            margin: 18px 0 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            margin-top: 24px;
            width: 100%;
            padding: 14px;
            border: 0;
            border-radius: 8px;
            background: #0f766e;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .result {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 20px;
            border-radius: 12px;
        }

        .price {
            color: #047857;
            font-size: 30px;
            font-weight: bold;
        }

        .error { color: #b91c1c; }
        .note { color: #64748b; font-size: 13px; line-height: 1.5; }
    </style>
</head>
<body>
<div class="container">
    <div class="panel">
        <h1>House Price Predictor</h1>
        <p class="subtitle">
            Enter a few property details to calculate an illustrative
            house-price estimate.
        </p>

        @if ($errors->any())
            <div class="error">
                <p>Please correct the following:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('house-price.predict') }}">
            @csrf

            <label for="location">City or location</label>
            <input
                id="location"
                name="location"
                type="text"
                value="{{ old('location', $data['location'] ?? '') }}"
                placeholder="e.g. Pune"
                maxlength="100"
                required
            >

            <label for="area">Area (square feet)</label>
            <input
                id="area"
                name="area"
                type="number"
                min="100"
                max="100000"
                step="any"
                value="{{ old('area', $data['area'] ?? '') }}"
                required
            >

            <label for="bedrooms">Number of bedrooms</label>
            <input
                id="bedrooms"
                name="bedrooms"
                type="number"
                min="1"
                max="20"
                step="1"
                value="{{ old('bedrooms', $data['bedrooms'] ?? '') }}"
                required
            >

            <label for="age">Property age (years)</label>
            <input
                id="age"
                name="age"
                type="number"
                min="0"
                max="200"
                step="1"
                value="{{ old('age', $data['age'] ?? '') }}"
                required
            >

            <button type="submit">Estimate House Price</button>
        </form>
    </div>

    @isset($estimatedPrice)
        <div class="panel result">
            <h2>Estimated Price</h2>
            <div class="price">
                ₹{{ number_format($estimatedPrice, 0, '.', ',') }}
            </div>
            <p>
                Property: {{ $data['location'] }} ·
                {{ number_format($data['area']) }} sq ft
            </p>
            <p class="note">
                Educational estimate from a fixed formula. This is not
                a trained machine-learning prediction or a market valuation.
            </p>
        </div>
    @endisset

    <p class="note">
        &copy; Deven Punse - Full Stack Developer & AI Engineer
    </p>
</div>
</body>
</html>
