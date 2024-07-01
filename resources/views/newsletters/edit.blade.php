<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Newsletter</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-size: 1.1em;
            color: #343a40;
        }
        input[type="text"],
        textarea {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 1em;
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="file"] {
            margin-bottom: 20px; 
        }
        textarea {
            height: 300px; 
            resize: none;
        }
        button {
            padding: 10px;
            font-size: 1em;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Newsletter</h1>
        <form action="{{ route('newsletters.update', $newsletter->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="{{ $newsletter->title }}" required>
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea id="content" name="content" required>{{ $newsletter->content }}</textarea>
            </div>
            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" name="image">
                @if($newsletter->image)
                    <img src="{{ asset('images/'.$newsletter->image) }}" alt="Current Image" width="100">
                @endif
            </div>
            <button type="submit">Update</button>
        </form>
        <a href="{{ route('newsletters.index') }}" class="back-link">Back to Newsletters</a>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
