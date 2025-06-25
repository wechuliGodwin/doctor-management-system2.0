<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Research Papers | AIC Kijabe Hospital</title>
  <meta name="description" content="Explore peer-reviewed research publications by AIC Kijabe Hospital, contributing to healthcare research in Africa and globally.">
  <meta name="keywords" content="AIC Kijabe Hospital, research papers, healthcare research, medical publications, peer-reviewed articles, health studies, medical research, Africa, global health">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="Research Papers | AIC Kijabe Hospital">
  <meta property="og:description" content="Discover the latest research and peer-reviewed publications by AIC Kijabe Hospital, showcasing our contributions to healthcare research in Africa and beyond.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.kijabehospital.org/research-papers">
  <meta property="og:image" content="https://www.kijabehospital.org/images/research-banner.jpg">
  <meta property="og:site_name" content="AIC Kijabe Hospital">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> 
  <style>
    body {
      font-family: 'Roboto', sans-serif; 
      font-size: 0.9rem;
    }
    .paper-list li {
      margin-bottom: 1rem; 
      line-height: 1.6; 
    }
    .paper-list li span {
      font-weight: 500; 
    }
    .paper-list li i {
      font-style: italic; 
    }
    .year-section {
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 1rem;
      padding-bottom: 0.75rem;
    }
    .sidebar h2, .sidebar h3 {
      font-size: 1.1rem;
    }
    .sidebar a {
      font-size: 0.85rem;
    }
    .container {
      gap: 1.5rem;
    }
  </style>
</head>

<body class="bg-gray-100">

  @include('layouts.navigation')

  <div class="container mx-auto my-10 px-6 flex flex-col md:flex-row md:gap-6">
    <!-- Main Content -->
    <div class="w-full md:w-3/4 bg-white p-6 rounded-lg shadow-lg mb-6 md:mb-0">
      <!-- Search Form -->
      <div class="mb-4 flex items-center space-x-2"> 
        <form action="{{ route('research-papers') }}" method="GET" class="flex-grow flex items-center space-x-2"> 
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search papers..." class="border border-gray-300 rounded-md px-3 py-2 w-full"> 
          <button type="submit" class="bg-[#159ed5] text-white px-4 py-2 rounded-md">Search</button>
        </form>
      </div>

      @if(isset($papers) && $papers->count() > 0)
        <!-- Papers List -->
        @foreach ($papers->groupBy('year') as $year => $yearPapers)
          <div class="year-section">
            <h2 class="text-xl font-bold text-[#159ed5] mb-4">{{ $year }}</h2>
            <ul class="paper-list">
              @foreach ($yearPapers as $paper)
                <li>
                  <span>{{ $paper->authors }}.</span> {{ $paper->title }}. 
                  <i>{{ $paper->journal }}</i>. 
                  @if($paper->date) {{ $paper->date->format('Y-m-d') }}. @endif
                  @if($paper->link)
                    <a href="{{ $paper->link }}" class="text-blue-500 hover:underline" target="_blank">View Publication</a>
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        @endforeach

        <!-- Pagination Links -->
        <div class="mt-6"> 
          {{ $papers->appends(request()->except('page'))->links() }} 
        </div>
      @else
        <p class="text-gray-700">No research papers found.</p>
      @endif
    </div>

    <!-- Sidebar -->
    <aside class="w-full md:w-1/4 bg-white p-4 rounded-lg shadow-md sidebar">
      <h2 class="text-xl font-bold mb-4 text-[#159ed5]">Evaluation & Research</h2>
      <nav>
        <ul class="text-gray-700">
          <li class="mb-2"><a href="#" class="hover:underline">Evaluation & Research</a></li>
          <li class="mb-2"><a href="#" class="hover:underline">Publications</a></li>
          <li class="mb-2"><a href="#" class="hover:underline">Abstracts</a></li>
          <li class="mb-2"><a href="#" class="hover:underline">Studies</a></li>
          <li class="mb-2"><a href="#" class="hover:underline">Submissions</a></li>
        </ul>
      </nav>

      <!-- Journal Categorization -->
      <h3 class="text-lg font-bold mt-8 mb-4 text-[#159ed5]">Journals</h3>
      @if(isset($journals) && count($journals) > 0)
        <ul class="text-gray-700">
          @foreach ($journals as $journal)
            <li class="mb-2">
              <a href="?journal={{ urlencode($journal) }}" class="hover:underline {{ request('journal') === $journal ? 'text-[#159ed5] font-semibold' : '' }}">
                {{ $journal }}
              </a>
            </li>
          @endforeach
        </ul>
      @else
        <p class="text-gray-700">No journals available.</p>
      @endif
    </aside>
  </div>

  @include('layouts.footer')

</body>
</html>