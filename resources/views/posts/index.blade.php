<!DOCTYPE html>
<html>

<head>
    <title>Posts Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Posts</h1>
                <p class="text-sm text-gray-500">Manage all your posts</p>
            </div>

            <a href="{{ route('posts.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm font-medium shadow-sm">
                + New Post
            </a>
        </div>

        <!-- ALERT -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-5">
                {{ session('success') }}
            </div>
        @endif

        <!-- CARD -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">

            <!-- SEARCH -->
            <div class="p-4 border-b flex justify-between items-center">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search posts..."
                        class="border border-gray-300 px-3 py-2 rounded-md text-sm focus:ring-1 focus:ring-blue-500 outline-none">

                    <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md text-sm">
                        Search
                    </button>
                </form>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($posts as $post)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-3">{{ $post->id }}</td>

                                <td class="px-6 py-3 font-medium text-gray-800">
                                    {{ $post->title }}
                                </td>

                                <td class="px-6 py-3 text-center">
                                    <div class="flex justify-center gap-2">

                                        <!-- VIEW -->
                                        <a href="{{ route('posts.show', $post->id) }}"
                                            class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200">
                                            View
                                        </a>

                                        <!-- EDIT -->
                                        <a href="{{ route('posts.edit', $post->id) }}"
                                            class="px-3 py-1 text-xs bg-green-100 text-green-600 rounded-md hover:bg-green-200">
                                            Edit
                                        </a>

                                        <!-- DELETE -->
                                        <form action="{{ route('posts.delete', $post->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Delete this post?')"
                                                class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded-md hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                                    No posts found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- PAGINATION -->
            <div class="px-6 py-4 border-t">
                {{ $posts->links() }}
            </div>

        </div>

    </div>

</body>

</html>