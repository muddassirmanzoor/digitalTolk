@extends('layouts.main')

@section('content')
    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Driver List</h4>
                            </div>
                        </div>
                    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{url('add-driver')}}" class="btn btn-primary mb-3">Add Driver</a>
                </div>
            </div>
        </div>
    </div>
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
            <div class="card">
                <div class="card-body" style="overflow: hidden;overflow-x: scroll;margin: 1.5rem;    padding: 0;">
                    <table id="datatable-buttons" class="table table-stripedw-100"><!--  dt-responsive   nowrap-->
                        <thead>
                        <tr>
                            <th>Driver Name</th>
                            <th>Driver Number</th>
                            <th>Driver Company</th>
                            <th>Status</th>
{{--                            <th>Edit</th>--}}
                            <th>Active/ Inactive</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($drivers as $i=>$driver)
                        <tr>
                            <td>{{$driver['driver_name']}}</td>
                            <td>{{$driver['driver_number']}}</td>
                            <td>{{$driver['driver_company']}}</td>

                            <td>{{$driver['status'] == 0 ? 'Inactive' : 'Active'}}</td>

                            <td>
                                @if($driver['status'] == 0)
                                    <form action="{{ url('update-driver-status/' . $driver['id']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Active</button>
                                    </form>
                                @else
                                    <form action="{{ url('update-driver-status/' . $driver['id']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Inactive</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
