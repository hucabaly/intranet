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
        'active' => '1'
    ],
    'training' => [
        'path' => 'training',
        'label' => 'Training',
        'active' => '1'
    ],
    'finance' => [
        'path' => 'finance',
        'label' => 'Finance',
        'active' => '1'
    ],
    'admin' => [
        'path' => 'admin',
        'label' => 'Admin',
        'active' => '1'
    ],
    'team' => [
        'path' => '#',
        'label' => 'Team',
        'active' => '1',
        'child' => [
            'team.member' => [
                'path' => 'team/member',
                'label' => 'Member',
                'active' => '1'
            ],
            'team.list' => [
                'path' => 'team/list',
                'label' => 'List team',
                'active' => '1',
                'child' => [
                    'team.sub1' => [
                        'path' => 'team/list/sub',
                        'label' => 'sub',
                        'active' => '1'
                    ],
                    'team.sub2' => [
                        'path' => 'team/list/sub',
                        'label' => 'sub',
                        'active' => '1'
                    ]
                ]
            ]
        ]
    ],
    'project' => [
        'path' => 'project',
        'label' => 'Project',
        'active' => '1'
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
                        'active' => '1'
                    ],
                    'css.create' => [
                        'path' => 'css/create',
                        'label' => 'Tạo CSS',
                        'active' => '1'
                    ]
                ]
            ]
        ]        
    ],
    'qms' => [
        'path' => 'qms',
        'label' => 'QMS',
        'active' => '1'
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
            'role' => [
                'path' => 'setting/role',
                'label' => 'Role',
                'active' => '1',
            ],
        ]        
    ]
];
