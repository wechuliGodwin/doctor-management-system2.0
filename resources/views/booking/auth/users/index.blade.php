@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #159ed5;">
                    <h5 class="mb-0 text-white">User Management</h5>
                    <a href="{{ route('booking.auth.users.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Create New User
                    </a>
                </div>
                
                <div class="card-body">
                    {{-- Success/Error Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Search and Filter Form --}}
                    <form method="GET" action="{{ route('booking.auth.users.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by name or email..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="role_filter" class="form-select">
                                    <option value="">All Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ request('role_filter') == $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($currentUser->role === 'superadmin')
                            <div class="col-md-2">
                                <select name="branch_filter" class="form-select">
                                    <option value="">All Branches</option>
                                    @foreach($hospital_branches as $branch)
                                        <option value="{{ $branch }}" {{ request('branch_filter') == $branch ? 'selected' : '' }}>
                                            {{ ucfirst($branch) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <select name="status_filter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('booking.auth.users.index') }}" class="btn btn-outline-secondary">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Users Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Hospital Branch</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                            </div>
                                            {{ $user->full_name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'superadmin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($user->hospital_branch) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                                    data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('booking.auth.users.edit', $user->id) }}">
                                                        <i class="fas fa-edit me-1"></i>Edit Details
                                                    </a>
                                                </li>
                                                <!-- <li>
                                                    <button class="dropdown-item" onclick="showChangePasswordModal({{ $user->id }}, '{{ $user->full_name }}')">
                                                        <i class="fas fa-key me-1"></i>Change Password
                                                    </button>
                                                </li> -->
                                                <li>
                                                    <form method="POST" action="{{ route('booking.users.toggle-status', $user->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item" 
                                                                onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-1"></i>
                                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @if($currentUser->role === 'superadmin' && $user->id !== $currentUser->id)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('booking.users.destroy', $user->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                            <i class="fas fa-trash me-1"></i>Delete User
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <p>No users found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
                        </div>
                        {{ $users->appends(request()->query())->links() }}
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
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 14px;
        font-weight: bold;
    }
    .table th {
        border-top: none;
        font-weight: 600;
    }
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card {
        border-radius: 0.75rem;
    }
    .card-header {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function showChangePasswordModal(userId, userName) {
        document.getElementById('userNameDisplay').textContent = userName;
        document.getElementById('changePasswordForm').action = `{{ route('booking.users.change-password', ':id') }}`.replace(':id', userId);
        
        // Clear form
        document.getElementById('new_password').value = '';
        document.getElementById('new_password_confirmation').value = '';
        
        new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
    }
</script>
@endpush