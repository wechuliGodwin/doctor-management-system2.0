@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header" style="background-color: #159ed5;">
                    <h5 class="mb-0 text-white">Create New Doctor</h5>
                </div>
                
                <div class="card-body">
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

                    <form method="POST" action="{{ route('booking.auth.doctors.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="doctor_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="doctor_name" name="doctor_name" 
                                   value="{{ old('doctor_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Optional)</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone (Optional)</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone') }}">
                        </div>

                        <div class="mb-3">
                            <label for="hospital_branch" class="form-label">Hospital Branch</label>
                            <select class="form-select" id="hospital_branch" name="hospital_branch" required>
                                @foreach($hospital_branches as $branch)
                                    <option value="{{ $branch }}" {{ old('hospital_branch') == $branch ? 'selected' : '' }}>
                                        {{ ucfirst($branch) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" id="department" name="department" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>
                                        {{ ucfirst($dept) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('booking.auth.doctors.index') }}" class="btn btn-secondary">Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">Create Doctor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection