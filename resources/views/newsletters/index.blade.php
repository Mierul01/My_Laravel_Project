<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletters</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
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
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            color: #343a40;
        }
        .header a {
            text-decoration: none;
            color: #007bff;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .header a i {
            font-size: 1.5em;
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
            flex-grow: 1;
            margin-right: 20px;
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
        .newsletter-text {
            margin-top: 5px;
            color: #6c757d;
        }
        .newsletter-actions {
            display: flex;
            align-items: center;
        }
        .newsletter-title a {
            text-decoration: none;
            color: inherit;
        }
        .newsletter-actions a,
        .newsletter-actions button {
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .newsletter-actions .btn-edit {
            background-color: #007bff;
            color: #fff;
        }
        .newsletter-actions .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }
        .newsletter-actions .btn-warning {
            background-color: #ffc107;
            color: #fff;
        }
        .newsletter-actions .btn-force-delete {
            background-color: #6c757d;
            color: #fff;
        }
        .newsletter-actions form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Newsletters</h1>
            <a href="{{ route('newsletters.create') }}"><i class="fas fa-plus"></i></a>
        </div>
        <ul class="newsletter-list" id="newsletter-list">
            @foreach ($newsletters as $newsletter)
                <li class="newsletter-item" id="newsletter-{{ $newsletter->id }}">
                    <div class="newsletter-content">
                        <div class="newsletter-image">
                            @if($newsletter->image)
                                <img src="{{ asset('images/' . $newsletter->image) }}" alt="Newsletter Image">
                            @else
                                <img src="https://via.placeholder.com/100" alt="Newsletter Image">
                            @endif
                        </div>
                        <div>
                            <p class="newsletter-title">
                                <a href="{{ route('newsletters.show', $newsletter->id) }}">{{ $newsletter->title }}</a>
                            </p>
                            <p class="newsletter-text">{{ Str::limit($newsletter->content, 100) }}</p>
                        </div>
                    </div>
                    <div class="newsletter-actions">
                        @if($newsletter->trashed())
                            <a href="{{ route('newsletters.restore', $newsletter->id) }}" class="btn btn-warning">Recover</a>
                            <form action="{{ route('newsletters.forceDelete', $newsletter->id) }}" method="POST" class="d-inline-block" id="force-delete-form-{{ $newsletter->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-force-delete" onclick="confirmForceDelete({{ $newsletter->id }})">Force Delete</button>
                            </form>
                        @else
                            <a href="{{ route('newsletters.edit', $newsletter->id) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('newsletters.destroy', $newsletter->id) }}" method="POST" class="d-inline-block" id="delete-form-{{ $newsletter->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-delete" onclick="confirmDelete({{ $newsletter->id }})">Delete</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @vite('resources/js/app.js')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                autoHideNewNewsletters();
            }, 120000); // 2 minutes in milliseconds
        });

        async function autoHideNewNewsletters() {
            const newsletters = document.querySelectorAll('.newsletter-item');
            newsletters.forEach(newsletter => {
                const newsletterId = newsletter.id.replace('newsletter-', '');
                hideNewsletter(newsletterId);
            });
        }

        async function hideNewsletter(newsletterId) {
            try {
                const response = await fetch(`/newsletters/hide/${newsletterId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ hidden: true })
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                updateNewsletterActions(newsletterId);
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        }

        function updateNewsletterActions(newsletterId) {
            const newsletterItem = document.getElementById(`newsletter-${newsletterId}`);
            const actionsDiv = newsletterItem.querySelector('.newsletter-actions');
            actionsDiv.innerHTML = `
                <a href="/newsletters/restore/${newsletterId}" class="btn btn-warning">Recover</a>
                <form action="/newsletters/forceDelete/${newsletterId}" method="POST" class="d-inline-block" id="force-delete-form-${newsletterId}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-force-delete" onclick="confirmForceDelete(${newsletterId})">Force Delete</button>
                </form>
            `;
        }

        function confirmDelete(newsletterId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + newsletterId).submit();
                }
            });
        }

        function confirmForceDelete(newsletterId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the newsletter!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('force-delete-form-' + newsletterId).submit();
                }
            });
        }
    </script>
</body>
</html>