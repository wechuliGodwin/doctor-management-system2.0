@extends('layouts.microsoft')

@section('content')

<div class="flex flex-col md:flex-row-reverse min-h-screen">
    <!-- Right Sidebar -->
    <div class="w-full md:w-1/4 bg-gradient-to-b from-[#159ed5] to-[#6c5dd3] p-6 shadow-xl">
        <div class="sticky top-6">
            <h2 class="text-2xl font-bold text-white mb-6 border-b-2 border-white/20 pb-3">Upcoming Events</h2>
            <ul class="space-y-4">
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <div class="text-white">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <strong class="font-semibold">Discovery Call</strong>
                        </div>
                        <p class="text-sm">Gnome Technologies</p>
                        <p class="text-sm opacity-80 mt-1">February 20, 2025 at 2 PM</p>
                    </div>
                </li>
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <div class="text-white">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <strong class="font-semibold">Discovery Call</strong>
                        </div>
                        <p class="text-sm">Medinous</p>
                        <p class="text-sm opacity-80 mt-1">Date TBC</p>
                    </div>
                </li>
                <!-- New links for objectives and feature requests -->
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <a href="{{ route('emr.objectives.list') }}" class="text-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Submitted Objectives</span>
                        </div>
                    </a>
                </li>
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <a href="{{ route('emr.features.list') }}" class="text-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Feature Requests</span>
                        </div>
                    </a>
                </li>
                <!-- New link for ERP Lifecycle -->
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <a href="{{ route('erp.lifecycle') }}" class="text-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>ERP Lifecycle</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

<!-- Main Content -->
    <div class="w-full md:w-3/4 p-8 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-extrabold text-[#1a365d] mb-8 text-center">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#159ed5] to-[#6c5dd3]">
                    EMR Benchmarking & Changeover
                </span>
            </h1>
	 
 	   <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline"> {{ session('success') }} Your input has been noted, and we appreciate your contribution to our EMR project!</span>
                </div>
            @endif

            <div class="prose-lg mb-12">
                <p class="text-gray-700 leading-relaxed mb-6">
                    Welcome to our EMR Benchmarking and Changeover initiative. This comprehensive exercise is designed to help in seeking for an EMR that will improve our efficiency through leveraging on technology.
                </p>
            </div>

            <!-- Benchmarking Sections -->
            <div class="space-y-12">
           <section class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
            <h2 class="text-3xl font-bold text-[#1a365d] mb-6">Tenwek Mission Hospital Benchmark</h2>
            <div class="flex items-center mb-4 text-[#159ed5]">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold">February 20, 2025</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Gather user feedback on the usage of Hospedia, including the support and customizations experience with the vendor.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Learn best practices for backup and failover, particularly as implemented in the CTC.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Explore best practices for telemedicine implementation at Tenwek with the current HMIS Hospedia.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Understand how Starlink can be integrated with existing LAN infrastructure for improved network performance.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Identify best practices for data center design, operation, and security in the context of healthcare.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Investigate methods for load balancing between multiple ISPs, including Starlink, to optimize network traffic.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Evaluate security configurations and strategies for protecting sensitive clinical data, especially with new network technologies.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Study the implementation of parallel network systems to support uninterrupted healthcare delivery.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Assess HL7 integration strategies for lab equipment and other medical devices to ensure seamless data exchange.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Review approaches to implementing and managing security backups for both data and network infrastructure.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Learn from their strategies for parallel system setups to ensure high availability in clinical services.</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Gather insights on usage of server resources to provide backup for CCTV footage.</span>
                </div>
            </div>
        </section>

                <!-- Mater Hospital Section -->
                <section class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <h2 class="text-3xl font-bold text-[#1a365d] mb-6">Mater Hospital Benchmark</h2>
                    <div class="flex items-center mb-4 text-[#159ed5]">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">Date TBC</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Learn how their telemedicine works, if they have a separate system for the school, HR, or if the systems are integrated into HMIS.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Gather user feedback on the usage of Mater HMIS, including the support and customizations experience from the internal team.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Understand the overall architecture and functionality of the Mater Hospital Health Management Information System (HMIS).</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Assess HL7 integration strategies for lab equipment and other medical devices to ensure seamless data exchange.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Cost and ROI: If the team is able to provide indicative cost, how much the HMIS project was worth from conception to deployment.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Security and Compliance: Review the system's security measures and compliance with health regulations, and what measures the IT team takes to secure the solution from external unwanted access.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#6c5dd3] flex-shrink-0 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Clinical Workflow: Observe how the system supports clinical workflows and patient care processes, including the ability to make the hospital paperless.</span>
                        </div>
                    </div>
                </section>
            </div>

<!-- Forms Section -->
<!-- Forms Section -->
<div class="mt-16 space-y-12">
    <!-- Objective Form -->
    <section class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
        <h2 class="text-2xl font-bold text-[#1a365d] mb-6">Suggest New Objectives</h2>
        <form class="space-y-6" action="{{ route('emr.benchmarking.objective.submit') }}" method="POST">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Objective</label>
                <textarea name="objective" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all" rows="4" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="department" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all">
                    <option value="">Select Department</option>
                    <option value="OPD">OPD</option>
                    <option value="IPD">IPD</option>
                    <option value="Theater">Theater</option>
                    <option value="SCM">SCM</option>
                    <option value="Finance">Finance</option>
                    <option value="Allied">Allied</option>
                    <option value="Other">Other</option>
                </select>
                <div class="mt-2">
                    <input type="text" name="otherDepartment" placeholder="Specify Other Department" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all">
                </div>
            </div>
            <button class="w-full bg-gradient-to-r from-[#159ed5] to-[#6c5dd3] text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-all shadow-md">
                Submit Objective
            </button>
        </form>
    </section>

    <!-- Feature Request Form -->
    <section class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
        <h2 class="text-2xl font-bold text-[#1a365d] mb-6">Request New Features</h2>
        <form class="space-y-6" action="{{ route('emr.benchmarking.feature.submit') }}" method="POST">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Feature</label>
                <textarea name="feature" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all" rows="4" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="department" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all">
                    <option value="">Select Department</option>
                    <option value="OPD">OPD</option>
                    <option value="IPD">IPD</option>
                    <option value="Theater">Theater</option>
                    <option value="SCM">SCM</option>
                    <option value="Finance">Finance</option>
                    <option value="Allied">Allied</option>
                    <option value="Other">Other</option>
                </select>
                <div class="mt-2">
                    <input type="text" name="otherDepartment" placeholder="Specify Other Department" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Importance</label>
                <input type="text" name="importance" placeholder="Briefly describe why this feature is important" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#6c5dd3] focus:border-transparent transition-all">
            </div>
            <button class="w-full bg-gradient-to-r from-[#159ed5] to-[#6c5dd3] text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-all shadow-md">
                Submit Feature Request
            </button>
        </form>
    </section>

</div>

    </section>
</div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .flex.flex-col.md\:flex-row-reverse {
        flex-direction: column;
    }

    .w-full.md\:w-1\/4 {
        width: 100%;
        order: 2; /* This will move the sidebar below the main content */
    }

    .w-full.md\:w-3\/4 {
        width: 100%;
        order: 1; /* This ensures the main content comes first */
    }
}
</style>
<script>
    // Add interactive elements here
    document.querySelectorAll('li').forEach(item => {
        item.addEventListener('mouseover', () => {
            item.style.transform = 'translateX(5px)';
        });
        item.addEventListener('mouseout', () => {
            item.style.transform = 'translateX(0)';
        });
    });
</script>
@endsection