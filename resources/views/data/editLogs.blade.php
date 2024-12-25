@extends('layouts.main')

@section('content')
    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Edit Logs </h4>
                            </div>
                        </div>
                    </div>
    <!-- end page title -->
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
        <div class="col-xl-12 col-lg-12">
            <div class="card card-h-100">
                <div class="card-body">
                    <strong>{{ $comments->user->name ?? 'Unknown User' }}:</strong>
                    {{$comments->comments ?? 'No Comments'}}
                </div>
            </div>
        </div>

        <!----------Show Table-------->
        <div class="col-xl-12 col-lg-12">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <h3 class="header-title mb-3"> Operational Id ({{$logs[0]['operational_id']}})</h3>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-centered w-100"><!--  dt-responsive   nowrap-->
                        <thead>
                        <tr>
                            <th>Section</th>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Changed By</th>
                            <th>Changed At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->section }}</td>
                                <td>{{ $fieldNames[$log->field] ?? $log->field }}</td> <!-- Show friendly name or fallback to original -->
                                <td>
                                @if($log->field === 'agent_id')
                                    {{ $agentNames[$log->old_value] ?? $log->old_value }} <!-- Replace old agent_id with name -->
                                    @else
                                        {{ $log->old_value }}
                                    @endif</td>
                                <td>
                                @if($log->field === 'agent_id')
                                    {{ $agentNames[$log->new_value] ?? $log->new_value }} <!-- Replace new agent_id with name -->
                                    @else
                                        {{ $log->new_value }}
                                    @endif
                                </td>
                                <td>{{ $log->user->name }}</td>
                                <td>{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!----------Show Table End-------->

    </div>
@endsection
