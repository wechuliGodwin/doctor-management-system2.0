<div class="relative">
    <form wire:submit.prevent="submitSearch">
        <input 
            type="text" 
            placeholder="Search..." 
            wire:model="searchTerm" 
            class="border border-gray-300 rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>

        <!-- Display validation errors -->
        @error('searchTerm')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </form>

    @if(!empty($searchResults))
        <ul class="absolute bg-white border border-gray-300 mt-2 rounded-md w-full">
            @foreach($searchResults as $result)
                <li class="p-2 hover:bg-gray-100">{{ $result->platform }} (ID: {{ $result->id }})</li>
            @endforeach
        </ul>
    @endif
</div>
