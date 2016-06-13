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

#### add menu
edit file `modules/core/config/menu.php`, format file:

    'menu.name' => [
        'path' => 'path/url',
        'label' => 'Menu Label',
        'active' => '1',
        'action' => 'route.name.permission',
        'child' => [
            'menu.name.child' => [
                'path' => 'path/url',
                'label' => 'Menu Child Label',
                'active' => '1',
                'action' => 'route.name.permission'
            ]
        ]
    ];

Then, run `php artisan db:seed` to update menu

#### Add domain allow logged
edit file `config.domain_logged.php`

#### delete confirm modal
- Button click has class `delete-confirm`
- option: 
    + data-noti: text show body modal, default text is "Are you sure delete item?"