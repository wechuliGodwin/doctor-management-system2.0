<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kijabe Hospital Research Papers - Published Medical Studies</title> 
  <meta name="description" content="Explore Kijabe Hospital's published research papers and medical studies, showcasing our contributions to healthcare and medical advancements.">
  <meta name="keywords" content="Kijabe Hospital, research papers, medical studies, publications, healthcare research, medical advancements, clinical studies, academic publications, medical journals, Kenya, Africa, global health">
  <link rel="canonical" href="https://www.kijabehospital.org/education/research-papers"> 
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> 

  <style>
    body {
      font-family: 'Roboto', sans-serif; 
    }
    .paper-list li {
      margin-bottom: 1.25rem; 
      line-height: 1.7; 
    }
    .paper-list li span {
      font-weight: 700; 
    }
    .paper-list li i {
      font-style: italic; 
    }
  </style>
</head>

<body class="bg-gray-100">

  @include('layouts.navigation')

  <div class="container mx-auto my-10 px-6">
    <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Research Papers</h1>

    <div class="bg-white p-8 rounded-lg shadow-lg">

      <div class="mb-6">
        <form action="{{ route('research-papers') }}" method="GET"> 
          <input type="text" name="search" placeholder="Search papers..." class="border border-gray-300 rounded-md px-4 py-2 w-full">
          <button type="submit" class="bg-[#159ed5] text-white px-6 py-2 rounded-md mt-2">Search</button>
        </form>
      </div>

      @if(isset($papers) && count($papers) > 0)
      <ul class="paper-list">
        @foreach ($papers as $paper)
        <li>
          <span>{{ $paper->authors }}.</span> {{ $paper->title }}. <i>{{ $paper->journal }}</i>. {{ $paper->date }};{{ $paper->volume }}({{ $paper->issue }}):{{ $paper->page }}. doi: <a href="#" class="text-blue-500 hover:underline">{{ $paper->doi }}</a>. PMID: {{ $paper->pmid }}; PMCID: {{ $paper->pmcid }}.
          <a href="#" class="text-blue-500 hover:underline">View Publication</a> 
        </li>
        @endforeach
      </ul>

      <div class="mt-6"> 
        {{ $papers->appends(request()->except('page'))->links() }} 
      </div>

      @else
      <p>No research papers found.</p>
      @endif
    </div>
  </div>

  @include('layouts.footer')

</body>
</html>