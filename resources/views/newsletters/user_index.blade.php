<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletters</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            color: #343a40;
        }
        .header a {
            text-decoration: none;
            color: #007bff;
        }
        .newsletter-list {
            list-style-type: none;
            padding: 0;
        }
        .newsletter-item {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .newsletter-item:last-child {
            border-bottom: none;
        }
        .newsletter-content {
            display: flex;
            align-items: center;
            flex: 1;
        }
        .newsletter-image {
            margin-right: 20px;
        }
        .newsletter-image img {
            width: 100px;
            height: auto;
            border-radius: 10px;
        }
        .newsletter-title {
            font-size: 1.25em;
            margin: 0;
        }
        .newsletter-actions {
            text-align: right;
            margin-left: 20px;
        }
        .newsletter-actions a {
            display: inline-block;
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
        }
        .newsletter-title a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Newsletters</h1>
        </div>
        <ul class="newsletter-list" id="newsletter-list">
            @foreach ($newsletters as $newsletter)
                <li class="newsletter-item">
                    <div class="newsletter-content">
                        <div class="newsletter-image">
                            <img src="{{ asset('images/'.$newsletter->image) }}" alt="Newsletter Image">
                        </div>
                        <div>
                            <p class="newsletter-title">
                                <a href="{{ route('user.newsletters.show', $newsletter->id) }}">{{ $newsletter->title }}</a>
                            </p>
                            <p>{{ Str::limit($newsletter->content, 100) }}</p>
                        </div>
                    </div>
                    <div class="newsletter-actions">
                        <a href="{{ route('user.newsletters.show', $newsletter->id) }}" class="btn btn-read-more">Read More</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        async function fetchNewsletters() {
            try {
                const response = await fetch('{{ route('api.newsletters.index') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const newsletters = await response.json();
                updateNewsletterList(newsletters);
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        }

        function updateNewsletterList(newsletters) {
            const newsletterList = document.getElementById('newsletter-list');
            newsletterList.innerHTML = '';
            newsletters.forEach(newsletter => {
                const listItem = document.createElement('li');
                listItem.className = 'newsletter-item';

                listItem.innerHTML = `
                    <div class="newsletter-content">
                        <div class="newsletter-image">
                            <img src="{{ asset('images') }}/${newsletter.image}" alt="Newsletter Image">
                        </div>
                        <div>
                            <p class="newsletter-title">
                                <a href="{{ route('user.newsletters.show', '') }}/${newsletter.id}">${newsletter.title}</a>
                            </p>
                            <p>${newsletter.content.substring(0, 100)}</p>
                        </div>
                    </div>
                    <div class="newsletter-actions">
                        <a href="{{ route('user.newsletters.show', '') }}/${newsletter.id}" class="btn btn-read-more">Read More</a>
                    </div>
                `;
                newsletterList.appendChild(listItem);
            });
        }

        setInterval(fetchNewsletters, 5000); // Fetch newsletters every 5 seconds

        document.addEventListener('DOMContentLoaded', () => {
            fetchNewsletters();
        });
    </script>
</body>
</html>
