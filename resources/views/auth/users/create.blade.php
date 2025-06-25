@extends('layouts.dashboard')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Add New User</h1>
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header text-white"
                style="background-color: #159ed5; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="mb-0">Create User Account</h5>
            </div>
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('booking.auth.users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="full_name" class="form-label fw-medium">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control"
                            placeholder="Enter full name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter password" required>
                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer;"
                            onclick="togglePassword('password', 'togglePasswordIcon')">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            placeholder="Confirm password" required>
                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer;"
                            onclick="togglePassword('password_confirmation', 'togglePasswordConfirmationIcon')">
                            <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                        </span>
                        @error('password_confirmation')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label fw-medium">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select a role</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Doctor (User)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn text-white" style="background-color: #159ed5;">Create User</button>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .form-control {
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }

            .form-control:focus {
                border-color: #159ed5;
                box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
            }

            .form-label {
                color: #333;
            }

            .card {
                max-width: 600px;
                margin: 0 auto;
            }

            .btn {
                border-radius: 0.375rem;
                padding: 0.5rem 1.5rem;
            }

            .btn:hover {
                background-color: #127aa3;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function togglePassword(fieldId, iconId) {
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(iconId);
                if (field.type === 'password') {
                    field.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    field.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        </script>
    @endpush
@endsection