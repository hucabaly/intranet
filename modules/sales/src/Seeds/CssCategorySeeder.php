<?php
namespace Rikkei\Sales\Seeds;

use Illuminate\Database\Seeder;
use DB;

class CssCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataDemo = [
            [
                'name' => 'OSDC',
                'parent_id' => '0'
            ],
            [
                'name' => 'Project base',
                'parent_id' => '0'
            ],
            [
                'name' => 'I. Chất lượng dự án',
                'parent_id' => '2'
            ],
            [
                'name' => 'II. Về quản lý dự án',
                'parent_id' => '2'
            ],
            [
                'name' => 'III. Về đánh giá BrSE',
                'parent_id' => '2'
            ],
            [
                'name' => '1. Về chất lượng giai đoạn phân tích yêu cầu',
                'parent_id' => '3'
            ],
            [
                'name' => '2. Về chất lượng giai đoạn thiết kế',
                'parent_id' => '3'
            ],
            [
                'name' => '3. Về chất lượng giai đoạn coding',
                'parent_id' => '3'
            ],
            [
                'name' => '4. Về chất lượng giai đoạn test',
                'parent_id' => '3'
            ],
            [
                'name' => '5. Về Chất lượng support khách hàng',
                'parent_id' => '3'
            ],
            [
                'name' => 'I. Về năng lực và thành tích',
                'parent_id' => '1'
            ],
            [
                'name' => 'II. Chất lượng dịch vụ',
                'parent_id' => '1'
            ],
            [
                'name' => 'III. Khả năng communication bằng tiếng Nhật',
                'parent_id' => '1'
            ],
            [
                'name' => 'IV. Đánh giá người phụ trách OSDC',
                'parent_id' => '1'
            ],
            [
                'name' => '1. Kỹ năng, năng lực',
                'parent_id' => '11'
            ],
            [
                'name' => '2. Hiệu quả làm việc',
                'parent_id' => '11'
            ],
            [
                'name' => '3. Tinh trách nhiệm',
                'parent_id' => '12'
            ],
            [
                'name' => '4. Nội quy lao động',
                'parent_id' => '12'
            ]
            
        ];
        foreach ($dataDemo as $data) {
            if (! DB::table('css_category')->select('id')->where('name', $data['name'])->get()) {
                DB::table('css_category')->insert($data);
            }
        }
    }
}
