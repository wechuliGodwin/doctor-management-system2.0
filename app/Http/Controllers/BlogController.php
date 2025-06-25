 $blogs]);
    }

    public function create()
    {
        return view('staff.blog.create');
    }

    public function show(Blog $blog)
    {
        // Fetch other blogs, excluding the current one
        $otherBlogs = Blog::where('id', '!=', $blog->id)
                          ->orderBy('created_at', 'desc') 
                          ->get(); 

        // Convert newline characters to HTML 
 for paragraph breaks
        $blog->content = nl2br($blog->content);

        return view('blog.show', [
            'blog' => $blog,
            'otherBlogs' => $otherBlogs,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Request Data:', $request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();  
                $request->image->move(public_path('images'), $imageName);
                $imagePath = 'images/' . $imageName;
            } else {
                $imagePath = null; 
            }

            // Here we're not modifying content, it's stored as is from the form
            $blog = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ]);

            Log::info('Blog Post Created:', $blog->toArray());

        } catch (\Exception $e) {
            Log::error('Error creating blog post:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create blog post.');
        }

        return redirect()->route('staff.blog.index')->with('success', 'Blog post created successfully!');
    }

    public function edit(Blog $blog)
    {
        return view('staff.blog.edit', ['blog' => $blog]);
    }

    public function update(Request $request, Blog $blog)
    {
        Log::info('Request Data (Update):', $request->all()); 

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();  
                $request->image->move(public_path('images'), $imageName);
                $imagePath = 'images/' . $imageName; 
                $blog->image = $imagePath; 
            }

            // Update content but keep newlines for paragraphs
            $blog->update([
                'title' => $request->title,
                'content' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ]);

            Log::info('Blog Post Updated:', $blog->toArray()); 

        } catch (\Exception $e) {
            Log::error('Error updating blog post:', ['error' => $e->getMessage()]); 
            return redirect()->back()->with('error', 'Failed to update blog post.'); 
        }

        return redirect()->route('staff.blog.index')->with('success', 'Blog post updated successfully!');
    }
}