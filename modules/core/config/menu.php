<?php
return [
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
        'path' => 'team',
        'label' => 'Team',
        'active' => '1',
        'child' => [
            'team.create' => [
                'path' => 'team/create',
                'label' => 'Create team',
                'active' => '1'
            ],
            'team.list' => [
                'path' => 'team/list',
                'label' => 'List team',
                'active' => '1'
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
        'active' => '1'
    ],
    'qms' => [
        'path' => 'qms',
        'label' => 'QMS',
        'active' => '1'
    ]
];
