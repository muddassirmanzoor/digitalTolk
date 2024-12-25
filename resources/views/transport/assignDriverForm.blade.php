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
                <h4 class="page-title">Assign Driver</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card card-h-100">
                <div class="card-body">
                    <form action="{{'assign-driver'}}" method="post">
                        @csrf
                        <input name="section_id" value="{{$operation['id']}}" type="hidden">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <h3 class="header-title mb-3">Driver Information</h3>
                                </div>
                            </div>
                            <hr>

                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="operational_id" class="form-label">Operational Id</label>
                                    <input type="text" id="operational_id" name="operational_id" class="form-control"
                                           value="{{$operation['operational_id']}}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="driver_id" class="form-label">Select Driver</label>
                                    <select class="form-select" id="driver_id" name="driver_id" required>
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{$driver['id']}}">{{$driver['driver_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="section" class="form-label">Type</label>
                                    <input type="text" id="section" name="section_type" class="form-control"
                                           value="{{ucfirst(request('section'))}}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">{{ucfirst(request('section'))}} Date</label>
                                    <input type="text" id="date"  class="form-control"
                                           value="{{date('d-m-Y', strtotime($operation[$dateColumn]))}}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="time" class="form-label">{{ucfirst(request('section'))}} Time</label>
                                    <input type="text" id="time"  class="form-control"
                                           value="{{$operation[$timeColumn]}}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="comments" class="form-label">Comments</label>
                                    <textarea type="text" id="comments" name="comments" class="form-control">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Assign</button>
                                </div>
                            </div>
                        </div> <!--Row End--->
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
@endsection

