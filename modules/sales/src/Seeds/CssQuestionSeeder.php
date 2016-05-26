<?php
namespace Rikkei\Sales\Seeds;

use Illuminate\Database\Seeder;
use DB;

class CssQuestionSeeder extends Seeder
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
                'content' => 'Khả năng phân tích yêu cầu và lý giải yêu cầu của team đánh giá thế nào',
                'category_id' => '6'
            ],
            [
                'content' => 'Các thay đổi yêu cầu có được đáp ứng đầy đủ vào sản phẩm không (tài liệu spec, source code, …)',
                'category_id' => '6'
            ],
            [
                'content' => 'Tài liệu thiết kế của team có nội dung chính xác, rõ ràng không?',
                'category_id' => '7'
            ],
            [
                'content' => 'Solution của thiết kế có thích hợp không?',
                'category_id' => '7'
            ],
            [
                'content' => 'Source code có được viết rõ ràng không? (có đúng convention không, có comment đầy đủ không)',
                'category_id' => '8'
            ],
            [
                'content' => 'Source code có phản ánh đầy đủ nội dung spec không?',
                'category_id' => '8'
            ],
            [
                'content' => 'Bạn nghĩ thế nào về khả năng làm unit test của team',
                'category_id' => '8'
            ],
            [
                'content' => 'Chất lượng của tài liệu test do team tạo ra thế nào?',
                'category_id' => '9'
            ],
            [
                'content' => 'Chất lượng thực hiện test của team thế nào?',
                'category_id' => '9'
            ],
            [
                'content' => 'Chất lượng đối ứng bug của team thế nào?',
                'category_id' => '9'
            ],
            [
                'content' => 'Response của team có nhanh hay không?',
                'category_id' => '10'
            ],
            [
                'content' => 'Solution mà team đề xuất có thích hợp hay không?',
                'category_id' => '10'
            ],
            [
                'content' => 'Team có đảm bảo được schedule không? (Thực hiện đúng tiến độ như schedule đề ra, release đúng thời gian kế hoạch)',
                'category_id' => '4'
            ],
            [
                'content' => 'Khả năng tạo tài liệu báo cáo của team thế nào?',
                'category_id' => '4'
            ],
            [
                'content' => 'Khả năng quản lý rủi ro, quản lý vấn đề của team thế nào?',
                'category_id' => '4'
            ],
            [
                'content' => 'Team làm việc có nhiệt tình, hăng hái hay không?',
                'category_id' => '4'
            ],
            [
                'content' => 'Quan hệ và thái độ của nhân viên trong team khi làm việc với khách hàng có tốt hay không?',
                'category_id' => '4'
            ],
            [
                'content' => 'Khả năng kỹ thuật của BrSE đánh giá thế nào? (khả năng phân tích và truyền đạt yêu cầu, thiết kế, coding,…)',
                'category_id' => '5'
            ],
            [
                'content' => 'Khả năng tiếng Nhật của BrSE thế nào (mail tiếng Nhật, chat tiếng Nhật, nói chuyện tiếng Nhật, dịch tài liệu tiếng Nhật,…)',
                'category_id' => '5'
            ],
            [
                'content' => 'Khả năng giải quyết vấn đề và support cho dự án đánh giá thế nào',
                'category_id' => '5'
            ]
        ];
        foreach ($dataDemo as $data) {
            if (! DB::table('css_question')->select('id')->where('content', $data['content'])->get()) {
                DB::table('css_question')->insert($data);
            }
        }
    }
}
