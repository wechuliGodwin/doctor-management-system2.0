@extends('layouts.dashboard')

@section('content')
    <div class="container py-3 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card border-0 shadow-lg rounded-3 w-100" style="max-width: 500px;">
            <div class="card-header text-white"
                style="background-color: #159ed5; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="mb-0">Create User Account</h5>
            </div>
            <div class="card-body p-3">
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
                    <div class="mb-2">
                        <label for="full_name" class="form-label fw-medium text-muted"
                            style="opacity: 0.7; font-size: 0.9rem;">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control form-control-sm"
                            placeholder="Enter full name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label fw-medium text-muted"
                            style="opacity: 0.7; font-size: 0.9rem;">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm"
                            placeholder="Enter email address" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2 position-relative">
                        <label for="password" class="form-label fw-medium text-muted"
                            style="opacity: 0.7; font-size: 0.9rem;">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm"
                            placeholder="Enter password" required>
                        <span class="position-absolute end-0 top-50 translate-middle-y me-2" style="cursor: pointer;"
                            onclick="togglePassword('password', 'togglePasswordIcon')">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2 position-relative">
                        <label for="password_confirmation" class="form-label fw-medium text-muted"
                            style="opacity: 0.7; font-size: 0.9rem;">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control form-control-sm" placeholder="Confirm password" required>
                        <span class="position-absolute end-0 top-50 translate-middle-y me-2" style="cursor: pointer;"
                            onclick="togglePassword('password_confirmation', 'togglePasswordConfirmationIcon')">
                            <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                        </span>
                        @error('password_confirmation')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="role" class="form-label fw-medium text-muted"
                            style="opacity: 0.7; font-size: 0.9rem;">Role</label>
                        <select name="role" id="role" class="form-control form-control-sm" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-12">
                        <label for="hospital_branch" class="form-label fw-medium text-muted"
                            style="opacity: 0.7; font-size: 0.9rem;">
                            Hospital Branch <span class="text-danger">*</span>
                        </label>
                        <select name="hospital_branch" id="hospital_branch" class="form-control form-control-sm" required>
                            <option value="" disabled {{ old('hospital_branch') ? '' : 'selected' }}>Select a branch
                            </option>
                            @if (!empty($hospital_branches))
                                @foreach ($hospital_branches as $branch)
                                    <option value="{{ $branch }}" {{ old('hospital_branch') === $branch ? 'selected' : '' }}>
                                        {{ ucfirst($branch) }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No branches available</option>
                            @endif
                        </select>
                        @error('hospital_branch')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn text-white w-100"
                        style="background-color: #159ed5; padding: 0.4rem;">Create User</button>
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

            .btn {
                border-radius: 0.375rem;
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