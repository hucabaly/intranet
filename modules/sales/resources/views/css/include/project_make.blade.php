<table class="table table-bordered bang1">
    <tr><td colspan="5" class="top"><label class="project-info-title">{{ trans('sales::view.Project infomation') }}</label></td></tr>
      <tr>
          <td class="title"><label>{{ trans('sales::view.Project name') }}<label</td>
        <td></td>
        <td class="title2"><label>{{ trans('sales::view.Period') }}</label></td>
        <td></td>
        <td class="make_date"><label>{{ trans('sales::view.Make date') }}</label></td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.Sale name') }}</label></td>
        <td></td>
        <td class="title2"><label>{{ trans('sales::view.Customer name') }}</label></td>
        <td></td>
        <td rowspan="3" class="diemso-base">
            <div>{{ trans('sales::view.Total mark') }}</div>
            <div class="diem">68.00</div>
        </td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.PM name') }}</label></td>
        <td></td>
        <td class="title2"><label>{{ trans('sales::view.Make name') }}</label></td>
        <td><input type="text" id="make_name" name="make_name" ></td>

    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.BrSE name') }}</label></td>
        <td></td>
        <td class="title2"><label>{{ trans('sales::view.Make email') }}</label></td>
        <td><input type="text" id="make_email" name="make_email" ></td>

    </tr>
</table>

<table class="table table-bordered bang2 table-base">
  <!-- header -->
  <tr class="header">
      <td><label>{{ trans('sales::view.No.') }}</label></td>
    <td><label>{{ trans('sales::view.Question') }}</label></td>
    <td class="reply"><label>{{ trans('sales::view.Rating') }}</label></td>
    <td class="comment"><label>{{ trans('sales::view.Comment') }}</label></td>
</tr>

<!-- muc to 1 -->
<tr class="mucto">
    <td class="title" colspan="3">I. プロジェクトの品質</td>

    <td class="title2">（★）と（★★）の項目がある場合はコメントをご記入ください。</td>

</tr>

