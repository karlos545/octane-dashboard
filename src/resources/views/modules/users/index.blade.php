@extends('adminlte::page')

@section('title')
    Dashboard
@stop

@section('content_header')
    <h1>Users</h1>
@stop
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Users</h3>

            <div class="box-tools">
                <form action="">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <div class="input-group-btn">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-danger"><i class="fa fa-times"></i></a>
                        </div>
                        <input type="text" name="q" class="form-control pull-right" placeholder="Search" value="{{ request()->get('q') }}">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
                @foreach($users as $user)
                    <tr s>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="label label-success">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ route('admin.users.delete', $user->id) }}" class="form-inline" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                                <button class="btn btn-xs btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
@stop