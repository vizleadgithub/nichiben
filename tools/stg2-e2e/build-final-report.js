// テスト計画(plan.json)全行について、最終的な結果(OK/NG/対応不要/未実施)と、対応不要の理由をまとめた
// 一覧を作る。自動テスト結果(report-xss.js と同じ集計)に、これまでのコードレビュー・Chrome拡張での
// 手動確認・既知の不具合対応状況(CLAUDE.md記載)を反映した手動の判定を重ねる。
// 出力: secure_report/脆弱性テスト計画_最終結果一覧.csv
const fs = require('fs');
const path = require('path');
const { RESULT_DIR } = require('./lib');
const { RANK } = require('./detect');

const plan = require('./test-plan/plan.json').rows;

// ---------- 1) 自動テスト結果(report-xss.jsと同じロジック)を集計 ----------
const files = fs.readdirSync(RESULT_DIR)
  .filter((n) => /^xss-(search-\w+|http|form-\w+|student-exam|s12|data-linked)-.*\.json$/.test(n))
  .sort().map((n) => path.join(RESULT_DIR, n));
const byId = new Map();
for (const f of files) {
  const run = require(path.resolve(f)).entries;
  if (/xss-form-/.test(f) && !run.some((e) => e.pid)) continue;
  const runKind = path.basename(f).replace(/^xss-(.+?)-\d{4}-.*$/, '$1');
  for (const id of new Set(run.map((e) => e.id))) {
    byId.set(id, [...(byId.get(id) || []).filter((e) => e.kind !== runKind),
      ...run.filter((e) => e.id === id).map((e) => ({ ...e, kind: runKind }))]);
  }
}
function autoVerdict(id) {
  const entries = byId.get(id);
  if (!entries) return null;
  const worst = entries.reduce((w, e) => (RANK[e.verdict] > RANK[w] ? e.verdict : w), '対象外');
  const note = entries.find((e) => e.note)?.note || '';
  return { worst, note };
}

// ---------- 2) 個別ID指定の手動判定(要確認の再確認・untouched行の解消) ----------
// 結果: 'OK' | 'NG' | '対応不要' | '未実施'
const MANUAL = {};
const set = (ids, result, reason) => { for (const id of ids.split(',').map((s) => s.trim())) MANUAL[id] = { result, reason }; };

// --- 要確認(自動テスト実行済み)の再確認 ---
set('B-0320,B-0341,B-0360', 'OK', '手動テスト(担当:佐々木)で再現確認済み。発見した不具合は[B-22]としてコミット0bae40cで修正し、再テストでOKを確認');
set('E-56', 'OK', '自動テストの所見で「文字としては残っているがエスケープ済み」と確認済み');
set('S12-video,S12-video-caption,S12-video-tag', 'OK', '自動テストの所見で「エスケープ済み」と確認済み(2026-10-09)');
set('A-0055,A-0063', '対応不要', '[B-4]既知の非セキュリティ系ロバストネス不具合(配列型チェックボックス項目の改ざんでHTTP 500になる。情報漏えい・スクリプト実行は無く対応しない方針で確定)');
// 日付欄(search_start_date/search_end_date等)の往復不一致: 攻撃文字列が日付欄の入力正規化で数字断片に
// 書き換わるだけで、未エスケープでの出現ではない(search_elive.phpで実機確認済み。2026-10-12)
set('A-0297,B-0057,B-0058,B-0091,B-0092,B-0203,B-0204,B-0223,B-0224,B-0234,B-0235,B-0310,B-0311,B-0331,B-0332,B-0471,B-0472',
  'OK', '日付欄の入力正規化により攻撃文字列が数字断片に変わるだけの無害な往復不一致(未エスケープ出現ではない。search_elive.phpで実機確認した既知パターンと同一)');
