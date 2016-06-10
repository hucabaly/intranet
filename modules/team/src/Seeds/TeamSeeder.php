<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bodTeam = Team::where('name', 'BOD')->where('parent_id', null)->first();
        if ($bodTeam) {
            return;
        }
        $dataDemo = [
            [
                'name' => 'BOD',
                'is_function' => '0',
                'follow_team_id' => '0',
                'child' => [
                    [
                        'name' => 'Rikkei - Hanoi',
                        'is_function' => '0',
                        'follow_team_id' => '0',
                        'child' => [
                            [
                                'name' => 'PTPM',
                                'is_function' => '1',
                                'follow_team_id' => '0',
                                'flag_permission_children' => 1,
                                'child' => [
                                    [
                                        'name' => 'Web',
                                        'is_function' => '1',
                                    ],
                                    [
                                        'name' => 'Mobile',
                                        'is_function' => '1',
                                        'child' => [
                                            [
                                                'name' => 'Android',
                                                'is_function' => '1',
                                            ],
                                            [
                                                'name' => 'iOS',
                                                'is_function' => '1',
                                            ],
                                        ]
                                    ],
                                    [
                                        'name' => 'Finance',
                                        'is_function' => '1',
                                    ],
                                    [
                                        'name' => 'Game',
                                        'is_function' => '1',
                                    ],
                                    [
                                        'name' => 'QA',
                                        'is_function' => '1',
                                    ],
                                ]
                            ], //end PTPM
                            [
                                'name' => 'Nhân sự',
                                'is_function' => '1',
                                'follow_team_id' => '0',
                            ],
                            [
                                'name' => 'HC - TH',
                                'is_function' => '1',
                                'follow_team_id' => '0',
                            ],
                            [
                                'name' => 'Sales',
                                'is_function' => '1',
                                'follow_team_id' => '0',
                            ],
                        ]
                    ], // end rikkei hanoi
                    [
                        'name' => 'Rikkei - Danang',
                        'is_function' => '0',
                        'follow_team_id' => '0',
                    ],
                    [
                        'name' => 'Rikkei - Jappan',
                        'follow_team_id' => '0',
                        'is_function' => '0',
                    ],
                ]
            ],
        ];
        DB::beginTransaction();
        try {
            $this->createTeamRecursive($dataDemo, null, 0);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        
    }
    
    /**
     * create team item demo
     * 
     * @param array $data
     * @param int $parentId
     * @param int $permissionAsId
     */
    protected function createTeamRecursive($data, $parentId, $permissionAsId = 0)
    {
        foreach ($data as $key => $item) {
            $dataChild = null;
            if (isset($item['child'] ) && count($item['child']) > 0) {
                $dataChild = $item['child'];
                unset($item['child']);
            }
            $itemDataAddtional = [
                'parent_id' => $parentId,
                'sort_order' => $key + 1
            ];
            if (! isset($item['follow_team_id'])) {
                $itemDataAddtional['follow_team_id'] = $permissionAsId;
            }
            if (isset($item['flag_permission_children']) && $item['flag_permission_children']) {
                $permissionAsId = true;
                unset($item['flag_permission_children']);
            }
            $item = array_merge($item, $itemDataAddtional);
            $team = Team::create($item);
            if ($dataChild) {
                if ($permissionAsId === true) {
                    $permissionAsId = $team->id;
                }
                $this->createTeamRecursive($dataChild, $team->id, $permissionAsId);
            }
        }
    }
}
