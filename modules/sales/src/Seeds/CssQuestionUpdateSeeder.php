<?php
namespace Rikkei\Sales\Seeds;

use Illuminate\Database\Seeder;
use DB;

class CssQuestionUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //PROJECT BASE
        
        DB::table('css_question')->where('id',1)
                ->update(['content' => 'プロジェクトチームの要求分析及び理解能力についてどう思われますか。']);
        
        DB::table('css_question')->where('id',2)
                ->update(['content' => '関連成果物に要求変更が十分に反映されていましたか。（要求仕様書、ソースコード、テストドキュメント等）']);
        
        DB::table('css_question')->where('id',3)
                ->update(['content' => 'プロジェクトチームが作成した設計書の内容は明確でしたか。']);
        
        DB::table('css_question')->where('id',4)
                ->update(['content' => '設計のソリューションは適切でしたか。']);
        
        DB::table('css_question')->where('id',5)
                ->update(['content' => 'ソースコードは明確に記述されていましたか（コーディング規則に従い、コメントは十分でしたか。）']);
        
        DB::table('css_question')->where('id',6)
                ->update(['content' => 'ソースコードは、ご要求の機能を全て反映していましたか。']);
        
        DB::table('css_question')->where('id',7)
                ->update(['content' => '単体テストはどう思われますか。','category_id' => 9]);
        
        DB::table('css_question')->where('id',8)
                ->update(['content' => 'テストのドキュメントの品質は良好でしたか。']);
        
        DB::table('css_question')->where('id',9)
                ->update(['content' => 'テスト実施の品質は良好でしたか。']);
        
        DB::table('css_question')->where('id',10)
                ->update(['content' => '不具合対応の品質はどう思われますか。（影響範囲の調査、対応期間など）']);
        
        DB::table('css_question')->where('id',11)
                ->update(['content' => 'チームのレスポンスタイムはいかがでしたか。']);
        
        DB::table('css_question')->where('id',12)
                ->update(['content' => 'チームの提案したソリューションは適切なものでしたか。']);
        
        DB::table('css_question')->where('id',13)
                ->update(['content' => 'プロジェクトチームのスケジュールの遵守度はいかがでしたか。（進捗状況がスケジュール通りであったかどうか、納品は納期通りであったかどうか等）']);
        
        DB::table('css_question')->where('id',14)
                ->update(['content' => 'プロジェクトチームの報告書の作成能力とその質はいかがでしたか。']);
        
        DB::table('css_question')->where('id',15)
                ->update(['content' => 'プロジェクトチームの課題・リスク管理能力はいかがでしたか。']);
        
        DB::table('css_question')->where('id',16)
                ->update(['content' => 'プロジェクトチームの熱心度はいかがでしたか。']);
        
        DB::table('css_question')->where('id',17)
                ->update(['content' => '貴社とプロジェクトチーム（又は弊社）との関係はいかがでしたでしょうか。仕事上やそれ以外も含む直接の会話やメールを通してのやりとりによる関係']);
        
        DB::table('css_question')->where('id',18)
                ->update(['content' => 'テクニカルスキルについてどう思われましたか。（要求の理解力、設計、コーディング能力等）']);
        
        DB::table('css_question')->where('id',19)
                ->update(['content' => '日本語能力についてどう思われましたか。（メールの日本語、会議での日本語、ドキュメントの日本語訳の質など）']);
        
        DB::table('css_question')->where('id',20)
                ->update(['content' => '問題解決能力、プロジェクトサポート能力についてどう思われましたか。']);
        
        DB::table('css_question')->where('id',21)
                ->update(['content' => '全体的に、弊社の提供した製品とサービスにご満足いただけましたか。']);
        
        
        
        //OSDC
        
        DB::table('css_question')->where('id',22)
                ->update(['content' => '弊社スタッフの能力は割り当てられた作業に適していましたか。']);
        
        DB::table('css_question')->where('id',23)
                ->update(['content' => '弊社スタッフのテクニカルスキルについてどう思われましたか。（要求の理解力、設計、コーディング能力等）']);
        
        DB::table('css_question')->where('id',24)
                ->update(['content' => '弊社スタッフの問題解決能力をどう思われましたか。']);
        
        DB::table('css_question')->where('id',25)
                ->update(['content' => '弊社スタッフのチームワークスキルについてどう思われますか。']);
        
        DB::table('css_question')->where('id',26)
                ->update(['content' => 'プロジェクトチームのスケジュールの遵守度はいかがでしたか。（進捗状況がス']);
        
        DB::table('css_question')->where('id',27)
                ->update(['content' => 'プロジェクトチームの報告書の作成能力とその質はいかがでしたか。']);
        
        DB::table('css_question')->where('id',28)
                ->update(['content' => '関連成果物に要求変更が十分に反映されていましたか。（要求仕様書、ソースコード、テストドキュメント等）']);
        
        DB::table('css_question')->where('id',29)
                ->update(['content' => '弊社スタッフの作業完了度について評価して下さい。']);
        
        DB::table('css_question')->where('id',30)
                ->update(['content' => '弊社スタッフの作業の質についてどう思われますか。']);
        
        DB::table('css_question')->where('id',31)
                ->update(['content' => '弊社スタッフの仕事対応のレスポンスについてどう思われますか。']);
        
        DB::table('css_question')->where('id',32)
                ->update(['content' => '弊社スタッフの作業に対する責任感・義務感について評価して下さい。 ']);
        
        DB::table('css_question')->where('id',33)
                ->update(['content' => '担当したプロジェクト、弊社、貴社の利益に対する弊社スタッフの責任意識についてどう感じられましたか。']);
        
        DB::table('css_question')->where('id',34)
                ->update(['content' => '弊社スタッフの、仕事や私事的な障害を乗り越える能力についてどう思われましたか']);
        
        DB::table('css_question')->where('id',35)
                ->update(['content' => '弊社スタッフは就業時間を有効に利用しましたか。']);
        
        DB::table('css_question')->where('id',36)
                ->update(['content' => '弊社スタッフの貴社の一般的な規律に対する遵守度はどうでしたか。']);
        
        DB::table('css_question')->where('id',37)
                ->update(['content' => 'メールにおける日本語翻訳の質について評価してください。']);
        
        DB::table('css_question')->where('id',38)
                ->update(['content' => '各種ドキュメントにおける日本語翻訳の質について評価してください。']);
        
        DB::table('css_question')->where('id',39)
                ->update(['content' => '会議（電話会議、TV会議、チャット等を含む）における通訳の日本語の質について評価してください。']);
        
        DB::table('css_question')->where('id',40)
                ->update(['content' => 'ラボ担当者の責任感について評価してください。（各プロジェクトの確認、リソース調整能力など）']);
        
        DB::table('css_question')->where('id',41)
                ->update(['content' => '貴社とプロジェクトチーム（又は弊社）との関係はいかがでしたでしょうか。仕事上やそれ以外も含む直接の会話やメールを通してのやりとりによる関']);
        
        DB::table('css_question')->where('id',42)
                ->update(['content' => '全体的に、弊社の提供した製品とサービスにご満足いただけましたか。']);
    }
}