set('B-0478', '対応不要', 'pidを数値以外に改ざんするとHTTP 500になる既知の非セキュリティ系ロバストネス不具合([B-4]と同系統。応答ボディは空でSQL・var_dump・サーバーパス等の情報漏えいは無い)');
// ページ送り(Pager.php/AdminPager.php)のGETパラメータ改ざんで、WordPress連携層のエラーハンドラが
// var_dump(WP_Error)とスタックトレース(/srv/配下のサーバー内部パス)をそのまま応答に出力する新規発見の
// 情報漏えい。2026-10-12、本資料作成の過程で発見(詳細未調査。要優先対応)
set('E-19,E-47,E-52,E-54,F-05,F-06', 'OK', '[B-23]として原因を特定・修正済み(mypage/buy_detail.php・report_product/info.php・csv.phpの3箇所で、該当レコード無しの際に$ret[0]がnullになりcount(null)でTypeError・サーバーパス漏えいしていた)。2026-10-12にデプロイ・再現しないことを確認済み');
// 商品管理の重い登録画面: product/add.php・product_ethics/add.phpは2026-10-11に人手で登録確定まで
// 進め、商品名・備考・講師名・検索ワードともOKを確認済み(タイムアウトはテストハーネスの制約)
set('B-0112,B-0113,B-0114,B-0115,B-0116,B-0118,B-0119,B-0121,B-0130,B-0132,B-0134,B-0137,B-0211,' +
  'B-0247,B-0248,B-0249,B-0250,B-0252,B-0253,B-0254,B-0255,B-0256,B-0265,B-0267,B-0269,B-0272,' +
  'S12-product-live,S12-product-memo,S12-product-name,S12-product-search-word,S12-product-teacher,S12-ethics-memo,S12-ethics-teacher',
  'OK', '商品管理の重い登録画面(submitComplete実行に90秒以上かかりPlaywrightが30秒でタイムアウトするだけ)。2026-10-11に人手で実際に登録確定まで進め、商品名・備考・講師名・検索ワードともエスケープ済みを確認済み');
set('B-0368,B-0369,B-0371,B-0372,B-0373,B-0374,B-0375,B-0376,B-0377,B-0378,B-0379,B-0380,B-0381,B-0382,' +
  'B-0383,B-0384,B-0385,B-0386,B-0387,B-0388,B-0389,B-0390,B-0391,B-0392,B-0393,B-0395,B-0415',
  'OK', '2026-10-12実機確認(Chrome操作): product_live/add.php(会場研修)の登録を単品料金1000円(有料)で完了まで実施(pid=26331)。攻撃文字列・型破り値はいずれもエスケープ済みで文字のまま表示、またはCK_NUM等のチェックで反映なし。クラッシュ・内部パス漏えいなし');
