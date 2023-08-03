@extends('layouts.app')
<style type="text/css">
  .footer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 250px;
  }
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <h2>Dashboard</h2>
                <div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Hello {{ Auth::user()->name }}, Welcome to Dashboard!!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
