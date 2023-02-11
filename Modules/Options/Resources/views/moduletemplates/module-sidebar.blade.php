<li class="nav-item {{ (request()->is('options*')) ? 'active' : '' }}" data-target="event-tour">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOptions">
        <i class="fas fa-cogs"></i>
        <span>@lang('Options')</span>
    </a>
    <div id="collapseOptions" class="collapse {{ (request()->is('options*')) ? 'show' : '' }}">
        <div class="py-2 collapse-inner rounded">
            <a class="collapse-item all-events-link {{ routeName() == 'options.confirm-page.index' ? 'active' : '' }}"
               href="{{ route('options.confirm-page.index') }}">@lang('Confirm page')</a>
        </div>
    </div>
</li>