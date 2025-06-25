<!-- resources/views/settings/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Settings - Manage User Roles</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.assign-role') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="user">Select User</label>
                    <select name="user_id" id="user" class="form-control">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="role">Assign Role</label>
                    <select name="role" id="role" class="form-control">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Assign Role</button>
            </form>
        </div>
    </div>

    <h2 class="mt-5">Available Roles and Permissions</h2>
    <div class="row">
        <div class="col-md-6">
            <h3>Roles</h3>
            <ul>
                @foreach($roles as $role)
                    <li>{{ $role->name }}</li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-6">
            <h3>Permissions</h3>
            <ul>
                @foreach($permissions as $permission)
                    <li>{{ $permission->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
