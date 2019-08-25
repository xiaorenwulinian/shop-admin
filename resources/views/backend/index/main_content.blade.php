{{--{extend name="admin@layout/basic" /}--}}
@extends('backend.layout.basic')

@section('style')
<style>
    .main_content {
        font-size: 40px;
        font-weight: 700;
        color:royalblue;
        text-align: center;
        margin: 200px auto;
    }
</style>
@endsection

@section('content')
    <p>This is my body content.</p>
<!-- Content Header (Page header) -->
<div class="main_content">
    欢迎回家
</div>

@endsection

@section('script')

@endsection

