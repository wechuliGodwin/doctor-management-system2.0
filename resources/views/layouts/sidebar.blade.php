<div class="w-full md:w-1/5 bg-gradient-to-b from-[#159ed5] to-[#6c5dd3] p-4 shadow-xl">
    <div class="sticky top-4">
        <h2 class="text-xl font-bold text-white mb-4 border-b border-white/20 pb-2">Upcoming Events</h2>
        <ul class="space-y-3">
            <li class="bg-white/10 p-3 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                <div class="text-white">
                    <div class="flex items-center mb-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <strong class="font-semibold text-sm">Discovery Call</strong>
                    </div>
                    <p class="text-xs">Gnome Technologies</p>
                    <p class="text-xs opacity-80 mt-0.5">February 20, 2025 at 2 PM</p>
                </div>
            </li>
            <li class="bg-white/10 p-3 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                <div class="text-white">
                    <div class="flex items-center mb-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <strong class="font-semibold text-sm">Discovery Call</strong>
                    </div>
                    <p class="text-xs">Medinous</p>
                    <p class="text-xs opacity-80 mt-0.5">Date TBC</p>
                </div>
            </li>
            <!-- New links for objectives and feature requests -->
            <li class="bg-white/10 p-3 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                <a href="{{ route('emr.objectives.list') }}" class="text-white">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-sm">Submitted Objectives</span>
                    </div>
                </a>
            </li>
            <li class="bg-white/10 p-3 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                <a href="{{ route('emr.features.list') }}" class="text-white">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-sm">Feature Requests</span>
                    </div>
                </a>
            </li>
            <!-- New link for ERP Lifecycle -->
            <li class="bg-white/10 p-3 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                <a href="{{ route('erp.lifecycle') }}" class="text-white">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-sm">ERP Lifecycle</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>