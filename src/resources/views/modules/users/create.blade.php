@extends('adminlte::page')

@section('title')
    Dashboard | TeamFA
@stop

@section('content_header')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create User</h3>
        </div>
        <!-- /.box-header -->
    <user-form :user="{}" :is-create-form="true" :roles="{{ $roles->toJson() }}"></user-form>
    </div>
@stop