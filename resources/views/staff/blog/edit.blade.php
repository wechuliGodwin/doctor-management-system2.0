<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Edit Blog</title>
  <style>
    /* ... your existing styles ... */
  </style>
</head>
<body>

<div class="navbar">
  <img src="{{ asset('images/logo.png') }}" alt="Kijabe Hospital Logo">
  <h1>Welcome to the Staff Dashboard</h1>
</div>

<div class="container">
  @include('layouts.menu')

  <div class="card">
    <h2>Update Blog Post</h2>
    <form action="{{ route('staff.blog.update', $blog) }}" method="POST"> 
      @csrf
      @method('PUT')

      <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ $blog->title }}" required>
      </div>

      <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="6" required>{{ $blog->content }}</textarea>
      </div>

      <button type="submit">Update Blog Post</button>
    </form>
  </div>
</div>

</body>
</html>