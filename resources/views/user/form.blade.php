@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Add User</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card card-h-100">
                <div class="card-body">
                    <form action="{{'add-user'}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <h3 class="header-title mb-3">User Information</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">User Name</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                           placeholder="Enter User Name" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           placeholder="Enter Email" min="6" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control"
                                           placeholder="Enter Password" min="6" required>
                                </div>
                            </div>  <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Retype Password</label>
                                    <input type="password" id="password" name="re_password" class="form-control"
                                           placeholder="Enter Password" min="1" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="agentId" class="form-label">Select Role</label>
                                    <select class="form-select" id="agentId" name="role">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Create</button>
                                </div>
                            </div>
                        </div> <!--Row End--->
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
@endsection

