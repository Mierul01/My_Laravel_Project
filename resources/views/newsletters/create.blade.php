<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Newsletter</title>
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
        textarea {
            height: 300px; 
            resize: none;
        }
        input[type="file"] {
            margin-bottom: 20px; 
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
        <h1>Create Newsletter</h1>
        <form id="newsletter-form" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
            </div>
            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" name="image">
            </div>
            <button type="submit">Create</button>
        </form>
        <a href="{{ route('newsletters.index') }}" class="back-link">Back to Newsletters</a>
    </div>

    <script>
        document.getElementById('newsletter-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            try {
                const response = await fetch('{{ route('newsletters.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                // Reload the newsletter list after successful creation
                fetchNewsletters();
                window.location.href = "{{ route('newsletters.index') }}";
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        });

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
            if (newsletterList) {
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
        }
    </script>
</body>
</html>
