@extends('themes::default.layout_error')
@section('content')

    <!-- Header -->
    <header class="ex-header">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 offset-xl-1">
                    <h1 class="text-center">@lang('419 ERROR')</h1>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
  <!-- end of header -->
    <div class="ex-form-1 pt-5 pb-5" style="height: calc(100vh - 348px)">
        <div class="container">
            <h2>{{ __('Page Expired') }}</h2>
            <a href="{{url('/')}}">@lang('Back to home-page')</a>
        </div>
    </div>
@endsection