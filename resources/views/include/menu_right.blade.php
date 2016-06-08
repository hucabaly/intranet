<!-- Navbar Right Menu -->
@if(Auth::user())
<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" class="user-image" alt="User Image">
                @else
                    <i class="fa fa-user"></i>
                @endif
                <span class="hidden-xs">{{ Auth::user()->nickname }}</span>
            </a>
            <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                    <p>{{ Auth::user()->name }} - Web Developer
                        <small>Member since Nov. 2012</small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                        <a href="{{ URL::to('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                </li>
            </ul>
        </li>
        @if (\Rikkei\Team\View\Permission::getInstance()->isAllow('team::setting.team.index'))
            <li class="setting">
                <a href="{{ URL::route('team::setting.team.index') }}">
                    <i class="fa fa-gears"></i>
                </a>
            </li>
        @endif
    </ul>
</div><!-- /.navbar-custom-menu -->
@endif