set('B-0322,B-0323,B-0343,B-0344', '未実施', '自動化不可(CSV取り込み画面への「アップロードする」リンククリックがPlaywrightから不安定。原因未解明)。手動(Chrome操作)でなら実施可能');
set('E-10,E-12,E-13', '未実施', '2026-10-12再検証: [B-13](mysql_insert_id())は修正済みで受講者登録自体は成功するが、直後のGMOペイメントサービスへの会員登録(member/regist.php:517 save_member())がstg2環境でエラーを返し、トランザクション全体がロールバックされるため、結局/cms_studentの検索結果に出てこない。アプリの不具合ではなくGMO決済サンドボックスのstg2設定(認証情報・疎通)の問題の可能性が高い。コードでは対応できず、インフラ側の確認が必要');
set('E-49', 'OK', '2026-10-12再検証: カテゴリ登録(cms_category/newdata)のボタン検出は解消し登録は成功することを確認。受講者サイト側のカテゴリ一覧への反映タイミングは未確認(別途要因)だが、カテゴリ名のエスケープ自体は同日の別調査で実在カテゴリ名を直接ページソース確認し安全と確認済み(CLAUDE.md「タグ表示」検証参照)');
set('F-01', 'OK', '2026-10-12コードレビューで実際の出力先を特定: backurlが出力される箇所はCMS側ログイン画面(login_page.php:36)の1箇所のみで、urlencode()適用済みのためHTML/属性への脱出は不可能と確認。リポジトリ全体を検索し他の出力先が無いことも確認済み');
set('F-04', 'OK', '2026-10-12にテストハーネスを修正(Hostヘッダー本体の差し替え確認専用にignoreHTTPSErrors付きのAPIRequestContextを追加)。再テストで6件ともOKを確認(外部ホストへのリンク生成・リダイレクトなし)');
set('F-07', 'NG', 'CSPでscript-srcに\'unsafe-inline\'・\'unsafe-eval\'を許可しており、XSS実行の抑止効果が弱い。X-Content-Type-OptionsがApacheとアプリで重複設定。セキュリティヘッダーの強化余地あり(個別のエスケープ漏れではない)');
set('B-0015,B-0016,B-0046', '未実施', 'テストハーネスの技術的制約(ページ遷移中のタイミング issue)、または[B-13]/[B-22]の修正により再テストで解消する可能性が高い');
set('A-0260', '未実施', '2026-10-12、同名ファイル欄が複数ある問題(.first()指定)は解消したが、cms_rankingの既知のページ遷移なし問題(下記)が残っており未検証。手動確認が必要');
set('A-0039,A-0044', 'OK', '2026-10-12再実行で解消を確認(偶発的なタイムアウトだった)');
set('C-0391,C-0392,C-0404,C-0405', 'OK', '2026-10-12再テストでほぼ全パターンOKを確認。残る一部の「要確認」は、日付欄のdatepicker・グループ送信時のマーカー混同というハーネス側の制約で、実際の値は入力どおり正しくエスケープされていることを確認済み');
set('C-0394,C-0395,C-0396,C-0397,C-0407,C-0408,C-0409,C-0410', 'OK', '日付欄(search_start_date等)はdatepickerウィジェットのためテストハーネスから値を設定できずタイムアウトするが、これらはsearch/index.phpのurl_plam構築処理の一部であり、全構成要素がurlencode()されていることを2026-10-12のコードレビューで確認済み(C-0400〜0406と同一箇所)');
set('C-0398,C-0411', 'OK', 'search_category[](チェックボックス配列)も同じurl_plam構築処理内でurlencode()されていることを確認済み。テストハーネスからのチェックボックス配列操作がタイムアウトするのはハーネス側の制約');
// ページ遷移なし(既知。原因未特定) - 上記で個別対応したB-0320/341/360を除く
set('A-0018,A-0020,A-0134,A-0235,A-0244,A-0248,A-0250,B-0013,B-0413',
  '未実施', '送信してもページ遷移が起きずサーバー応答でのエスケープを確認できない既知の制約(原因未特定。CMSの一部確認画面で再現)。手動確認が必要');
// mailmagazine/form.phpのHTTP500 - mailmagazine/conf.phpのクラッシュ修正(コミットced3ae1)より前の結果の可能性
set('B-0072,B-0073,B-0074,B-0075,B-0076,B-0087,B-0088', 'OK', '2026-10-12再テストで解消を確認(mailmagazine/conf.phpのクラッシュ修正コミットced3ae1がstg2に反映済みで、7件ともOK)');

// --- untouched行(自動テストが一度も触れていない行)の解消 ---
// CMS「表示専用の値」クラスタ: 依頼A〜DのChrome拡張確認で個別に判定
set('A-0031,A-0032,A-0106,A-0107,A-0108,A-0131,A-0132,A-0133,A-0137,A-0138,A-0159,A-0168,A-0175,A-0176,' +
  'A-0198,A-0207,A-0214,A-0215,A-0245,A-0246,A-0256,A-0257,A-0281,A-0282,A-0283,A-0284,A-0285,A-0286,A-0287,' +
  'A-0310,A-0352,A-0410,A-0454,A-0455,A-0461,A-0482',
  'OK', '依頼A〜D(Chrome拡張での実ブラウザ確認)で画面を実際に開き、確認用出力・未エスケープ出現なしを確認済み');
set('A-0058,A-0059', '対応不要', 'cms_classのクラスレコードが0件のため、編集・編集(受講者)画面への到達経路が無い(依頼B確認)');
set('A-0064', '対応不要', 'cms_class_materialへの直接アクセスは空白ページ、親画面(cms_class)もレコード0件で到達経路なし(依頼C確認)');
set('A-0362', '対応不要', 'cms_student_groupのグループレコードが0件のため確認画面への到達経路が無い(依頼C確認)');
set('A-0409', '対応不要', 'cms_student_sub_auth/confirm.phpはどのコントローラからも描画されないオーファンビュー(到達経路なし。2026-10-12コードレビューで確認)');
set('A-0457,A-0458', '対応不要', '/cms_video/newdata1・newdata2はCodeIgniterの有効なアクション名ではなく404(コントローラには単一のnewdata()しか無い。コードレビューで確認)');
set('A-0480', '対応不要', '生授業契約(contract=fixation)が無いアカウントでは管理画面トップへリダイレクトする仕様(コードレビューで確認)。テストアカウントはこの契約を持たない');
set('A-0483', '対応不要', 'メール本文のプレーンテキストテンプレートでHTMLレンダリング文脈が無くXSS観点では対象外(2026-10-12コードレビューで確認)');

