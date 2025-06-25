<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  @include('layouts.menu')

  <div class="container mx-auto my-8 p-4">
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
    @endif

    <div class="card bg-white border border-gray-300 rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Blog Posts</h2>

      <table class="min-w-full">
        <thead>
          <tr>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Title</th>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Content</th>
            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white">
          @foreach ($blogs as $blog)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-sm leading-5">{{ $blog->title }}</td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-sm leading-5">{{ Str::limit($blog->content, 50) }}</td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-sm leading-5">
                <a href="{{ route('staff.blog.edit', $blog) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>


</body>
</html>