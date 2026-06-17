<!DOCTYPE html>
<html>

<head>
    <title>View Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-2xl font-bold mb-4">📄 Post Details</h2>

        @if($post->featured_image_url)
            <img src="{{ $post->featured_image_url }}" class="w-full max-h-96 object-cover rounded mb-4">
        @endif

        <p class="mb-3"><strong>ID:</strong> {{ $post->id }}</p>
        <p class="mb-3"><strong>Title:</strong> {{ $post->title }}</p>

        <a href="{{ route('posts.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Back
        </a>

    </div>

</body>

</html>