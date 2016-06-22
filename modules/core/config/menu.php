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
        'active' => '1',
        'action_code' => 'view.profile',
    ],
    'hr' => [
        'path' => 'hr',
        'label' => 'HR',
        'active' => '1',
        'action_code' => 'view.list.recruitment',
        'child' => [
            'add.employee' => [
                'path' => 'team/member/create',
                'label' => 'Create employee',
                'active' => '1',
                'action_code' => 'add.account',
            ]
        ]
    ],
    'training' => [
        'path' => 'training',
        'label' => 'Training',
        'active' => '1',
        'action_code' => 'edit.test.schedule'
    ],
    'finance' => [
        'path' => 'finance',
        'label' => 'Finance',
        'active' => '1',
        'action_code' => 'view.hr.inout.month'
    ],
    'admin' => [
        'path' => 'admin',
        'label' => 'Admin',
        'active' => '1',
        'action_code' => 'view.hr.inout.month.pre'
    ],
    'team' => [
        'path' => 'team/member',
        'label' => 'Team',
        'active' => '1',
        'action_code' => 'view.team.member',
    ],
    'project' => [
        'path' => 'project',
        'label' => 'Project',
        'active' => '1',
        'action_code' => 'view.hr.inout.year'
    ],
    'sales' => [
        'path' => 'sales',
        'label' => 'Sales',
        'active' => '1',
        'child' => [
            'sales.css' => [
                'path' => 'sale/css',
                'label' => 'CSS',
                'active' => '1',
                'child' => [
                    'css.list' => [
                        'path' => 'sales/css/list',
                        'label' => 'Danh sách CSS',
                        'active' => '1',
                        'action_code' => 'view.list.css',
                    ],
                    'css.create' => [
                        'path' => 'css/create',
                        'label' => 'Tạo CSS',
                        'active' => '1',
                        'action_code' => 'edit.detail.css',
                    ],
                    'view.analyze.css' => [
                        'path' => 'css/analyze',
                        'label' => 'Phân tích CSS',
                        'active' => '1',
                        'action_code' => 'view.analyze.css',
                    ]
                ]
            ]
        ]        
    ],
    'qms' => [
        'path' => 'qms',
        'label' => 'QMS',
        'active' => '1',
        'action_code' => 'system.notification.result.recruitment',
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
                'action_code' => 'edit.setting.team',
            ],
            'menu.item' => [
                'path' => 'setting/menu/item',
                'label' => 'Menu',
                'active' => '1',
                'action_code' => 'edit.setting.menu'
            ],
            'acl' => [
                'path' => 'setting/acl',
                'label' => 'Acl',
                'active' => '1',
                'action_code' => 'edit.setting.acl'
            ]
        ]        
    ],
];
