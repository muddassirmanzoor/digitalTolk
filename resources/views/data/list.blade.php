@extends('layouts.main')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <!-- Page Title -->
                <h4 class="page-title mb-0">Data List</h4>
            @if(auth()->check() && auth()->user()->getRoleNames()->first() !== 'transport')
                <!-- Add Data Button -->
                <a href="{{ url('add-data') }}" class="btn btn-primary mb-0">Add Data</a>
                @endif
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <!-- Form Section -->
    <form id="filterForm" method="POST" action="{{ url('data-list') }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Arrival Date Input -->
                    <div class="mb-2">
                        <label for="arrival_date" class="form-label">Arrival Date</label>
                        <input type="date" id="arrival_date" name="arrival_date" class="form-control"
                               value="{{ old('arrival_date', $arrival_date ?? '') }}">
                    </div>

                    <!-- Search Button (aligned to the right) -->
                    <div>
                        <button type="submit" class="btn btn-primary">Search Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
                            <th>Operational Id</th>
                            <th>Agent Name</th>
                            <th>Group Leader name</th>
                            <th>Nationality</th>
                            <th>Arrival Date</th>
                            <th>Departure Date</th>
{{--                            <th>Status</th>--}}
                            @if(auth()->check() && auth()->user()->getRoleNames()->first() !== 'transport')
                            <th>Edit</th>
                            @endif
                            @hasanyrole('reviewer|manager|admin') <th>Confirm Status</th> @endhasanyrole
                            @hasanyrole('admin|manager') <th>Logs</th> @endhasanyrole
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($operations as $i=>$operation)
                        <tr style="{{ count($operation['audit']) > 0 ? 'color: red;' : '' }}">
                            <td>{{$operation['operational_id']}}</td>
                            <td>{{$operation['agent']['agent_name']}}</td>
                            <td>{{$operation['group_leader_name']}}</td>
                            <td>{{$operation['nationality']}}</td>
                            <td>{{date('d-m-Y', strtotime($operation['arrival']['arrival_date']))}}</td>
                            <td>{{date('d-m-Y', strtotime($operation['departure']['departure_date']))}}</td>
{{--                            <td>{{$operation['status'] == 0 ? 'Entered' : 'Confirmed'}}</td>--}}
                            @if(auth()->check() && auth()->user()->getRoleNames()->first() !== 'transport')

                            <td>
                                @if($operation['status'] == 0 ||  auth()->user()->hasAnyRole(['manager', 'admin']))
                                    <a href="{{ url('edit-data/' . $operation['id']) }}">
                                        <i class="mdi mdi-pencil-circle"></i>
                                    </a>
                                @else
                                    <span class="text-muted"><i class="mdi mdi-pencil"></i></span>
                                @endif
                            </td>
                            @endif

                            @hasanyrole('reviewer|manager|admin')
                            <td>
                                @if($operation['status'] == 0)
                                    <form action="{{ url('update-status/' . $operation['id']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                    </form>
                                @else
                                    Confirmed
                                @endif
                            </td>
                            @endhasanyrole

                            @hasanyrole('admin|manager')
                            <td>
                                @if($operation['audit']->isNotEmpty())
                                    <a href="{{ url('edit-logs/' . $operation['operational_id']) }}">Yes</a>
                                @else
                                    No
                                @endif
                            </td>
                            @endhasanyrole
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