// 商品管理(alfproduct) B-番号の「まとめ」表示クラスタ
set('B-0017', 'OK', 'ソース確認で`association_name`等がSmartyの|escape修飾子で出力されていることを確認済み');
set('B-0027,B-0047,B-0319,B-0321,B-0324,B-0327,B-0340,B-0342,B-0345,B-0348,B-0361,B-0479,B-0480,B-0481',
  'OK', '依頼Dで実在IDを用いて実際に画面を開き、確認用出力・未エスケープ出現なしを確認済み(2026-10-12)');
// secure_report/XSS対応_手動テスト手順書.xlsx「3_実施表」(担当:佐々木、Chrome操作)の結果を反映(2026-10-12)
set('B-0016', 'OK', '手動テスト(担当:佐々木)で確認済み。hidden項目(mode・order_detail_id・csrf_token)の改ざんはすべて403または無反映。副次的に見つかった「modeが許可値以外だとCSRF検証されない」問題は[B-18]として修正・stg2反映済みを確認(2026-10-09)。正規のmode=pay操作も実施し入金完了・エラー無しを確認');
set('B-0048', 'OK', '手動テスト(担当:佐々木)で確認済み。CSVファイル名・中身への攻撃文字列はいずれも反映されない。副次的に見つかった「入金日が数字でないCSVでHTTP 500+サーバーパス漏えい」[B-3と同系統]・「列数不正行が無言で無視される」は[B-15][B-20]として修正・stg2反映済みを確認(2026-10-09)。正規の取込も実施し成功・エラー無しを確認');
set('B-0049', '対応不要', '手動テスト(担当:佐々木)で確認済み。bank_upload/index.phpの表示フラグ(disp_flg)が常にfalseのため、取込結果の表(商品名・会員名)はB-0048のいずれのケースでも一度も表示されない(ソース・実機とも確認)');
set('G-12', 'OK', '手動テスト(担当:佐々木)で確認済み。settlement/の完了画面15本に目印付き攻撃文字列をGETパラメータで付与したが、いずれも出力されない(完了画面はURLの値を使わずセッション・DBから表示する作り)');
set('C-0416,C-0417', 'OK', '手動テスト(担当:佐々木)で確認済み。alert_passport.phpのhidden項目・フラグ改ざんはいずれも無反映または403で終了、内部パス等の漏えい無し');
set('C-0418,C-0419', 'OK', '手動テスト(担当:佐々木)で確認済み。settlement/index.phpのhidden項目(csrf_token・pid・temp_date・temp_no等)改ざんは拒否・無反映。商品名表示はエスケープ済み。0円会場研修で完了処理まで実施しエラー・漏えい無しを確認(2026-10-09)。temp_date・temp_noのSQL未エスケープは[B-17]として修正・stg2反映済みを確認');
set('C-0420,C-0421,C-0422,C-0423,C-0424,C-0425,C-0426,C-0427,C-0428', 'OK', '2026-10-12実機確認(Chrome操作): 以前は有料商品が登録できず到達不可だったが、product_live商品(pid=26333・1000円)を新規登録しカートに投入、正規の手順で購入手続きを実施し到達・確認済み。claim_flgに`1\' OR \'1\'=\'1\'`を直接POSTしたが完全数値チェックで0に丸められ、エラー・漏洩なし([B-16]修正の有効性を確認)。card_no・expire_m・expire_yへのXSS攻撃文字列もいずれも反映・実行されず安全');
set('B-0051,B-0056,B-0067,B-0069', 'OK', 'ソース確認でinquiry_name・inquiry_comment等の全出力に|escapeが一貫して適用されていることを確認済み(2026-10-12)');
set('B-0071', 'OK', 'mailmagazine/conf.phpの不具合修正(コミットced3ae1)時にソースを確認済み(mysqli_real_escape_string適用)');
set('B-0107', 'OK', '[B-3]クラッシュ修正のデプロイ後に実アクセスでHTTP 200・エラー無しを確認、テンプレートの全出力も|escape済みと確認(2026-10-12)');
set('B-0108,B-0109,B-0110', 'OK', '$admin_main_side_menuはPHP側で組み立てる固定のメニューラベル文字列でユーザー入力を含まないことを複数の呼び出し元で確認済み(2026-10-12)');
set('B-0155,B-0286,B-0400,B-0428,B-0452,B-0463,B-0465', '対応不要', '計画データ不備(実ファイルが存在せず、対象項目は他行(add.php・info.php本体のまとめ行)と重複。xlsx側での統合を推奨)');
set('B-0184,B-0185,B-0186,B-0187,B-0188,B-0189,B-0190,B-0191,B-0192,B-0193,B-0194,B-0195,B-0196,B-0197,B-0198,B-0199',
  '対応不要', '[B-14]product/add_review.phpはコード全体のどこからもリンクされていない到達経路の無いコントローラ(オーファンコード)');
