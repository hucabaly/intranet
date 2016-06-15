Core package
===

This is a package for Rikkei Intranet System

Features
---

- [x] Authentication with Google
- [ ] Access control
- [ ] Enable / disable feature
- [x] Integrate AdminLTE Theme
- [x] Allow config menu by file
- [x] Integrate switch localization

#### add menu and acl default
Run in command line:

    `composer dump-autoload`
    `php artisan db:seed --class=PermissionSeeder` 

to update menu and acl

#### Acl
Acl has 3 level:
- level 1: acl group, ex: Profile, Recruitment, Setting, ....
- level 2: action label, ex: View profile, View list member,...
- level 3: action route, ex: team::setting.team.edit, team::team.member.edit

#### Add domain allow logged
edit file `config.domain_logged.php`

#### delete confirm modal
- Button click has class `delete-confirm`
- option: 
    + data-noti: text show body modal, default text is "Are you sure delete item?"