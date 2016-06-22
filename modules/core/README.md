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

#### migration and seed
php artisan vendor:publish --tag=database
php artisan migrate
php artisan db:seed
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RecruitmentSeeder

#### add menu and acl default
Run in command line:

    `composer dump-autoload`
    `php artisan db:seed --class=PermissionSeeder` 

to update menu and acl

#### Recuirement demo data
Add demo data for recruitment

    `composer dump-autoload`
    `php artisan db:seed --class=RecruitmentSeeder`

Fill phone field demo in profile data, presenter autoload:

    0912345678
    0922345678
    0932345678
    0942345678

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