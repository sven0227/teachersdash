@extends('core::layouts.app')

@section('title', __('Event Order Email Template'))

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">@lang('Event Order Email Template')</h1>
</div>
<div class="row">
    <div class="col-md-3">
        @include('core::partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <form role="form" method="post" action="{{ route('settings.events.save_order_email') }}" autocomplete="off">
            @csrf
            <div class="card">
                    <div class="card-status bg-blue"></div>
                <div class="card-header">
                    <h4 class="card-title">@lang('Event Order Email Template')</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Order email when guest order a event')</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea name="ORDER_EMAIL_CONTENT" id="ORDER_EMAIL_CONTENT" rows="4" class="form-control">{{ config('events.ORDER_EMAIL_CONTENT') }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <small>
                                            <p>@lang('Enter the following fields so that the content entered by the guest into the form field will be pasted automatically:')</p>
                                            <ul>
                                                <li>@lang('Event name'): <strong>%event_name%</strong></li>
                                                <li>@lang('Event start date'): <strong>%event_start_date%</strong></li>
                                                <li>@lang('Registration date'): <strong>%guest_registration_date%</strong>
                                                <li>@lang('Guest fullname'): <strong>%guest_fullname%</strong></li>
                                                <li>@lang('Guest email'): <strong>%guest_email%</strong></li>
                                                <li>@lang('Total paid amount'): <strong>%total_paid_amount%</strong>
                                            </ul>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fe fe-save mr-2"></i> @lang('Save settings')
                    </button>
                </div>
            </div>

        </form>

    </div>
    
</div>
@push('scripts')
<script src="{{ Module::asset('events:js/settings/order_email.js') }}"></script>    
@endpush
@stop