<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\Team;
use Rikkei\Team\Model\User;
use Rikkei\Team\Model\Position;
use Rikkei\Team\Model\TeamRule;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('team')->where('name', 'BOD')->where('parent_id', 0)->get()) {
            return;
        }
        $dataDemo = [
            [
                'name' => 'BOD',
                'permission_as' => '0',
                'is_function' => '0',
                'child' => [
                    [
                        'name' => 'Rikkei - Hanoi',
                        'permission_as' => '0',
                        'is_function' => '0',
                        'child' => [
                            [
                                'name' => 'PTPM',
                                'permission_as' => '0',
                                'is_function' => '0',
                                'child' => [
                                    [
                                        'name' => 'Web',
                                        'permission_as' => '0',
                                        'is_function' => '1',
                                    ],
                                    [
                                        'name' => 'Mobile',
                                        'permission_as' => '0',
                                        'is_function' => '1',
                                        'child' => [
                                            [
                                                'name' => 'Android',
                                                'permission_as' => '0',
                                                'is_function' => '1',
                                            ],
                                            [
                                                'name' => 'iOS',
                                                'permission_as' => '0',
                                                'is_function' => '1',
                                            ],
                                        ]
                                    ],
                                    [
                                        'name' => 'Finance',
                                        'permission_as' => '0',
                                        'is_function' => '1',
                                    ],
                                    [
                                        'name' => 'Game',
                                        'permission_as' => '0',
                                        'is_function' => '1',
                                    ],
                                    [
                                        'name' => 'QA',
                                        'permission_as' => '0',
                                        'is_function' => '1',
                                    ],
                                ]
                            ], //end PTPM
                            [
                                'name' => 'Nhân sự',
                                'permission_as' => '0',
                                'is_function' => '1',
                            ],
                            [
                                'name' => 'HC - TH',
                                'permission_as' => '0',
                                'is_function' => '1',
                            ],
                            [
                                'name' => 'Sales',
                                'permission_as' => '0',
                                'is_function' => '1',
                            ],
                        ]
                    ], // end rikkei hanoi
                    [
                        'name' => 'Rikkei - Danang',
                        'permission_as' => '0',
                        'is_function' => '0',
                    ],
                    [
                        'name' => 'Rikkei - Jappan',
                        'permission_as' => '0',
                        'is_function' => '0',
                    ],
                ]
            ],
        ];
        DB::beginTransaction();
        try {
            $this->createTeamRecursive($dataDemo, 0);
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
     */
    protected function createTeamRecursive($data, $parentId)
    {
        foreach ($data as $key => $item) {
            $dataChild = null;
            if (isset($item['child'] ) && count($item['child']) > 0) {
                $dataChild = $item['child'];
                unset($item['child']);
            }
            $itemDataAddtional = [
                'parent_id' => $parentId,
                'position' => $key + 1
            ];
            $item = array_merge($item, $itemDataAddtional);
            $team = Team::create($item);
            if ($dataChild) {
                $this->createTeamRecursive($dataChild, $team->id);
            }
        }
    }
}
