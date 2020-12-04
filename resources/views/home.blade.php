@extends('layouts.app')

@section('body-class', 'bg-primary')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card border-0 map-card">
                <div class="card-body bg-dark text-white">
                    <map-component></map-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
