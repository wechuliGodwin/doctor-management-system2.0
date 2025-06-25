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
                    SmartCare ERP Lifecycle & Upgrade Options
                </span>
            </h1>

            <div class="mb-12 text-gray-700">
                <p class="leading-relaxed mb-4">
                    SmartCare ERP was implemented on September 13, 2019, with a typical lifecycle duration of 5-8 years. Here's a visualization of its lifecycle progression:
                </p>
            </div>

	    
            <!-- Visualization Container -->
            <div id="erpLifecycle" class="w-full h-[400px] md:h-[400px]"></div>

            <div class="prose-lg mt-10">
                <h2 class="text-2xl font-bold text-[#1a365d] mb-4">System Health Indicators</h2>
                <ul class="list-disc pl-5">
                    <li><strong>Comprehensive, Real-Time Data Access:</strong> Legacy systems can't integrate or update data in real-time.</li>
                    <li><strong>Robust Business Intelligence:</strong> Older systems require IT intervention for data analysis and reporting.</li>
                    <li><strong>Mobile Access:</strong> Lack of mobile capabilities restricts productivity.</li>
                    <li><strong>Seamless Integration:</strong> Difficulties in integrating with modern applications.</li>
                    <li><strong>Easier Maintenance and Upkeep:</strong> High maintenance costs and complexity.</li>
                </ul>
            </div>
<br>
<br>
<br>
	<div class="mb-6 text-gray-700">
    <h2 class="text-2xl font-bold text-[#1a365d] mb-4"><u>SmartCare ERP Implementation Summary</u></h2>
    <ul class="list-disc pl-5">
        <li><strong>Implementation Date:</strong> September 13, 2019 - The system went live after extensive setup and training.</li>
        <li><strong>Project Sponsor:</strong> Kijabe Hospital, with Digital Leo Limited as the Project Manager and Intersoft Technologies as the Implementation Partner.</li>
        <li><strong>Key Objectives:</strong>
            <ul class="list-disc pl-5">
                <li>Harmonize operations across all business units.</li>
                <li>Improve efficiency in patient care delivery through automation.</li>
                <li>Enhance financial reporting and management.</li>
            </ul>
        </li>
        <li><strong>Phases:</strong>
            <ul class="list-disc pl-5">
                <li><strong>Design Workshops:</strong> Held with users to define requirements.</li>
                <li><strong>Build:</strong> System configurations and data migration completed.</li>
                <li><strong>Testing:</strong> Multiple testing phases including UAT (User Acceptance Testing).</li>
                <li><strong>Training:</strong> Extensive training sessions from August 26 to September 12, 2019, covering 643 trainees.</li>
                <li><strong>Cutover:</strong> Final preparations and data migration finalized on September 12, 2019.</li>
                <li><strong>Go-Live:</strong> Mock Go-Live on September 10, followed by actual Go-Live on September 13, 2019.</li>
            </ul>
        </li>
        <li><strong>Post-Go-Live:</strong> Support structure was established, with ongoing issues managed and resolved.</li>
        <li><strong>Project Completion:</strong> The project was completed with a one-month delay, approved by the Strategy team to ensure vendor readiness and a smoother cutover.</li>
    </ul>
</div>

<div class="my-4 text-center">
    <a href="{{ asset('images/smartcare_project_management.pdf') }}" 
       class="inline-block px-6 py-3 text-white bg-[#159ed5] hover:bg-[#0d75a5] rounded-full shadow-lg transition-colors duration-300 ease-in-out"
       target="_blank"
       aria-label="Open SmartCare ERP Project Management Document in new window">
        View Full Document
    </a>
</div>
        </div>


    </div>
</div>

<!-- Include D3.js -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const margin = {top: 20, right: 150, bottom: 60, left: 100},
        width = 800 - margin.left - margin.right,
        height = 400 - margin.top - margin.bottom;

    const svg = d3.select("#erpLifecycle")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", `translate(${margin.left},${margin.top})`);

    const phaseData = [
        {year: 2019, phase: "Implementation"},
        {year: 2020, phase: "Implementation"},
        {year: 2021, phase: "Growth"},
        {year: 2022, phase: "Growth"},
        {year: 2023, phase: "Maturity"},
        {year: 2024, phase: "Maturity"},
        {year: 2025, phase: "Extension"},
        {year: 2026, phase: "Extension"},
        {year: 2027, phase: "Extension"}
    ];

    // List of phases for the y-axis
    const phases = ["Extension", "Maturity", "Growth", "Implementation"];

    // Add X axis
    const x = d3.scaleLinear()
        .domain(d3.extent(phaseData, d => d.year))
        .range([0, width]);
    svg.append("g")
        .attr("transform", `translate(0, ${height})`)
        .call(d3.axisBottom(x).tickFormat(d3.format("d")));

    // Add Y axis
    const y = d3.scalePoint()
        .domain(phases)
        .range([0, height])
        .padding(0.5); // Adjust padding to reduce spacing between points
    svg.append("g")
        .call(d3.axisLeft(y).tickSize(0)); // Remove tick marks

    // Color scale for phases
    const color = d3.scaleOrdinal()
        .domain(phases)
        .range(["#8b4fda", "#6c5dd3", "#3f7dba", "#159ed5"]);

    // Line generator
    const line = d3.line()
        .x(d => x(d.year))
        .y(d => y(d.phase));

    // Add the line
    svg.append("path")
        .datum(phaseData)
        .attr("fill", "none")
        .attr("stroke", "#000")
        .attr("stroke-width", 2)
        .attr("d", line);

    // Add dots at each point
    svg.selectAll("dot")
        .data(phaseData)
        .enter().append("circle")
        .attr("cx", d => x(d.year))
        .attr("cy", d => y(d.phase))
        .attr("r", 5)
        .style("fill", d => color(d.phase))
        .style("stroke", "#fff")
        .style("stroke-width", "2px");

    // Add colored buttons with white text for phases on the right side
    const buttonWidth = 120;
    const buttonHeight = 30;

    svg.selectAll(".phase-button")
        .data(phases)
        .enter().append("g")
        .attr("class", "phase-button")
        .attr("transform", (d, i) => `translate(${width + 20}, ${y(d)})`) // Positioning the buttons on the right
        .each(function(d) {
            const buttonColor = color(d);

            // Create a rectangle for the button
            d3.select(this).append("rect")
                .attr("width", buttonWidth)
                .attr("height", buttonHeight)
                .attr("fill", buttonColor)
                .attr("rx", 5) // Rounded corners
                .attr("ry", 5);

            // Add text inside the button
            d3.select(this).append("text")
                .attr("x", buttonWidth / 2)
                .attr("y", buttonHeight / 2)
                .attr("dy", ".35em") // Center the text vertically
                .attr("text-anchor", "middle")
                .style("fill", "#fff") // White text
                .text(d);
        });

    // X-axis label
    svg.append("text")
        .attr("transform", `translate(${width / 2}, ${height + margin.top + 30})`)
        .style("text-anchor", "middle")
        .text("Year");
});
</script>

<style>
@media (max-width: 768px) {
    .flex.flex-col.md\:flex-row-reverse {
        flex-direction: column;
    }

    .w-full.md\:w-1\/4 {
        width: 100%;
        order: 2;
    }

    .w-full.md\:w-3\/4 {
        width: 100%;
        order: 1;
    }
}
</style>

@endsection