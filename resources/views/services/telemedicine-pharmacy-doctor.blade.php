<!-- resources/views/pharmacy/pharmacist.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-custom {
            background-color: #ffffff;
            color: #1f2937;
        }
        .btn-custom:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }
        .btn-approve {
            background-color: #10b981; /* Green for approve */
            color: white;
        }
        .btn-approve:hover {
            background-color: #059669; /* Darker green on hover */
        }
        .btn-reject {
            background-color: #ef4444; /* Red for reject */
            color: white;
        }
        .btn-reject:hover {
            background-color: #dc2626; /* Darker red on hover */
        }
        .nav-bg {
            background-color: #159ed5;
        }
        .logo-img {
            height: 40px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
        }
        @media (max-width: 768px) {
            .logo-img {
                height: 32px;
                max-width: 120px;
            }
        }
        .card {
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="nav-bg text-white p-6">
        <div class="container mx-auto flex items-center justify-between">
            <a href="{{ route('pharmacist.index') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Kijabe Hospital Logo" class="logo-img">
            </a>
            <button id="menu-toggle" class="md:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div id="menu" class="hidden md:flex md:items-center space-x-6">
                <a href="{{ route('pharmacist.index') }}" class="hover:text-gray-200">Dashboard</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:text-gray-200 flex items-center">
                    Logout
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="flex flex-col space-y-4 mt-4">
                <a href="{{ route('pharmacist.index') }}" class="hover:text-gray-200">Dashboard</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="hover:text-gray-200 flex items-center">
                    Logout
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Pharmacist Dashboard</h1>

        <!-- Pending Actions Section -->
        <section class="mb-12">
            <!-- New Drug Orders -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">New Drug Orders</h2><hr>
                @if($orders->isEmpty())
                    <p class="text-gray-600">No new drug orders to review.</p>
                @else
                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-4 text-gray-700">Order ID</th>
                                    <th class="py-2 px-4 text-gray-700">Drug Name</th>
                                    <th class="py-2 px-4 text-gray-700">Quantity</th>
                                    <th class="py-2 px-4 text-gray-700">Delivery Address</th>
                                    <th class="py-2 px-4 text-gray-700">Prescription</th>
                                    <th class="py-2 px-4 text-gray-700">Order Date</th>
                                    <th class="py-2 px-4 text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $order->id }}</td>
                                        <td class="py-2 px-4">{{ $order->drug_name }}</td>
                                        <td class="py-2 px-4">{{ $order->quantity }}</td>
                                        <td class="py-2 px-4">{{ $order->delivery_address }}</td>
                                        <td class="py-2 px-4">
                                            @if($order->prescription_path)
                                                <a href="{{ asset('storage/' . $order->prescription_path) }}" target="_blank" class="text-blue-500 hover:underline">View</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="py-2 px-4">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <form action="{{ route('pharmacist.order.approve', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-approve px-3 py-1 rounded">Approve</button>
                                            </form>
                                            <form action="{{ route('pharmacist.order.reject', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-reject px-3 py-1 rounded">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Appointments -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Appointments</h2><hr>
                @if($consultations->isEmpty())
                    <p class="text-gray-600">No upcoming appointments.</p>
                @else
                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-4 text-gray-700">Date</th>
                                    <th class="py-2 px-4 text-gray-700">Type</th>
                                    <th class="py-2 px-4 text-gray-700">Notes</th>
                                    <th class="py-2 px-4 text-gray-700">Status</th>
                                    <th class="py-2 px-4 text-gray-700">Booked On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consultations as $consultation)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $consultation->consultation_date }}</td>
                                        <td class="py-2 px-4">{{ ucfirst($consultation->consultation_type) }}</td>
                                        <td class="py-2 px-4">{{ $consultation->notes ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ ucfirst($consultation->status) }}</td>
                                        <td class="py-2 px-4">{{ $consultation->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Refill Requests -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Refill Requests</h2><hr>
                @if($refills->isEmpty())
                    <p class="text-gray-600">No refill requests to review.</p>
                @else
                    <div class="bg-white rounded-lg shadow-lg p-6 overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-4 text-gray-700">Original Order ID</th>
                                    <th class="py-2 px-4 text-gray-700">Quantity</th>
                                    <th class="py-2 px-4 text-gray-700">Delivery Address</th>
                                    <th class="py-2 px-4 text-gray-700">Status</th>
                                    <th class="py-2 px-4 text-gray-700">Requested On</th>
                                    <th class="py-2 px-4 text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refills as $refill)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $refill->original_order_id }}</td>
                                        <td class="py-2 px-4">{{ $refill->quantity }}</td>
                                        <td class="py-2 px-4">{{ $refill->delivery_address }}</td>
                                        <td class="py-2 px-4">{{ ucfirst($refill->status) }}</td>
                                        <td class="py-2 px-4">{{ $refill->created_at->format('M d, Y') }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <form action="{{ route('pharmacist.refill.approve', $refill->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-approve px-3 py-1 rounded">Approve</button>
                                            </form>
                                            <form action="{{ route('pharmacist.refill.reject', $refill->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-reject px-3 py-1 rounded">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- JavaScript -->
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
