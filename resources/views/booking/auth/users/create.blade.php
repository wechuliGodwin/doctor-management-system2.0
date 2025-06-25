@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-header text-white d-flex justify-content-between align-items-center"
                     style="background-color: #159ed5; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                    <h5 class="mb-0">Create New User</h5>
                    <a href="{{ route('booking.auth.users.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
                
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   placeholder="Enter email address" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="password" class="form-control"
                                           placeholder="Enter password" required>
                                    <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-0 h-100 px-3"
                                            onclick="togglePassword('password', 'togglePasswordIcon')">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control" placeholder="Confirm password" required>
                                    <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-0 h-100 px-3"
                                            onclick="togglePassword('password_confirmation', 'togglePasswordConfirmationIcon')">
                                        <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label fw-medium">User Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
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

                            <div class="col-md-6 mb-3">
                                <label for="hospital_branch" class="form-label fw-medium">Hospital Branch</label>
                                <select name="hospital_branch" id="hospital_branch" class="form-select" required>
                                    <option value="" disabled {{ old('hospital_branch') ? '' : 'selected' }}>Select Branch</option>
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
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                <label class="form-check-label fw-medium" for="is_active">
                                    Account Active
                                </label>
                                <small class="form-text text-muted d-block">User will be able to login immediately</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white py-2" style="background-color: #159ed5;">
                                <i class="fas fa-user-plus me-1"></i>Create User Account
                            </button>
                            <a href="{{ route('booking.auth.users.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .form-control:focus {
        border-color: #159ed5;
        box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
    }
    .form-select:focus {
        border-color: #159ed5;
        box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
    }
    .btn:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    .card {
        border-radius: 0.75rem;
    }
    .position-relative .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
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