@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-business-time"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Billable Hours</span>
                    <span class="info-box-number">{{number_format($total_hours)}}</span>
                </div>
            </div>
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Revenue</span>
                    <span class="info-box-number">GH&#8373; {{number_format($total_revenue,2)}}</span>
                </div>
            </div>

        </div>
    </div>
@stop

@section("footer").
@endsection
