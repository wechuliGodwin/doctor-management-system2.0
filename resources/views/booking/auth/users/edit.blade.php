@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header text-white d-flex justify-content-between align-items-center"
                     style="background-color: #159ed5; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                    <h5 class="mb-0">Edit User: {{ $user->full_name }}</h5>
                    <a href="{{ route('booking.auth.users.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
                
                <div class="card-body p-4">
                    {{-- Success/Error Messages --}}
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

                    {{-- User Info Card --}}
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                                {{ strtoupper(substr($user->full_name, 0, 2)) }}
                            </div>
                            <span class="badge bg-{{ $user->role === 'superadmin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }} mb-1">
                                {{ ucfirst($user->role) }}
                            </span>
                            <br>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <p class="text-muted small mt-2">
                                Member since {{ $user->created_at->format('M Y') }}
                            </p>
                        </div>
                        <div class="col-md-9">
                            <form method="POST" action="{{ route('booking.users.update', $user->id) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name" class="form-label fw-medium">Full Name</label>
                                        <input type="text" name="full_name" id="full_name" class="form-control"
                                               value="{{ old('full_name', $user->full_name) }}" required>
                                        @error('full_name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-medium">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label fw-medium">Role</label>
                                        <select name="role" id="role" class="form-select" required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role }}" 
                                                        {{ old('role', $user->role) == $role ? 'selected' : '' }}>
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
                                        <select name="hospital_branch" id="hospital_branch" class="form-select" 
                                                {{ $currentUser->role === 'admin' ? 'disabled' : '' }} required>
                                            @foreach($hospital_branches as $branch)
                                                <option value="{{ $branch }}" 
                                                        {{ old('hospital_branch', $user->hospital_branch) == $branch ? 'selected' : '' }}>
                                                    {{ ucfirst($branch) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($currentUser->role === 'admin')
                                            <small class="text-muted">Admins cannot change hospital branch</small>
                                        @endif
                                        @error('hospital_branch')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" 
                                                   id="is_active" value="1" 
                                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium" for="is_active">
                                                Account Active
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn text-white" style="background-color: #159ed5;">
                                        <i class="fas fa-save me-1"></i>Update User
                                    </button>
                                    <a href="{{ route('booking.auth.users.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Additional Actions --}}
                    <div class="border-top pt-4">
                        <h6 class="fw-bold mb-3">Additional Actions</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <button class="btn btn-outline-warning w-100" 
                                        onclick="showChangePasswordModal({{ $user->id }}, '{{ $user->full_name }}')">
                                    <i class="fas fa-key me-1"></i>Change Password
                                </button>
                            </div>
                            <div class="col-md-6 mb-2">
                                <form method="POST" action="{{ route('booking.users.toggle-status', $user->id) }}" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }} w-100"
                                            onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-1"></i>
                                        {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @if($currentUser->role === 'superadmin' && $user->id !== $currentUser->id)
                        <div class="mt-3">
                            <form method="POST" action="{{ route('booking.users.destroy', $user->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="fas fa-trash me-1"></i>Delete User
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Change Password Modal --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Changing password for: <strong id="userNameDisplay"></strong></p>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .avatar-lg {
        width: 80px;
        height: 80px;
        font-size: 24px;
        font-weight: bold;
    }
    .form-control:focus {
        border-color: #159ed5;
        box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
    }
    .form-select:focus {
        border-color: #159ed5;
        box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
    }
    .card {
        border-radius: 0.75rem;
    }
    .btn:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    function showChangePasswordModal(userId, userName) {
        console.log('Opening modal for user:', userId, userName); // Debug log
        
        // Set the user name in the modal
        document.getElementById('userNameDisplay').textContent = userName;
        
        // Set the form action URL
        const form = document.getElementById('changePasswordForm');
        form.action = `{{ route('booking.users.change-password', ':id') }}`.replace(':id', userId);
        
        console.log('Form action set to:', form.action); // Debug log
        
        // Clear form fields
        document.getElementById('new_password').value = '';
        document.getElementById('new_password_confirmation').value = '';
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        modal.show();
    }
</script>
</push>