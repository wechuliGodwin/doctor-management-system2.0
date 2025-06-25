<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Create Blog Post</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  @include('layouts.menu')

  <div class="container mx-auto my-8 p-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Blog Post</h2>

    <form action="{{ route('staff.blog.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
      @csrf

      <div class="mb-4">
        <label for="title" class="block text-gray-700 font-bold mb-2">Title:</label>
        <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>

      <div class="mb-4">
        <label for="image" class="block text-gray-700 font-bold mb-2">Image:</label>
        <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>

      <div class="mb-4">
        <label for="content" class="block text-gray-700 font-bold mb-2">Content:</label>
        <textarea id="content" name="content" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
      </div>

      <div class="mb-4">
        <label for="meta_title" class="block text-gray-700 font-bold mb-2">Meta Title:</label>
        <input type="text" id="meta_title" name="meta_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>

      <div class="mb-4">
        <label for="meta_description" class="block text-gray-700 font-bold mb-2">Meta Description:</label>
        <textarea id="meta_description" name="meta_description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
      </div>

      <div class="mb-4">
        <label for="meta_keywords" class="block text-gray-700 font-bold mb-2">Meta Keywords:</label>
        <input type="text" id="meta_keywords" name="meta_keywords" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>

      <div class="flex items-center justify-end">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          Create Blog Post
        </button>
      </div>
    </form>
  </div>


</body>
</html>