<!-- muc be 1 -->
<tr class="mucbe">
    <td class="title" colspan="3">1.要求分析工程の品質</td>

    <td class="title2">
    </tr>

    <!-- cau 1 -->
    <tr class="cau">
        <td class="title" colspan="2">① プロジェクトチームの要求分析及び理解能力についてどう思われますか。</td>

        <td class="title2">★★ - やや不満</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 2 -->
    <tr class="cau">
        <td class="title" colspan="2">②　関連成果物に要求変更が十分に反映されていましたか。（要求仕様書、ソースコード、テストドキュメント等）</td>

        <td class="title2">★★★ - 普通</td>
        <td class="title2"></td>
    </tr>


    <!-- muc be 2 -->  
    <tr class="mucbe">
        <td class="title" colspan="3">2.設計工程の品質</td>

        <td class="title2"></td>
    </tr>

    <!-- cau 3 -->
    <tr class="cau">
        <td class="title" colspan="2">③ プロジェクトチームが作成した設計書の内容は明確でしたか。</td>

        <td class="title2">★★★★★ - 満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 4 -->
    <tr class="cau">
        <td class="title" colspan="2">④ 設計のソリューションは適切でしたか。</td>

        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- muc be 3 -->
    <tr class="mucbe">
        <td class="title" colspan="3">3.コーディング工程の品質</td>

        <td class="title2"></td>
    </tr>

    <!-- cau 5 -->
    <tr class="cau">
        <td class="title" colspan="2">⑤  ソースコードは明確に記述されていましたか（コーディング規則に従い、コメントは十分でしたか。）</td>

        <td class="title2">★★ - やや不満</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 6 -->
    <tr class="cau">
        <td class="title" colspan="2">⑥ ソースコードは、ご要求の機能を全て反映していましたか。</td>

        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 7 -->
    <tr class="cau">
        <td class="title" colspan="2">⑦　単体テストはどう思われますか。</td>

        <td class="title2">★★★★★ - 満足</td>
        <td class="title2"></td>
    </tr>

    <!-- muc be 4 -->
    <tr class="mucbe">
        <td class="title" colspan="3">4.プロジェクトのテスト工程の品質</td>

        <td class="title2"></td>
    </tr>

    <!-- cau 8 -->
    <tr class="cau">
        <td class="title" colspan="2">⑧ テストのドキュメントの品質は良好でしたか。</td>

        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 9 -->
    <tr class="cau">
        <td class="title" colspan="2">⑨ テスト実施の品質は良好でしたか。</td>

        <td class="title2">★★★ - 普通</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 10 -->
    <tr class="cau">
        <td class="title" colspan="2">⑩　不具合対応の品質はどう思われますか。（影響範囲の調査、対応期間など）</td>

        <td class="title2">★★ - やや不満</td>
        <td class="title2"></td>
    </tr>

    <!-- muc be 5 -->
    <tr class="mucbe">
        <td class="title" colspan="3">5.プロジェクトのお客様サポートの品質</td>

        <td class="title2"></td>
    </tr>

    <!-- cau 11 -->
    <tr class="cau">
        <td class="title" colspan="2">⑪ チームのレスポンスタイムはいかがでしたか。</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 12 -->
    <tr class="cau">
        <td class="title" colspan="2">⑫チームの提案したソリューションは適切なものでしたか。</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- muc to 2 -->
    <tr class="mucto">
        <td class="title" colspan="4">II.プロジェクト管理</td>
    </tr>

    <!-- cau 13 -->
    <tr class="cau">
        <td class="title" colspan="2">⑬　プロジェクトチームのスケジュールの遵守度はいかがでしたか。（進捗状況がスケジュール通りであったかどうか、納品は納期通りであったかどうか等） </td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 14 -->
    <tr class="cau">
        <td class="title" colspan="2">⑭　プロジェクトチームの報告書の作成能力とその質はいかがでしたか。</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 15 -->
    <tr class="cau">
        <td class="title" colspan="2">⑮　プロジェクトチームの課題・リスク管理能力はいかがでしたか。</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 16 -->
    <tr class="cau">
        <td class="title" colspan="2"> ⑯　プロジェクトチームの熱心度はいかがでしたか。　</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 17 -->
    <tr class="cau">
        <td class="title" colspan="2">⑰　貴社とプロジェクトチーム（又は弊社）との関係はいかがでしたでしょうか。仕事上やそれ以外も含む直接の会話やメールを通してのやりとりによる関係</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- muc to 3 -->
    <tr class="mucto">
        <td class="title" colspan="4">III.ブリッジSEのチームに関する評価</td>
    </tr>

    <tr class="cau">
        <td class="title" colspan="3">担当ブリッジSE氏名：</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 18 -->
    <tr class="cau">
        <td class="title" colspan="2">⑱　テクニカルスキルについてどう思われましたか。（要求の理解力、設計、コーディング能力等）</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 19 -->
    <tr class="cau">
        <td class="title" colspan="2"> ⑲　日本語能力についてどう思われましたか。（メールの日本語、会議での日本語、ドキュメントの日本語訳の質など）</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- cau 20 -->
    <tr class="cau">
        <td class="title" colspan="2">⑳　問題解決能力、プロジェクトサポート能力についてどう思われましたか。</td>
        
        <td class="title2">★★★★ - やや満足</td>
        <td class="title2"></td>
    </tr>

    <!-- muc to 4 -->
    <tr class="mucto">
        <td class="title" colspan="4">IV. 全体</td>
    </tr>

    <!-- cau tong quat -->
    <tr class="cau">
        <td class="title" colspan="2">全体的に、弊社の提供した製品とサービスにご満足いただけましたか。</td>
        
        <td class="title2">★★★★★ - 満足</td>
        <td class="title2"></td>
    </tr>

    <!-- danh gia chung -->
    <tr class="cau">
        <td class="title" >
            <div>このプロジェクトで貴社にさらに満足いただくには、</div>
            <div>・プロジェクトの品質</div>
            <div>・プロジェクト管理能力</div>
            <div>・日本語でのコミュニケーション能力</div>
            <div>を向上するために、弊社は何をすべきであったと思われますか。</div>
            <div>（最も重要と思われる項目を3つ記入してください。）</div>
        </td>
        
        <td class="title2" colspan="2"></td>
        <td class="title2"></td>
    </tr>
</table>