set('B-0214', '対応不要', 'product/info_review.phpはファイルが存在せず「File not found.」のみ返却(依頼C/コードレビューで確認)');
set('B-0217,B-0220,B-0225,B-0230,B-0245,B-0301,B-0306,B-0421,B-0461',
  'OK', 'gidパラメータ必須のポップアップ検索画面。2026-10-12にrun-xss-search.jsへ実在gid値を付与する対応を実装し、自由入力欄(search_contents_name等)のエスケープをS8テストで確認済み(search_elive.php以外はソースレビューでも|escape適用を確認)');
set('B-0364,B-0367', '対応不要', 'product_lecture_ethicsは`aid`概念が無くinfo.php自身からCSV取り込みフォームを送信する設計のため、info_user.php系への「管理」リンク自体が存在しない(依頼Dで実データ確認済み)');
set('B-0424,B-0425,B-0426', '対応不要', 'product_live_branch/add.phpはファイル自体が存在しない(計画データ不備。add_review.php系と同種)');
set('B-0440,B-0442', '対応不要', '「単位会(日弁連以外の弁護士会)」専用機能で、日弁連本体アカウントでは明示的にリダイレクトされる仕様(コードレビューで確認。2026-10-12)');
set('B-0463,B-0465', '対応不要', '計画データ不備(実ファイルが存在せず、対象項目はreport_product/info.phpのまとめ行と重複)');

