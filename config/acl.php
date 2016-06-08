<?php

return [
    'A-2' => [
        'label' => 'Profile',
        'child' => [
            'view.profile' => [
                'label' => 'View thông tin ở trang cá nhân',
                'routes' => [
                    'profile.*'
                ]
            ],
            'view.team.member' => [
                'label' => 'Xem danh sách các thành viên',
                'routes' => [
                    'team::team.member.index'
                ]
            ],
            'view.review.test.employee' => [
                'label' => 'Xem lại bài test của nhân viên',
                'routes' => [
                    
                ]
            ],
            'view.point.employee' => [
                'label' => 'Xem danh sách điểm cộng trừ của nhân viên',
                'routes' => [
                ]
            ],
            'edit.information.base' => [
                'label' => 'Chỉnh sửa thông tin cơ bản, link facebook, upload CV',
                'routes' => [
                    'team::team.member.edit',
                    'team::team.member.save',
                ]
            ],
            'edit.team.position' => [
                'label' => 'Chỉnh sửa thông tin về team, vị trí',
                'routes' => [
                    'team::team.member.edit.team.position'
                ]
            ],
            'edit.role' => [
                'label' => 'Chỉnh sửa thông tin vai trò đặc biệt',
                'routes' => [
                    'team::team.member.edit.role'
                ]
            ],
            'edit.point' => [
                'label' => 'Chỉnh sửa điểm cộng, điểm trừ',
                'routes' => [
                ]
            ],
            'edit.test.schedule' => [
                'label' => 'Chỉnh sửa phần test định kỳ',
                'routes' => [
                ]
            ],
            'edit.skill' => [
                'label' => 'Chỉnh sửa phần trình độ và kỹ năng',
                'routes' => [
                ]
            ],
            'edit.experience' => [
                'label' => 'Chỉnh sửa phần kinh nghiêm dự án',
                'routes' => [
                ]
            ],
            'delete.employee.left' => [
                'label' => 'Set nhân viên sang nghỉ việc',
                'routes' => [
                ]
            ],
            'add.account' => [
                'label' => 'Đăng ký thành viên mới',
                'routes' => [
                ]
            ],
        ],
    ], // end A-2 profile
    
    'B-1' => [
        'label' => 'Tuyển dụng',
        'child' => [
            'view.list.recruiment' => [
                'label' => 'Xem danh sách yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'view.details.recruiment' => [
                'label' => 'Xem chi tiết yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'view.download.cv.applicant' => [
                'label' => 'Download CV ứng viên',
                'routes' => [
                ]
            ],
            'view.details.profile.applicant' => [
                'label' => 'Xem chi tiết hồ sơ ứng viên',
                'routes' => [
                ]
            ],
            'edit.recruiment' => [
                'label' => 'Chỉnh sửa yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'edit.profile.applicant' => [
                'label' => 'Chỉnh sửa hồ sơ ứng viên',
                'routes' => [
                ]
            ],
            'delete.recruiment' => [
                'label' => 'Xóa yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'delete.profile.applicant' => [
                'label' => 'Xóa hồ sơ ứng viên khỏi yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'add.recruiment' => [
                'label' => 'Thêm yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'add.applicant' => [
                'label' => 'Thêm ứng viên vào yêu cầu đăng tuyển',
                'routes' => [
                ]
            ],
            'system.email.interview.applicant' => [
                'label' => 'Gửi email phỏng vấn tới ứng viên',
                'routes' => [
                ]
            ],
            'system.update.result.interview' => [
                'label' => 'Cập nhập kết quả phỏng vấn',
                'routes' => [
                ]
            ],
            'system.email.offer.applicant' => [
                'label' => 'Gửi email OFFER đến ứng viên',
                'routes' => [
                ]
            ],
            'system.email.decline.applicant' => [
                'label' => 'Gửi email từ chối đến ứng viên',
                'routes' => [
                ]
            ],
            'system.notification.result.recruiment' => [
                'label' => 'Thông báo kết quả tuyển dụng',
                'routes' => [
                ]
            ],
        ],
    ], //end B-1 Recruiment
    
    'B-3' => [
        'label' => 'Tuyển dụng - Summary',
        'child' => [
            'view.hr.inout.month' => [
                'label' => 'Tổng hợp nhân sự in/out trong tháng',
                'routes' => [
                ]
            ],
            'view.hr.inout.month.pre' => [
                'label' => 'Tổng hợp nhận sự in/out các tháng trước',
                'routes' => [
                ]
            ],
            'view.hr.inout.year' => [
                'label' => 'Tổng hợp nhân sự in/out cả năm',
                'routes' => [
                ]
            ],
        ]
    ], //end B-3 Recruiment - summary
    
    'C-1' => [
        'label' => 'Css',
        'child' => [
            'view.list' => [
                'label' => 'Xem danh sách css',
                'routes' => [
                    'sales::css.list'
                ]
            ],
            'view.detail' => [
                'label' => 'Xem chi tiết css',
                'routes' => [
                    'sales::css.view'
                ]
            ],
            'edit.detail' => [
                'label' => 'Tạo và sửa css',
                'routes' => [
                    'sales::css.cancel',
                    'sales::css.create',
                    'sales::css.detail',
                    'sales::css.saveResult',
                    'sales::css.saveResult',
                    'sales::css.update',
                ]
            ],
            'view.analyze' => [
                'label' => 'Phân tích css',
                'routes' => [
                    'sales::css.analyze',
                    'sales::css.filterAnalyze',
                    'sales::css.applyAnalyze',
                    'sales::css.showAnalyzeListProject',
                    'sales::css.getListLessThreeStar',
                    'sales::css.getProposes',
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
