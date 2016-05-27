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
                'content' => '1. Khả năng phân tích yêu cầu và lý giải yêu cầu của team đánh giá thế nào',
                'category_id' => '6'
            ],
            [
                'content' => '2. Các thay đổi yêu cầu có được đáp ứng đầy đủ vào sản phẩm không (tài liệu spec, source code, …)',
                'category_id' => '6'
            ],
            [
                'content' => '3. Tài liệu thiết kế của team có nội dung chính xác, rõ ràng không?',
                'category_id' => '7'
            ],
            [
                'content' => '4. Solution của thiết kế có thích hợp không?',
                'category_id' => '7'
            ],
            [
                'content' => '5. Source code có được viết rõ ràng không? (có đúng convention không, có comment đầy đủ không)',
                'category_id' => '8'
            ],
            [
                'content' => '6. Source code có phản ánh đầy đủ nội dung spec không?',
                'category_id' => '8'
            ],
            [
                'content' => '7. Bạn nghĩ thế nào về khả năng làm unit test của team',
                'category_id' => '8'
            ],
            [
                'content' => '8. Chất lượng của tài liệu test do team tạo ra thế nào?',
                'category_id' => '9'
            ],
            [
                'content' => '9. Chất lượng thực hiện test của team thế nào?',
                'category_id' => '9'
            ],
            [
                'content' => '10. Chất lượng đối ứng bug của team thế nào?',
                'category_id' => '9'
            ],
            [
                'content' => '11. Response của team có nhanh hay không?',
                'category_id' => '10'
            ],
            [
                'content' => '12. Solution mà team đề xuất có thích hợp hay không?',
                'category_id' => '10'
            ],
            [
                'content' => '13. Team có đảm bảo được schedule không? (Thực hiện đúng tiến độ như schedule đề ra, release đúng thời gian kế hoạch)',
                'category_id' => '4'
            ],
            [
                'content' => '14. Khả năng tạo tài liệu báo cáo của team thế nào?',
                'category_id' => '4'
            ],
            [
                'content' => '15. Khả năng quản lý rủi ro, quản lý vấn đề của team thế nào?',
                'category_id' => '4'
            ],
            [
                'content' => '16. Team làm việc có nhiệt tình, hăng hái hay không?',
                'category_id' => '4'
            ],
            [
                'content' => '17. Quan hệ và thái độ của nhân viên trong team khi làm việc với khách hàng có tốt hay không?',
                'category_id' => '4'
            ],
            [
                'content' => '18. Khả năng kỹ thuật của BrSE đánh giá thế nào? (khả năng phân tích và truyền đạt yêu cầu, thiết kế, coding,…)',
                'category_id' => '5'
            ],
            [
                'content' => '19. Khả năng tiếng Nhật của BrSE thế nào (mail tiếng Nhật, chat tiếng Nhật, nói chuyện tiếng Nhật, dịch tài liệu tiếng Nhật,…)',
                'category_id' => '5'
            ],
            [
                'content' => '20. Khả năng giải quyết vấn đề và support cho dự án đánh giá thế nào',
                'category_id' => '5'
            ],
            
            
            
            
            
            [
                'content' => '1. Năng lực của nhân viên công ty cung cấp cho các bạn phù hợp chứ?',
                'category_id' => '15'
            ],
            [
                'content' => '2. Kỹ năng về kỹ thuật của nhân viên công ty cung cấp cho các bạn phù hợp chứ (khả năng phân tích yêu cầu, thiết kế, coding,..)',
                'category_id' => '15'
            ],
            [
                'content' => '3. Khả năng giải quyết vấn đề của nhân viên công ty cung cấp cho các bạn thế nào?',
                'category_id' => '15'
            ],
            [
                'content' => '4. Kỹ năng làm việc nhóm của các thành viên trong team thế nào?',
                'category_id' => '15'
            ],
            [
                'content' => '5. Các project trong OSDC có đảm bảo tiến độ đã nếu trong schedule hay không?',
                'category_id' => '15'
            ],
            [
                'content' => '6. Khả năng tạo tài liệu báo cáo, report hàng ngày của member trong OSDC có tốt không?',
                'category_id' => '15'
            ],
            [
                'content' => '7. Các thay đổi về requirement có được phản ánh đầy đủ vào sản phẩm hay không? (tài liệu requiremetn, source code,…)',
                'category_id' => '15'
            ],
            [
                'content' => '8. Các task của member có được hoàn thành 1 cách triệt để hay không?',
                'category_id' => '16'
            ],
            [
                'content' => '9. Việc hoàn thành task của member chất lượng thế nào?',
                'category_id' => '16'
            ],
            [
                'content' => '10. Thời gian response của member có tốt không?',
                'category_id' => '16'
            ],
            [
                'content' => '11. Trách nhiệm và thái độ làm việc của member trong team thế nào?',
                'category_id' => '17'
            ],
            [
                'content' => '12. Member có trách nhiệm và ý thức được rằng phải nỗ lực vì lợi ích của công ty và của khách hàng hay không?',
                'category_id' => '17'
            ],
            [
                'content' => '13. Member trong team có cố gắng khắc vụ những sự cố hay việc cá nhân để hoàn thành dự án tốt hay không?',
                'category_id' => '17'
            ],
            [
                'content' => '14. Member có dùng hiệu quả thời gian làm việc của mình hay không? (làm việc riêng trong giờ làm việc, làm các công việc khác,…)',
                'category_id' => '18'
            ],
            [
                'content' => '15. Member có đảm bảo tuân thủ đúng các quy tắc nội dung đã thống nhất giữa 2 công ty hay không?',
                'category_id' => '18'
            ],
            [
                'content' => '16. Khả năng đọc hiểu và dịch mail tiếng Nhật có tốt không?',
                'category_id' => '13'
            ],
            [
                'content' => '17. Khả năng dịch các tài liệu tiếng Nhật có tốt không?',
                'category_id' => '13'
            ],
            [
                'content' => '18. Chất lượng dịch tiếng Nhật trong các buổi họp với khách hàng có tốt hay không?',
                'category_id' => '13'
            ],
            [
                'content' => '19. Hãy đánh giá trách nhiệm của người phụ trách OSDC (quan tâm đến tình hình các dự án, khả năng điều chỉnh nguồn lực,…)',
                'category_id' => '14'
            ],
            [
                'content' => '20. Quan hệ giữa Team và khách hàng có tốt hay không?',
                'category_id' => '14'
            ]
        ];
        foreach ($dataDemo as $data) {
            if (! DB::table('css_question')->select('id')->where('content', $data['content'])->get()) {
                DB::table('css_question')->insert($data);
            }
        }
    }
}
