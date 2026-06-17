<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>

    @trixassets

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .trix-editor {
            min-height: 250px;
            background: white;
        }

        .image-preview {
            max-width: 250px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">✏️ Edit Post</h2>

        <a href="{{ route('posts.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Back
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('posts.update', $post->id) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2">
                Title
            </label>

            <input
                type="text"
                name="title"
                id="title"
                value="{{ old('title', $post->title) }}"
                class="w-full border border-gray-300 p-3 rounded focus:ring focus:ring-blue-200"
            >
        </div>

        {{-- Featured Image --}}
        <div class="mb-5">

            <label class="block font-semibold mb-2">
                Featured Image
            </label>

            @if($post->featured_image)
                <img
                    id="imagePreview"
                    src="{{ asset('storage/' . $post->featured_image) }}"
                    class="image-preview mb-3"
                >
            @else
                <img
                    id="imagePreview"
                    src=""
                    class="image-preview mb-3"
                    style="display:none;"
                >
            @endif

            <input
                type="file"
                name="featured_image"
                id="imageInput"
                accept="image/*"
                class="w-full border border-gray-300 p-2 rounded"
            >

        </div>

        {{-- Content --}}
        <div class="mb-5">

            <label class="block font-semibold mb-2">
                Post Content
            </label>

            @trix($post, 'content', ['disk' => 'public'])

        </div>

        {{-- Stats --}}
        <div class="flex gap-6 text-sm text-gray-600 mb-5">
            <span>
                Words:
                <span id="wordCount">0</span>
            </span>

            <span>
                Characters:
                <span id="charCount">0</span>
            </span>

            <span id="readingTime">
                0 min read
            </span>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700"
        >
            Update Post
        </button>

    </form>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const trixEditor = document.querySelector('trix-editor');

    const wordCountEl = document.getElementById('wordCount');
    const charCountEl = document.getElementById('charCount');
    const readingTimeEl = document.getElementById('readingTime');

    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    function updateCounts() {

        if (!trixEditor) return;

        const text = trixEditor.innerText.trim();

        const words = text.length
            ? text.split(/\s+/).length
            : 0;

        const chars = text.length;

        const minutes = words > 0
            ? Math.ceil(words / 200)
            : 0;

        wordCountEl.textContent = words;
        charCountEl.textContent = chars;
        readingTimeEl.textContent = minutes + ' min read';
    }

    if (trixEditor) {

        trixEditor.addEventListener('trix-change', updateCounts);

        trixEditor.addEventListener('trix-initialize', function () {
            updateCounts();
        });

    }

    imageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {

            imagePreview.style.display = 'none';

            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            imagePreview.src = e.target.result;

            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(file);

    });

});
</script>

</body>
</html>