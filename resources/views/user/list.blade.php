@extends('layouts.main')

@section('content')
    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">User List</h4>
                            </div>
                        </div>
                    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{url('add-user')}}" class="btn btn-primary mb-3">Add User</a>
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
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
{{--                            <th>Edit</th>--}}
                            <th>Active/ Inactive</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $i=>$user)
                        <tr
{{--                            style="{{ count($operation['audit']) > 0 ? 'color: red;' : '' }}"--}}
                        >
                            <td>{{$user['name']}}</td>
                            <td>{{$user['email']}}</td>
                            <td>  @if($user->roles->isNotEmpty())
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                @else
                                    No Role Assigned
                                @endif</td>
                            <td>{{$user['status'] == 0 ? 'Inactive' : 'Active'}}</td>
{{--                            <td>--}}
{{--                                <a href="{{ url('edit-user/' . $user['id']) }}">--}}
{{--                                    <i class="mdi mdi-pencil-circle"></i>--}}
{{--                                </a>--}}

{{--                            </td>--}}

                            <td>
                                @if($user['status'] == 0)
                                    <form action="{{ url('update-user-status/' . $user['id']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Active</button>
                                    </form>
                                @else
                                    <form action="{{ url('update-user-status/' . $user['id']) }}" method="POST" style="display: inline;">
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
