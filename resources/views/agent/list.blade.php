@extends('layouts.main')

@section('content')
    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Agent List</h4>
                            </div>
                        </div>
                    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{url('add-agent')}}" class="btn btn-primary mb-3">Add Agent</a>
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
                            <th>Agent Name</th>
                            <th>Agent Number</th>
                            <th>Status</th>
{{--                            <th>Edit</th>--}}
                            <th>Active/ Inactive</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($agents as $i=>$agent)
                        <tr>
                            <td>{{$agent['agent_name']}}</td>
                            <td>{{$agent['agent_number']}}</td>

                            <td>{{$agent['status'] == 0 ? 'Inactive' : 'Active'}}</td>

                            <td>
                                @if($agent['status'] == 0)
                                    <form action="{{ url('update-agent-status/' . $agent['id']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Active</button>
                                    </form>
                                @else
                                    <form action="{{ url('update-agent-status/' . $agent['id']) }}" method="POST" style="display: inline;">
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