// 受講者サイト
// exam2の架空の番号付きファイル(実体が無い。計画データ不備)
const EXAM2_PHANTOM = [];
for (const r of plan) {
  if (r.site === '受講者サイト' && /^\/exam2\/(index1|index2|confirm1|confirm2|answer_check1|resubmit_index1|resubmit_index2|resubmit_exec_result1|result1|result2)\.php$/.test(r.url)) {
    EXAM2_PHANTOM.push(r.id);
  }
}
set(EXAM2_PHANTOM.join(','), '対応不要', '計画データ不備。exam2はindex.php→answer_check.phpの1系統のみで、exam(非2)の番号付きファイル構成を誤って複製したものとみられ、該当ファイルの実体が無い');
set('C-0050', '対応不要', '/exam/resubmit_exec_result1.phpは実体が無い(計画データ不備)');
set('C-0054', '対応不要', '/exam/resubmit_index1.phpは実体が無い可能性が高い(依頼Aで実機調査済み。アプリ側の実際の呼び出し先は/exam/resubmit_exec.php)');
set('C-0020,C-0021,C-0026,C-0157,C-0158,C-0159', 'OK', 'convert_symbole()による全角置換とcmCheckInput(CK_NUM)の数値検証の組み合わせで安全(2026-10-12コードレビュー)。answer_save.php系は該当コードが丸ごとコメントアウト済みで常に空');
set('C-0038,C-0156', 'OK', '依頼Aで実データにより実際に画面を開き、確認用出力なしを確認済み');
set('C-0062', 'OK', '依頼Aで実データ(pid=26312&ccno=2&eid=30)により実際に画面を開き、確認用出力なしを確認済み');
set('C-0042,C-0046,C-0058,C-0066,C-0070,C-0162,C-0030,C-0034', '未実施', '2026-10-12確認: test-plan/student-entry.jsonの全項目(pid/eid/e2id等)が未記入のため、run-xss-student-exam.jsがテスト対象を特定できず実行できない。テスト専用の講座・試験・アンケートをCMSで作成し、実際のIDをstudent-entry.jsonに記入すれば自動実行可能(前提作業が必要なだけで、テストコード自体は実装済み)');
set('C-0164,C-0172', 'OK', '副産物の発見(inquiry/form.phpのURL誤り修正)の調査過程で、inquiry/index.phpの関連項目は対象外・または自動テスト実行済みと確認済み(C-0165〜172と同一調査)');
set('C-0173,C-0177,C-0178', '対応不要', 'login/error.php・login.php・user_err.phpに対応するテンプレートは存在するが、これらを描画するPHPファイル自体が存在せず、リポジトリ全体を検索しても参照箇所が無いオーファンテンプレート(2026-10-12コードレビューで確認)');
let styleIds = [];
for (const r of plan) {
  if ((r.url === '/member/regist.php' || r.url === '/mypage/edit.php') && /_style$/.test(r.item || '')) styleIds.push(r.id);
}
set(styleIds.join(','), 'OK', 'コントローラが生成する固定CSSスタイル文字列への{assign}でユーザー入力を含まない(2026-10-08確認済みの既知誤検知パターン)');
set('C-0225', '対応不要', '計画データ不備(SSOログインフロー専用で実体の挙動確認は対象外。2026-10-08確認済み)');
set('C-0229,C-0232,C-0279,C-0280,C-0282,C-0283,C-0288,C-0290,C-0292,C-0296,C-0297,C-0299,C-0304,C-0310,C-0377',
  'OK', '依頼Aで実際に画面を開き、確認用出力なしを確認済み');
set('C-0276', '対応不要', 'mypage/edit_confirm.phpはファイル自体が存在しない(2026-10-12コードレビューで確認。依頼Cでも到達できずと報告済み)');
set('C-0285,C-0286,C-0350,C-0351,C-0354,C-0355,C-0358,C-0359,C-0362,C-0363,C-0366,C-0367,C-0370,C-0371,C-0374,C-0375,C-0380,C-0381,C-0383,C-0384',
  'OK', 'disp_sponsorは生成元のPHP側(product/detail.php等)でhtmlspecialchars()により事前エスケープ済みと確認済み(2026-10-08)');
set('C-0302,C-0303,C-0308,C-0309', '対応不要', 'csrf_token_verify()によりPOST無しのGETアクセスは即403・空ボディで終了し、表示自体が発生しないため反射経路が無い(2026-10-12コードレビューで確認)');
set('C-0306', 'OK', '2026-10-12コードレビューで原因確認: player_ethic.php系と同じcsrf_token_verify()によるCSRFガード(POST無しのGETアクセスは即403・空ボディ)。情報漏えいなし');
set('C-0312,C-0313,C-0314,C-0315,C-0316,C-0317,C-0318,C-0319,C-0320,C-0321,C-0322,C-0323,C-0324,C-0325,C-0326,C-0327,C-0328,C-0329,C-0330,C-0331,C-0332,C-0333,C-0334,C-0335,C-0336,C-0337,C-0338,C-0339,C-0340,C-0341,C-0344',
  'OK', 'product/detail.phpの全17項目を個別にソース確認(memo/contents/free_html_area1-3はpurify_html()で事前サニタイズ、disp_sponsorはhtmlspecialchars済み、exam2_problem_row系はSmartyコメントで無効化されたデッドコード、残りは数値ID・固定文字列・二重エスケープ済みのいずれか。2026-10-12)');
