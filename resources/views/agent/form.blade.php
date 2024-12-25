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
                <h4 class="page-title">Add Agent</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card card-h-100">
                <div class="card-body">
                    <form action="{{'add-agent'}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <h3 class="header-title mb-3">Agent Information</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Agent Name</label>
                                    <input type="text" id="name" name="agent_name" class="form-control"
                                           placeholder="Enter Agent Name" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="agent_number" class="form-label">Agent Number</label>
                                    <input type="text" id="agent_number" name="agent_number" class="form-control"
                                           placeholder="Enter Agent Number" required>
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

