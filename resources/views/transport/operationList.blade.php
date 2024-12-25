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

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Operational ID</th>
                <th>Section</th>
                <th>Date</th>
                <th>Time</th>
                <th>Travel From</th>
                <th>Travel To</th>
                <th>Driver</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($combinedOperations as $operation)
                <tr style="
                @if ($operation['section'] === 'arrival') --bs-table-accent-bg: #ffe6f0; background-color: #ffe6f0; {{-- Light Pink --}}
                @elseif ($operation['section'] === 'departure') --bs-table-accent-bg: #e6f7ff; background-color: #e6f7ff; {{-- Light Blue --}}
                @elseif ($operation['section'] === 'movement') --bs-table-accent-bg: #e6ffe6; background-color: #e6ffe6; {{-- Light Green --}}
                @elseif ($operation['section'] === 'mzarat') --bs-table-accent-bg: #fff4e6; background-color: #fff4e6; {{-- Light Orange --}}
                @endif">
                    <td>{{ $operation['operational_id'] }}</td>
                    <td>{{ ucfirst($operation['section']) }}</td>
                    <td>{{ date('d-m-Y', strtotime($operation[$operation['dateColumn']])) }}</td>
                    <td>{{ $operation[$operation['timeColumn']] }}</td>
                    <td>{{ $operation['travel_from'] ?? '-' }}</td>
                    <td>{{ $operation['travel_to'] ?? '-' }}</td>
                    <td>
                        @if($operation['driver_assignment'])
                            <a href="{{ url('edit-driver-assignment/' . $operation['id']) }}" class="btn btn-sm btn-primary">
                                {{ $operation['driver_assignment']['driver']['driver_name'] }}
                            </a>
                        @else
                            <form action="{{ url('assign-driver-form') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="operation_id" value="{{ $operation['operational_id'] }}">
                                <input type="hidden" name="section" value="{{ $operation['section'] }}">
                                <button type="submit" class="btn btn-sm btn-success">Assign</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

@endsection
