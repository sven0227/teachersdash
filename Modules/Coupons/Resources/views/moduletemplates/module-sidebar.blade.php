<li class="nav-item {{ request()->is('coupons*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('coupons.index') }}">
        <i class="fa fa-tag"></i> Coupons
    </a>
</li>