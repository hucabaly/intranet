<?php

use Rikkei\Core\Model\User;
use Rikkei\Team\View\Permission;
use Rikkei\Core\View\Menu;
use Rikkei\Core\Model\Menus;

$menuSetting = Menus::getMenuSetting();
if (Menu::getActive() == 'setting') {
    $userSetting = ' active';
} else {
    $userSetting = '';
}
?>

<!-- Navbar Right Menu -->
@if(Auth::user())
<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        @if ($menuSetting && $menuSetting->id)
            <?php $menuSettingHtml = Menu::get($menuSetting->id, 1); ?>
            @if (e($menuSettingHtml))
                <li class="setting dropdown{{ $userSetting }}">
                    <a href="{{ URL::route('team::setting.team.index') }}" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-gears"></i>
                    </a>
                    <ul class="dropdown-menu">
                        {!! $menuSettingHtml !!}
                    </ul>
                </li>
            @endif
        @endif
        <!-- User Account Menu -->
        <li class="user user-menu">
            <a href="#">
                @if(User::getAvatar())
                    <img src="{{ User::getAvatar() }}" class="user-image" alt="User Image">
                @else
                    <i class="fa fa-user"></i>
                @endif
                <span class="hidden-xs">{{ User::getNickName() }}</span>
            </a>
        </li>
        <li class="logout">
            <a href="{{ URL::to('logout') }}">
                <i class="fa fa-sign-out"></i>
            </a>
        </li>
    </ul>
</div><!-- /.navbar-custom-menu -->
@endif