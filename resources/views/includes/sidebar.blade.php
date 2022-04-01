<aside id="sidebar-wrapper">
<div class="sidebar-brand">
     <a href="{{ route('dashboard.index') }}">Technical Test</a>
</div>
<div class="sidebar-brand sidebar-brand-sm">
     <a href="{{ route('dashboard.index') }}">Test</a>
</div>
<ul class="sidebar-menu">
     <li class="menu-header">Dashboard</li>
     <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('dashboard.index') }}">
               <i class="fas fa-th-large"></i>  <span>Dashboard</span>
          </a>
     </li>
     <li class="menu-header">Master Data</li>
     <li class="nav-item dropdown {{ request()->is('master*') ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
               <i class="far fa-file-alt"></i> <span>Master Data</span></a>
          <ul class="dropdown-menu">
               <li class="{{ request()->is('master/customer') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('master.customer') }}">
                         Customer
                    </a>
               </li>
               @if( \Auth::user()->roles == 'admin')
               <li class="{{ request()->is('master/category-tenant') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('master.category-tenant') }}">
                         Category Tenant
                    </a>
               </li>
               <li class="{{ request()->is('master/tenant') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('master.tenant') }}">
                         Tenant
                    </a>
               </li>
               <li class="{{ request()->is('master/user') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('master.user') }}">
                         User
                    </a>
               </li>
               @endif
          </ul>
     </li>
     <li class="menu-header">Transaction</li>
     <li class="{{ request()->is('transaction/tenant') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('transaction.tenant') }}">
               <i class="fas fa-credit-card"></i>
               <span> Transaction Tenant</span>
          </a>
     </li>
</ul>
</aside>