@extends('layouts.facepage')
@section('active-home')
    active
@endsection
@section('title-content')
    Choose Application
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @if ($application_access > 0)
                @foreach ($applications as $application)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-primary card-outline" style="min-height: 270px">
                            <div class="card-header text-center">
                                <h5 class="card-title m-0 text-center">
                                    {{$application->application_name}}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p class="card-text text-justify">
                                                {{$application->application_description}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ $application->application_link }}" class="btn btn-primary form-control">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else

            @endif
        </div>
    </div>
@endsection
