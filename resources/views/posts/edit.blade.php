<!DOCTYPE html>
<html>

<head>
    <title>Edit Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-2xl font-bold mb-4">✏️ Edit Post</h2>

        <form method="POST" action="{{ route('posts.update', $post->id) }}">
            @csrf
            @method('PUT')

            <label class="block mb-2">Title</label>
            <input type="text" name="title" value="{{ $post->title }}" class="w-full border p-2 rounded mb-4">

            <button class="bg-green-500 text-white px-4 py-2 rounded">
                Update
            </button>
        </form>

    </div>

</body>

</html>