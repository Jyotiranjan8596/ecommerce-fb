@extends('layouts.master')

@section('content')
    @include('admin.permissions._form', ['editing' => true])
@endsection
