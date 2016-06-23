<?php
return [
    [
        'team' => 'PTPM',
        'role' => 'Team Leader',
        'scope' => \Rikkei\Team\Model\Permissions::SCOPE_TEAM,
    ],
    [
        'team' => 'PTPM',
        'role' => 'Sub-Leader',
        'scope' => \Rikkei\Team\Model\Permissions::SCOPE_TEAM,
    ],
    [
        'team' => 'PTPM',
        'role' => 'Member',
        'scope' => \Rikkei\Team\Model\Permissions::SCOPE_SELF,
    ],
    [
        'team' => 'BOD',
        'role' => 'Member',
        'scope' => \Rikkei\Team\Model\Permissions::SCOPE_COMPANY,
    ],
];