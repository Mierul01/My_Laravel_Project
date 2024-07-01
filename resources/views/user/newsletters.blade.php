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
        .newsletter-list {
            list-style-type: none;
            padding: 0;
        }
        .newsletter-item {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            display: flex;
            align-items: center;
        }
        .newsletter-item:last-child {
            border-bottom: none;
        }
        .newsletter-content {
            display: flex;
            align-items: center;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Newsletters</h1>
        </div>
        <ul class="newsletter-list" id="newsletter-list">
            <!-- Newsletter items will be injected here -->
        </ul>
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Configure Pusher
        Pusher.logToConsole = true;
        var pusher = new Pusher('YOUR_PUSHER_KEY', {
            cluster: 'YOUR_PUSHER_CLUSTER'
        });

        var channel = pusher.subscribe('newsletters');
        channel.bind('new-newsletter', function(data) {
            var list = document.getElementById('newsletter-list');
            var item = document.createElement('li');
            item.className = 'newsletter-item';
            item.innerHTML = `
                <div class="newsletter-content">
                    <div class="newsletter-image">
                        <img src="https://via.placeholder.com/100" alt="Newsletter Image">
                    </div>
                    <div>
                        <p class="newsletter-title">${data.title}</p>
                        <p>${data.content}</p>
                    </div>
                </div>
            `;
            list.insertBefore(item, list.firstChild);
        });
    </script>
</body>
</html>
