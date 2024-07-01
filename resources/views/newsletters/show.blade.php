<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .newsletter-content {
            text-align: center;
        }
        .newsletter-image {
            margin-bottom: 20px;
        }
        .newsletter-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .newsletter-title {
            font-size: 2em;
            margin-bottom: 10px;
            color: #343a40;
        }
        .newsletter-text {
            text-align: justify;
            font-size: 1.1em;
            color: #6c757d;
            line-height: 1.5; 
            margin-bottom: 1em; 
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
        p {
            margin: 0; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-image">
                @if ($newsletter->image)
                    <img src="{{ asset('images/' . $newsletter->image) }}" alt="Newsletter Image">
                @else
                    <img src="https://via.placeholder.com/800x400" alt="Newsletter Image">
                @endif
            </div>
            <div class="newsletter-title">{{ $newsletter->title }}</div>
            <div class="newsletter-text">{!! nl2br(e($newsletter->content)) !!}</div>
        </div>
        <a href="{{ route('newsletters.index') }}" class="back-link">Back to Newsletters</a>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