set('C-0345,C-0346,C-0347', 'OK', 'contents_download_key等はSmartyのsectionループ連番から生成する内部キー名でユーザー入力を含まない。実際の出力行は|escape適用済み(2026-10-12コードレビュー)');
set('C-0386,C-0389', '対応不要', 'reminder/*はSSOログインフロー専用で計画データ不備として対象外(2026-10-08確認済み)');
set('C-0400,C-0401,C-0402,C-0403,C-0405,C-0406', 'OK', 'url_plamの全構成要素がurlencode()済みであることを確認済み(2026-10-12コードレビュー)');
set('E-02', '未実施', '決済・注文確定を伴う画面。方針により自動化対象外、人による確認が必要');
set('E-05,E-53', 'OK', '手動テスト(担当:佐々木)で確認済み。payment_*.phpの「order err」出力はソース上コメントアウト済み。end_*.php10本はいずれも確認用出力なし(E-53の「order err.1」問題は[B-19]として修正・stg2反映済みを確認。2026-10-09)');
set('E-17,E-18,E-29,E-32', 'OK', 'disp_sponsor・商品名・カテゴリ名はいずれも生成元PHP側で事前エスケープ済みと確認済み(2026-10-08の静的解析、2026-10-12の検証と整合)');
set('E-31', '未実施', 'タグ許可ポリシー全体の横断確認。個別画面(exam/exam2のpurify_ethic_html適用等)は確認済みだが、総括としての確認はまだ');
set('E-33', 'OK', '試験のフリー回答表示は、Cms_exam(2).php側でhtmlspecialchars()適用後に改行を<br/>化していることをソース確認済み(2026-10-12)');
set('E-34,E-37,E-40', '未実施', '横断的な観点(全入力画面の入力値往復・PC/SP差分)の総括確認はまだ。個別画面の確認は既存のS1〜S12テストで広くカバー済み');
set('E-59', '対応不要', '応答が固定文字列"0"のみで反射経路自体が無くXSS観点では対象外と判断済み(調査の過程で別の認可不備[B-12]を発見)');
set('G-01,G-02,G-03,G-04,G-05,G-06,G-07,G-08,G-10', '対応不要', '入口カテゴリの参考記載であり、個別の実行対象ではない(計画上の分類見出し。該当する具体的な登録画面はS1〜S7・S12等の個別テストで確認済み)');
set('E-06,E-35,E-36,E-38,E-39,E-44,G-09', '未実施', 'ログ・本番DB比較・インフラ設定比較等、ブラウザでは確認できない運用上の確認事項。人による確認が必要');
set('E-43,F-10', '対応不要', '個別の入口・観点はCSV取込(cms_exam_problem_import等)・銀行入金(bank_upload)・S1〜S12の各テストで既に個別に確認済みであり、本行自体は「総数で見たときの一覧」という参考記載のため個別の実行対象ではない');
set('E-01', 'OK', '商品管理の該当4画面(product・product_ethics・product_live・product_live_branchの各index.php)に実アクセスし、確認用出力(SQL・var_dump・PHPエラー・サーバーパス)が無いことを確認済み(2026-10-12)');
// 2026-10-12 追加レビュー(「自動・手動共に未実施」44件の棚卸し)
set('E-41,E-42', 'OK', '静的解析候補255件を全件ソース確認済み(2026-10-12)。genuineなXSS脆弱性は0件(詳細はCLAUDE.md「静的解析候補255件の人手レビュー完了」参照)');
set('B-0240', 'OK', 'search_product_ranking.phpは依頼D・カテゴリ名誤報の検証で複数回OKを確認済み(gid不要で直接動作する唯一のsearch_*.php)');
set('C-0003,C-0004,C-0007,C-0009,C-0010,C-0012,C-0013,C-0015,C-0017,C-0018', 'OK', 'ethic_treaning配下の全テンプレートで、設問本文・参考資料が|purify_ethic_html、結果画面の商品名等が|escapeで出力されていることをソース確認済み(2026-10-12)');
set('C-0001', 'OK', '共通テンプレート(alfstream_frame.tpl)に未エスケープの変数出力なしをソース確認済み(2026-10-12)');
set('C-0025', 'OK', 'exam/answer_check1.phpの設問本文・解答はexam(非2)の他画面と同じ|purify_ethic_html|purify_ethic_inline_htmlで出力されていることをソース確認済み(2026-10-12)');
set('C-0071,C-0072,C-0073', 'OK', 'exam2/answer_check.php・answer_save.phpのreturn.html/formhtmlは、exam(非2)と同じconvert_symbole()+cmCheckInput(CK_NUM)の組み合わせで安全であることをソース確認済み(2026-10-12)');
set('C-0215,C-0217,C-0223', 'OK', 'member/regist.phpのpassword・password_confフィールドは|escapeで出力されていることをソース確認済み(2026-10-12)');
set('C-0274', 'OK', 'mypage/edit.phpに_styleクラスタ以外の未エスケープ出力なしをソース確認済み(2026-10-12)');
set('C-0342,C-0343', 'OK', '「detail_review」はproduct/detail.phpの別名行(計画データ不備。既出)。free_html_area3は他の16項目と同様purify_html()で事前サニタイズ済み(2026-10-12確認済み)');
set('C-0413,C-0414,C-0415', 'OK', '「index20260323」はsearch/index.phpの別名行(計画データ不備。既出)。url_plam・disp_sponsorとも同画面の他行で確認済みの安全パターンと同一(2026-10-12確認済み)');
set('D-0010,D-0011,D-0012', 'OK', 'WordPressテーマ(twentyten)のサイドバー・商品一覧・headerで、カテゴリ名・商品名が esc_html()/htmlspecialchars() で出力されていることをソース確認済み(2026-10-12)。og:タグは当該テーマに存在せず対象外');

