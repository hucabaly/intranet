<?php

return [
    'A-2' => [
        'label' => 'Profile',
        'child' => [
            'view.profile' => [
                'label' => 'View profile',
                'routes' => [
                    'profile::view'
                ]
            ],
            'view.team.member' => [
                'label' => 'View list member',
                'routes' => [
                    'team::team.member.index'
                ]
            ],
            'view.review.test.employee' => [
                'label' => 'View review test of member',
                'routes' => [
                    
                ]
            ],
            'view.point.employee' => [
                'label' => 'View list point of member',
                'routes' => [
                ]
            ],
            'edit.information.base' => [
                'label' => 'Edit base information, link facebook, upload CV',
                'routes' => [
                    'team::team.member.edit',
                    'team::team.member.save',
                ]
            ],
            'edit.team.position' => [
                'label' => 'Edit information team, position of member',
                'routes' => [
                    'team::team.member.edit.team.position'
                ]
            ],
            'edit.role' => [
                'label' => 'Edit information role of member',
                'routes' => [
                    'team::team.member.edit.role'
                ]
            ],
            'edit.point' => [
                'label' => 'Edit point of member',
                'routes' => [
                ]
            ],
            'edit.test.schedule' => [
                'label' => 'Edit test schedule',
                'routes' => [
                ]
            ],
            'edit.skill' => [
                'label' => 'Edit skill',
                'routes' => [
                ]
            ],
            'edit.experience' => [
                'label' => 'Edit experience project',
                'routes' => [
                ]
            ],
            'delete.employee.left' => [
                'label' => 'Set employee left work',
                'routes' => [
                ]
            ],
            'add.account' => [
                'label' => 'Register new member',
                'routes' => [
                ]
            ],
        ],
    ], // end A-2 profile
    
    'B-1' => [
        'label' => 'Recruitment',
        'child' => [
            'view.list.recruitment' => [
                'label' => 'View list request recruitment',
                'routes' => [
                ]
            ],
            'view.details.recruitment' => [
                'label' => 'View detail request recuitment',
                'routes' => [
                ]
            ],
            'view.download.cv.applicant' => [
                'label' => 'Download CV applicant',
                'routes' => [
                ]
            ],
            'view.details.profile.applicant' => [
                'label' => 'View detail profile applicant',
                'routes' => [
                ]
            ],
            'edit.recruitment' => [
                'label' => 'Edit request recruitment',
                'routes' => [
                ]
            ],
            'edit.profile.applicant' => [
                'label' => 'Edit profile applicant',
                'routes' => [
                ]
            ],
            'delete.recruitment' => [
                'label' => 'Delete request recruitment',
                'routes' => [
                ]
            ],
            'delete.profile.applicant' => [
                'label' => 'Delete profile applicant from request recruitment',
                'routes' => [
                ]
            ],
            'add.recruitment' => [
                'label' => 'Add request recruitment',
                'routes' => [
                ]
            ],
            'add.applicant' => [
                'label' => 'Add applicant to request recruitment',
                'routes' => [
                ]
            ],
            'system.email.interview.applicant' => [
                'label' => 'Send interview email to applicant',
                'routes' => [
                ]
            ],
            'system.update.result.interview' => [
                'label' => 'Update result interview',
                'routes' => [
                ]
            ],
            'system.email.offer.applicant' => [
                'label' => 'Send OFFER email to applicant',
                'routes' => [
                ]
            ],
            'system.email.decline.applicant' => [
                'label' => 'Send decline email to applicant',
                'routes' => [
                ]
            ],
            'system.notification.result.recruitment' => [
                'label' => 'Notification result recruitment',
                'routes' => [
                ]
            ],
        ],
    ], //end B-1 Recruiment
    
    'B-3' => [
        'label' => 'Recruitment - Summary',
        'child' => [
            'view.hr.inout.month' => [
                'label' => 'View hr inout in month',
                'routes' => [
                ]
            ],
            'view.hr.inout.month.pre' => [
                'label' => 'View ht inout in previous month',
                'routes' => [
                ]
            ],
            'view.hr.inout.year' => [
                'label' => 'View hr inout in year',
                'routes' => [
                ]
            ],
        ]
    ], //end B-3 Recruiment - summary
    
    'C-1' => [
        'label' => 'Css',
        'child' => [
            'view.list' => [
                'label' => 'View list css',
                'routes' => [
                    'sales::css.list'
                ]
            ],
            'view.detail' => [
                'label' => 'View detail css',
                'routes' => [
                    'sales::css.view'
                ]
            ],
            'edit.detail' => [
                'label' => 'Create and edit css',
                'routes' => [
                    'sales::css.create',
                    'sales::css.update',
                    'sales::css.preview',
                    'sales::css.save',
                    'sales::css.list',
                    'sales::css.view',
                    'sales::css.detail',
                    'sales::css.cancel',
                ]
            ],
            'view.analyze' => [
                'label' => 'Analyze css',
                'routes' => [
                    'sales::css.analyze',
                    'sales::css.filterAnalyze',
                    'sales::css.applyAnalyze',
                    'sales::css.showAnalyzeListProject',
                    'sales::css.getListLessThreeStar',
                    'sales::css.getProposes',
                    'sales::css.getListLessThreeStarByQuestion',
                    'sales::css.getProposesByQuestion',
                ]
            ],
        ]
    ], //end css
    
    'S-1' => [
        'label' => 'Setting',
        'child' => [
            'edit.team' => [
                'label' => 'Setting team / role',
                'routes' => [
                    'team::setting.team.*',
                    'team::setting.role.*',
                ]
            ],
        ]
    ], //end S-1 setting
];
