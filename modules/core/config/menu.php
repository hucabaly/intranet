<?php
return [
    'home' => [
        'path' => '/',
        'label' => 'Home',
        'active' => '1'
    ],
    'profile' => [
        'path' => 'profile',
        'label' => 'Profile',
        'active' => '1'
    ],
    'hr' => [
        'path' => 'hr',
        'label' => 'HR',
        'active' => '1',
        'action' => 'hr::route.name'
    ],
    'training' => [
        'path' => 'training',
        'label' => 'Training',
        'active' => '1',
        'action' => 'training::route.name'
    ],
    'finance' => [
        'path' => 'finance',
        'label' => 'Finance',
        'active' => '1',
        'action' => 'finance::route.name'
    ],
    'admin' => [
        'path' => 'admin',
        'label' => 'Admin',
        'active' => '1',
        'action' => 'admin::route.name'
    ],
    'team' => [
        'path' => 'team/member',
        'label' => 'Team',
        'active' => '1',
        'action' => 'team::team.member.index',
    ],
    'project' => [
        'path' => 'project',
        'label' => 'Project',
        'active' => '1',
        'action' => 'project::route.name'
    ],
    'sales' => [
        'path' => 'sales',
        'label' => 'Sales',
        'active' => '1',
        'action' => 'sales::css.list',
        'child' => [
            'sales.css' => [
                'path' => 'sale/css',
                'label' => 'CSS',
                'active' => '1',
                'action' => 'sales::css.list*',
                'child' => [
                    'css.list' => [
                        'path' => 'sales/css/list',
                        'label' => 'Danh sách CSS',
                        'active' => '1',
                        'action' => 'sales::css.list',
                    ],
                    'css.create' => [
                        'path' => 'css/create',
                        'label' => 'Tạo CSS',
                        'active' => '1',
                        'action' => 'sales::css.create',
                    ],
                    'css.analyze' => [
                        'path' => 'css/analyze',
                        'label' => 'Phân tích CSS',
                        'active' => '1',
                        'action' => 'sales::css.analyze',
                    ]
                ]
            ]
        ]        
    ],
    'qms' => [
        'path' => 'qms',
        'label' => 'QMS',
        'active' => '1',
        'action' => 'ams::route.name',
    ],
    
    'setting' => [
        'path' => '#',
        'label' => 'Setting',
        'active' => '1',
        'child' => [
            'team' => [
                'path' => 'setting/team',
                'label' => 'Team',
                'active' => '1',
            ],
            'menu' => [
                'path' => '#',
                'label' => 'Menu',
                'active' => '1',
                'child' => [
                    'menu.group' => [
                        'path' => 'setting/menu/group',
                        'label' => 'Menu group',
                        'active' => '1',
                    ],
                    'css.create' => [
                        'path' => 'setting/menu/item',
                        'label' => 'Menu item',
                        'active' => '1',
                    ],
                ]
            ],
            'acl' => [
                'path' => 'setting/acl',
                'label' => 'Acl',
                'active' => '1',
            ]
        ]        
    ],
];
