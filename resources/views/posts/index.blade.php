<!DOCTYPE html>
<html>

<head>
    <title>Posts Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Posts</h1>
                <p class="text-sm text-gray-500">Manage all your posts</p>
            </div>

            <a href="{{ route('posts.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm font-medium shadow-sm">
                + New Post
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-5">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">

            <div class="p-4 border-b">
                <form method="GET" class="flex gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search posts..."
                        class="border border-gray-300 px-3 py-2 rounded-md text-sm w-full focus:ring-1 focus:ring-blue-500 outline-none"
                    >

                    <button
                        type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md text-sm">
                        Search
                    </button>

                </form>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-gray-700">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">

                        <tr>
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Image</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @forelse($posts as $post)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-3">
                                    {{ $post->id }}
                                </td>

                                <td class="px-6 py-3">

                                    @if($post->featured_image_url)

                                        <img
                                            src="{{ $post->featured_image_url }}"
                                            alt="{{ $post->title }}"
                                            class="w-14 h-14 object-cover rounded-md border"
                                        >

                                    @else

                                        <div class="w-14 h-14 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs">
                                            No Image
                                        </div>

                                    @endif

                                </td>

                                <td class="px-6 py-3 font-medium text-gray-800">
                                    {{ $post->title }}
                                </td>

                                <td class="px-6 py-3">

                                    <div class="flex justify-center gap-2 flex-wrap">

                                        <a href="{{ route('posts.show', $post->id) }}"
                                            class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200">
                                            View
                                        </a>

                                        <a href="{{ route('posts.edit', $post->id) }}"
                                            class="px-3 py-1 text-xs bg-green-100 text-green-600 rounded-md hover:bg-green-200">
                                            Edit
                                        </a>

                                        <form action="{{ route('posts.delete', $post->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                onclick="return confirm('Delete this post?')"
                                                class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded-md hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                    No posts found
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="px-6 py-4 border-t">
                {{ $posts->withQueryString()->links() }}
            </div>

        </div>

    </div>

</body>

</html>

