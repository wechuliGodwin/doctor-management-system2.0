<!-- resources/views/pharmacy/services.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Pharmacy Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-custom {
            background-color: #159ed5;
            color: white;
        }
        .btn-custom:hover {
            background-color: #127ca8;
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
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="nav-bg text-white p-6">
        <div class="container mx-auto flex items-center justify-between">
            <a href="{{ route('pharmacy.index') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Kijabe Hospital Logo" class="logo-img">
            </a>
            <button id="menu-toggle" class="md:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div id="menu" class="hidden md:flex md:items-center space-x-6">
                <a href="#" onclick="toggleModal('orderModal')" class="hover:text-gray-200">New Drug Order</a>
                <a href="#" onclick="toggleModal('consultationModal')" class="hover:text-gray-200">Book Consultation</a>
                <a href="#" onclick="toggleModal('refillModal')" class="hover:text-gray-200">Request Refill</a>
                <a href="#" onclick="toggleModal('otcModal')" class="hover:text-gray-200">Over-the-Counter</a>
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
                <a href="#" onclick="toggleModal('orderModal')" class="hover:text-gray-200">New Drug Order</a>
                <a href="#" onclick="toggleModal('consultationModal')" class="hover:text-gray-200">Book Consultation</a>
                <a href="#" onclick="toggleModal('refillModal')" class="hover:text-gray-200">Request Refill</a>
                <a href="#" onclick="toggleModal('otcModal')" class="hover:text-gray-200">Over-the-Counter</a>
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
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Tele-Pharmacy</h1>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <a href="#" onclick="toggleModal('orderModal'); return false;" class="bg-green-500 rounded-lg shadow-lg p-6 card cursor-pointer"> <!-- New Drug Order: Green -->
                <h2 class="text-xl font-semibold text-white mb-4">New Drug Order</h2>
                <p class="text-white mb-4">Order medications by uploading a verified prescription</p>
            </a>
            <a href="#" onclick="toggleModal('refillModal'); return false;" class="bg-purple-500 rounded-lg shadow-lg p-6 card cursor-pointer"> <!-- Request a Refill: Purple -->
                <h2 class="text-xl font-semibold text-white mb-4">Request a Refill</h2>
                <p class="text-white mb-4">Request a refill for your existing orders.</p>
            </a>
            <a href="#" onclick="toggleModal('otcModal'); return false;" class="bg-orange-500 rounded-lg shadow-lg p-6 card cursor-pointer"> <!-- Over-the-Counter: Orange -->
                <h2 class="text-xl font-semibold text-white mb-4">Over-the-Counter (OTC) Medications</h2>
                <p class="text-white mb-4">Purchase medications without a prescription.</p>
            </a>
            <a href="#" onclick="toggleModal('consultationModal'); return false;" class="bg-blue-500 rounded-lg shadow-lg p-6 card cursor-pointer"> <!-- Book a Consultation: Blue -->
                <h2 class="text-xl font-semibold text-white mb-4">Virtual Consultation</h2>
                <p class="text-white mb-4">Schedule a session with a pharmacist.</p>
            </a>
        </div>

        <!-- History Sections -->
        <section class="mb-12">
            <!-- Ordered Medications -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ordered Medications</h2><hr>
                @if($records->where('type', 'order')->isEmpty())
                    <p class="text-gray-600">You have no ordered medications yet.</p>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records->where('type', 'order') as $order)
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Booked Appointments -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Booked Appointments</h2><hr>
                @if($records->where('type', 'consultation')->isEmpty())
                    <p class="text-gray-600">You have no booked appointments yet.</p>
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
                                @foreach($records->where('type', 'consultation') as $consultation)
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
                @if($records->where('type', 'refill')->isEmpty())
                    <p class="text-gray-600">You have no refill requests yet.</p>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records->where('type', 'refill') as $refill)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $refill->original_order_id }}</td>
                                        <td class="py-2 px-4">{{ $refill->quantity }}</td>
                                        <td class="py-2 px-4">{{ $refill->delivery_address }}</td>
                                        <td class="py-2 px-4">{{ ucfirst($refill->status) }}</td>
                                        <td class="py-2 px-4">{{ $refill->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Modals -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">New Drug Order</h2>
                <button onclick="toggleModal('orderModal')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('pharmacy.order.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="drug_name" class="block text-gray-700">Drug Name</label>
                    <input type="text" class="w-full p-2 border rounded" id="drug_name" name="drug_name" required>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700">Quantity</label>
                    <input type="number" class="w-full p-2 border rounded" id="quantity" name="quantity" min="1" required>
                </div>
                <div class="mb-4">
                    <label for="prescription" class="block text-gray-700">Upload Prescription (if required)</label>
                    <input type="file" class="w-full p-2 border rounded" id="prescription" name="prescription" accept=".pdf,.jpg,.png">
                </div>
                <div class="mb-4">
                    <label for="delivery_address" class="block text-gray-700">Delivery Address</label>
                    <textarea class="w-full p-2 border rounded" id="delivery_address" name="delivery_address" rows="3" required></textarea>
                </div>
                <button type="submit" class="inline-flex items-center btn-custom px-4 py-2 rounded transition-colors duration-300">
                    Place Order
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div id="consultationModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Book a Consultation</h2>
                <button onclick="toggleModal('consultationModal')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('pharmacy.consultation.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="consultation_date" class="block text-gray-700">Preferred Date</label>
                    <input type="date" class="w-full p-2 border rounded" id="consultation_date" name="consultation_date" required>
                </div>
                <div class="mb-4">
                    <label for="consultation_type" class="block text-gray-700">Consultation Type</label>
                    <select class="w-full p-2 border rounded" id="consultation_type" name="consultation_type" required>
                        <option value="">Select Type</option>
                        <option value="video">Video Call</option>
                        <option value="phone">Phone Call</option>
                        <option value="chat">Chat</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-gray-700">Additional Notes</label>
                    <textarea class="w-full p-2 border rounded" id="notes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="inline-flex items-center btn-custom px-4 py-2 rounded transition-colors duration-300">
                    Book Consultation
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div id="refillModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Request a Refill</h2>
                <button onclick="toggleModal('refillModal')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('pharmacy.refill.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="order_id" class="block text-gray-700">Previous Order ID</label>
                    <input type="text" class="w-full p-2 border rounded" id="order_id" name="order_id" required>
                    @error('order_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="refill_quantity" class="block text-gray-700">Refill Quantity</label>
                    <input type="number" class="w-full p-2 border rounded" id="refill_quantity" name="refill_quantity" min="1" required>
                </div>
                <div class="mb-4">
                    <label for="refill_address" class="block text-gray-700">Delivery Address</label>
                    <textarea class="w-full p-2 border rounded" id="refill_address" name="refill_address" rows="3" required></textarea>
                </div>
                <button type="submit" class="inline-flex items-center btn-custom px-4 py-2 rounded transition-colors duration-300">
                    Request Refill
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div id="otcModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Over-the-Counter Medications</h2>
                <button onclick="toggleModal('otcModal')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('pharmacy.otc.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="otc_drug_name" class="block text-gray-700">Medication Name</label>
                    <input type="text" class="w-full p-2 border rounded" id="otc_drug_name" name="otc_drug_name" required>
                </div>
                <div class="mb-4">
                    <label for="otc_quantity" class="block text-gray-700">Quantity</label>
                    <input type="number" class="w-full p-2 border rounded" id="otc_quantity" name="otc_quantity" min="1" required>
                </div>
                <div class="mb-4">
                    <label for="otc_delivery_address" class="block text-gray-700">Delivery Address</label>
                    <textarea class="w-full p-2 border rounded" id="otc_delivery_address" name="otc_delivery_address" rows="3" required></textarea>
                </div>
                <button type="submit" class="inline-flex items-center btn-custom px-4 py-2 rounded transition-colors duration-300">
                    Place Order
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
        }

        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target === modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        };
    </script>
</body>
</html>
