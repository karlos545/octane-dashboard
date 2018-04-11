@extends('adminlte::page')

@section('title')
    Dashboard | TeamFA
@stop

@section('content_header')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $user->full_name }}</h3>
        </div>
    <user-form :user="{{ $user->toJson() }}" :roles="{{ $roles->toJson() }}"></user-form>
    </div>
@stop