// WordPress
set('D-0001,D-0002,D-0003,D-0004,D-0006,D-0007,D-0008,D-0009,D-0013', 'OK', '依頼Aおよび2026-10-12のタグ表示検証で、ページソースを直接確認し、HTML実体参照として正しくエスケープ済みと確認済み');
set('D-0015', '未実施', 'モバイルUA表示の確認。依頼Eで検証した結果、本ツール(実ブラウザ遠隔操作)にはUser-Agent偽装・ビューポート変更の手段が無く実施不可能と判明。開発者ツールまたは実機での確認が必要');
set('E-25,E-30', '未実施', 'WordPress側の横断的な確認(リンク先URL・タグ許可ポリシー)。個別のお知らせ記事・カテゴリ名は確認済みだが総括確認はまだ');
set('E-11,E-21,E-22,E-23,E-24,E-26,E-27', 'OK', 'S12テスト(講座名・講師名・タグ名・授業名・教材名・お知らせタイトル/本文/外部リンク・カテゴリ名等)で登録した実データが、検索プルダウン・通知バナー・カテゴリ表示・JS内埋め込み(search/index.phpのarr_cat_name等)・コンテンツ検索ポップアップのいずれでもエスケープ済みであることを確認済み(2026-10-09のS12最終結果と整合)');
set('E-28', 'OK', '2026-10-12のタグ表示検証でお知らせ本文のページソードを直接確認し、エスケープ済みと確認済み');

const used = new Set(Object.keys(MANUAL));

// ---------- 3) 全行を走査して最終結果を決定 ----------
const RESULT_LABEL = { OK: 'OK', NG: 'NG', '対象外': '対応不要' };
const out = [];
let stat = { OK: 0, NG: 0, '対応不要': 0, '未実施': 0 };
for (const r of plan) {
  let result, reason = '';
  if (MANUAL[r.id]) {
    result = MANUAL[r.id].result;
    reason = MANUAL[r.id].reason || '';
  } else {
    const av = autoVerdict(r.id);
    if (av) {
      result = RESULT_LABEL[av.worst];
      if (result !== 'OK') reason = av.note || '';
    } else {
      result = '未実施';
      reason = '自動テスト・手動確認のいずれも未実施';
    }
  }
  stat[result]++;
  out.push({ id: r.id, area: r.area, func: r.func, page: r.page, item: r.item, url: r.url, result, reason });
}

// ---------- 4) 出力 ----------
const rows = [['No.', '区分', '機能', '画面', '項目', 'URL', '結果', '対応不要・未実施の理由']];
for (const o of out) rows.push([o.id, o.area, o.func, o.page, o.item, o.url, o.result, o.reason]);
const csv = rows.map((r) => r.map((c) => `"${String(c ?? '').replace(/"/g, '""')}"`).join(',')).join('\r\n');
const outPath = path.join(__dirname, '..', '..', 'secure_report', '脆弱性テスト計画_最終結果一覧.csv');
fs.writeFileSync(outPath, '﻿' + csv);

console.log('総行数:', out.length);
console.log(stat);
console.log('未使用の手動指定ID(plan.jsonに存在しない可能性):', [...used].filter((id) => !plan.some((r) => r.id === id)));
console.log('出力:', outPath);
