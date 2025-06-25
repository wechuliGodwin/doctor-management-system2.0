<div class="container mx-auto p-4">
    <!-- Widgets Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Widget: Need Surgery -->
        <div class="bg-blue-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-blue-800">Need Surgery</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $counts['need_surgery'] ?? 0 }}</p>
        </div>

        <!-- Widget: SHA Submitted-Pending Approval -->
        <div class="bg-yellow-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-yellow-800">SHA Submitted-Pending Approval</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $counts['sha_submitted'] ?? 0 }}</p>
        </div>

        <!-- Widget: Insurance Approved-Deposit Paid-Ready to Schedule -->
        <div class="bg-green-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-green-800">Insurance Approved-Deposit Paid-Ready to Schedule</h3>
            <p class="text-2xl font-bold text-green-600">{{ $counts['insurance_approved'] ?? 0 }}</p>
        </div>

        <!-- Widget: Scheduled -->
        <div class="bg-teal-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-teal-800">Scheduled</h3>
            <p class="text-2xl font-bold text-teal-600">{{ $counts['scheduled'] ?? 0 }}</p>
        </div>

        <!-- Widget: Completed -->
        <div class="bg-purple-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-purple-800">Completed</h3>
            <p class="text-2xl font-bold text-purple-600">{{ $counts['completed'] ?? 0 }}</p>
        </div>

        <!-- Widget: Inactive -->
        <div class="bg-gray-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800">Inactive</h3>
            <p class="text-2xl font-bold text-gray-600">{{ $counts['inactive'] ?? 0 }}</p>
        </div>

        <!-- Widget: SHA Rejected -->
        <div class="bg-red-100 p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-red-800">SHA Rejected</h3>
            <p class="text-2xl font-bold text-red-600">{{ $counts['sha_rejected'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Items List Section -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">All Items</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->patient_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->date ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>