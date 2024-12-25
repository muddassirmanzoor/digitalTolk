@extends('layouts.main')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <!-- Page Title -->
                <h4 class="page-title mb-0">Operations List</h4>
        </div>
    </div>
    <!-- End Page Title -->

    <!-- Form Section -->
    <form id="filterForm" method="POST" action="{{ url('operations-list') }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-end gap-3">
                    <!-- Arrival Date Input -->
                    <div class="mb-2" style="width: 200px;">
                        <label for="operation_date" class="form-label">Operation Date</label>
                        <input type="date" id="operation_date" name="operation_date" class="form-control"
                               value="{{ old('operation_date', $operation_date ?? '') }}">
                    </div>
                    <!-- Movement Type -->
                    <div class="mb-2" style="width: 200px;">
                        <label for="section" class="form-label">Select Type</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Type</option>
                            <option value="arrival" {{ $section == 'arrival' ? 'selected' : '' }}>Arrival</option>
                            <option value="departure" {{ $section == 'departure' ? 'selected' : '' }}>Departure</option>
                            <option value="movement" {{ $section == 'movement' ? 'selected' : '' }}>Movement</option>
                            <option value="mzarat" {{ $section == 'mzarat' ? 'selected' : '' }}>Mzarat</option>
                        </select>
                    </div>
                    <!-- Search Button -->
                    <div class="mb-2">
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
                            <th>Type</th>
                            <th>{{ucfirst($section)}} Date</th>
                            <th>{{ucfirst($section)}} Time</th>
                            @if($section == 'movement')
                                <th>Travel From</th>
                                <th>Travel To</th>
                            @endif
                            <th>Assign Driver</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($operations as $i=>$operation)
                        <tr>
                            <td>{{$operation['operational_id']}}</td>
                            <td>{{ucfirst($section)}}</td>
                            <td>{{date('d-m-Y', strtotime($operation[$dateColumn]))}}</td>
                            <td>{{$operation[$timeColumn]}}</td>
                            @if($section == 'movement')
                            <td>{{$operation['travel_from']}}</td>
                            <td>{{$operation['travel_to']}}</td>
                            @endif

                            <td>
                            @if($operation->driverAssignment)
                                <!-- Driver Assigned: Show Driver Name with Edit Link -->
{{--                                    <span>{{ $operation->driverAssignment->driver->driver_name }}</span>--}}
                                    <a href="{{ url('edit-driver-assignment/' . $operation->id) }}"
                                       class="btn btn-sm btn-primary">{{ $operation->driverAssignment->driver->driver_name }}</a>
                            @else
                                <!-- Driver Not Assigned: Show Assign Button -->
                                    <form action="{{ url('assign-driver-form') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="operation_id" value="{{ $operation->operational_id }}">
                                        <input type="hidden" name="section" value="{{ $section }}">
                                        <button type="submit" class="btn btn-sm btn-success">Assign</button>
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
