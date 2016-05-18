<?php
use Rikkei\Core\View\Menu;
?>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
    <ul class="nav navbar-nav">
        <li
            @if(Menu::isActive('profile'))
                class="active"
            @endif
        ><a href="{{ URL::to('/profile') }}">Profile</a></li>
        <li
            @if(Menu::isActive('hr'))
                class="active"
            @endif
        ><a href="{{ URL::to('/hr') }}">HR</a></li>
        <li
            @if(Menu::isActive('training'))
                class="active"
            @endif
        ><a href="{{ URL::to('/training') }}">Training</a></li>
        <li
            @if(Menu::isActive('finance'))
                class="active"
            @endif
        ><a href="{{ URL::to('/finance') }}">Finance</a></li>
        <li
            @if(Menu::isActive('admin'))
                class="active"
            @endif
        ><a href="{{ URL::to('/admin') }}">Admin</a></li>
        <li
            @if(Menu::isActive('team'))
                class="active"
            @endif
        ><a href="{{ URL::to('/team') }}">Team</a></li>
        <li
            @if(Menu::isActive('project'))
                class="active"
            @endif
        ><a href="{{ URL::to('/project') }}">Project</a></li>
        <li
            @if(Menu::isActive('sales '))
                class="active"
            @endif
        ><a href="{{ URL::to('/sales') }}">Sales</a></li>
        <li
            @if(Menu::isActive('qms'))
                class="active"
            @endif
        ><a href="{{ URL::to('/qms') }}">QMS</a></li>
    </ul>
    <form class="navbar-form navbar-left" role="search" type="get" action="">
        <div class="form-group">
            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search">
        </div>
    </form>
</div><!-- /.navbar-collapse -->

