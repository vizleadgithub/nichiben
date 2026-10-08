# XSS対応 脆弱性テスト自動化 — オンボーディング

対象: `tools/stg2-e2e/` にある、XSS脆弱性テスト計画(`secure_report/XSS対応_脆弱性テスト計画*.xlsx`、全1,489行)を
Playwrightで自動実行するプロジェクトに、これから手を動かす人向けのガイドです。

**まず読むもの**: `CLAUDE.md`(リポジトリ直下)の「stg2 環境へのブラウザ自動テスト」以降。使い方・前提・方針はすべてそこに書かれています。
このファイルは、CLAUDE.mdを読む前の地図・最初のとっかかりとして使ってください。

**共有リンク(Claude Codeで開くとこの内容を読み込んだ状態で会話を始められます)**: https://claude.ai/claude-code/onboard/SsrFRQvkYH8_
このファイルを更新したら、リンク先も更新すること(`ShareOnboardingGuide` ツールで再アップロードできる)。

**VSCode拡張機能での開き方**:
- 確実な方法: このファイル(`ONBOARDING.md`)の中身をコピーして、VSCode拡張機能のClaude Codeパネル(Sparkアイコン、または `Cmd/Ctrl+Shift+X`)で新しい会話を開始し、最初のメッセージとして貼り付ける。
- リンクをそのまま開く方法(`https://claude.ai/claude-code/onboard/...`)がVSCode拡張機能上でどこまで自動的に機能するか(クリックだけでVSCode側に連携されるか等)は、現時点で確認が取れていません。リンクを直接共有する場合は、相手の環境で一度試してもらうのが確実です。

---

## 1. 何のためのプロジェクトか

- テスト計画は1,489行あり、手作業での全件実施は非現実的。
- 方針: **自動テストのスクリプトで網羅的に実行し、検出された候補を人が再現確認して最終判定する**。
- 現状(2026-10-08時点): 自動化済み1,173行 / 未自動化137行 / 手動95行 / 計画データ不備84行。
  正確な内訳は `tools/stg2-e2e/test-plan/plan-fixes.json` の各行の `note` と、CLAUDE.mdの「まだ自動化していないもの」節を参照。

## 2. 絶対に守ること(安全規則)

1. **対象は stg2 環境のみ**。本番・旧stgには絶対にアクセスしない(コード側で `assertStg2()` によるチェックが入っている)。
2. **受講者SSO(本番と共用の外部ドメイン)へは攻撃文字列を送らない**。ブラウザ側で通信を遮断する仕組みがあるので、それを外さない。
3. **メール送信・決済を伴う操作は自動化しない**(手動で実施する方針)。
4. **書き込みを伴うテスト(段階B)の前には、必ずDBダンプを取る**(人が実施。スクリプトは実行するだけ)。
5. **新しい画面・コントローラを対象にするときは、実際にURLへアクセスする前に、必ずソースを読んで副作用(メール送信・ファイル書き込み・DB更新)が無いか確認する。**
   このルールは今回のセッションで痛感した教訓です。例:
   - CMSのバッチコントローラの1つ(`Once_bat_reconversion_book_library.php`)は `index()` メソッドが実処理を直接呼ぶ作りで、素のGETだけで実行されてしまう。
   - CSV出力の一部ファイルは、呼ばれるたびに認証なしでサーバー上にファイルを書き込む副作用があった。
   いずれも「まず動かしてみる」のではなく、**ソースを読んでから安全な方法(静的解析、または非実在IDでの確認)を選ぶ**ことで事故を避けられた。迷ったら実アクセスせず、静的なソース確認だけに留めるのが安全側。

## 3. コードの構成

```
tools/stg2-e2e/
├── xss-session.js      # Session class(ログイン状態の保持・自動再ログイン・ダイアログ/JSエラー収集)
├── xss-form-lib.js      # フォーム操作の共通部品(setFields/fillBaseline/submitGuarded/submitComplete)
├── detect.js            # 検出ロジック共通部品(Recorder・classify・domInjection・debugFindings)
├── xss-payloads.js       # 攻撃文字列セット(P01〜P19)とテストセット(S1〜S12)の対応表
├── lib.js               # 環境変数読み込み・ブラウザコンテキスト生成
├── login.js             # CMS・受講者(SSO)・代替倫理研修アカウントのログイン
├── run-xss-search.js     # 段階A: S8 検索条件
├── run-xss-form.js       # 段階A: 登録系(S1〜S7・S9〜S11。入力→確認画面まで。DB書き込みなし)
├── run-xss-http.js       # 段階A: 入口以外・HTTP(F-01〜F-09)と重点項目の一部(GETのみ)
├── run-xss-student-exam.js  # 段階B: 受講者サイトの試験・アンケート
├── run-xss-s12.js        # 段階B: S12(表示専用の値)・F-10(入力→全画面の自動追跡)
├── run-xss-data-linked.js   # 段階B: 実データの紐付けが必要だった重点項目(E-10・E-12・E-13・E-14・E-60)
├── report-xss.js         # 結果をテスト計画のID単位に集計
└── test-plan/
    ├── export-plan.py     # xlsx → plan.json(xlsxは直接編集しない)
    ├── plan-fixes.json    # レビュー起因の修正をplan.jsonに後付けするパッチ(Git管理する)
    ├── form-defaults.json # 画面ごとに必要な既定値(必須項目を通すため)
    ├── student-entry.json / data-entries.json  # 実データ(pid・eid等)の設定
    └── plan.json          # 生成物。コミットしない
```

- **段階A**: GETと確認画面までの送信だけ。DBへ書き込まない。DBダンプ不要。
- **段階B**: 実際に登録・更新する。DBダンプが前提。

## 4. 新しいチェックを追加する実例

難易度別に、既存コードから近い実装を探すのが早いです。

- **一番シンプル**: `run-xss-http.js` の `checkE50`(既存の `tamper()` ヘルパーを1行呼ぶだけ)。
- **静的解析のみ(実URLへアクセスしない安全重視パターン)**: `run-xss-http.js` の `checkE51`・`checkE57`。
  権限次第で実害が出る・副作用が読めない画面は、ソースを読んで判定するだけに留める。
- **複数ステップにまたがる段階Bの例**: `run-xss-data-linked.js` の `checkE60`
  (テスト用データの登録 → 状態の変更 → 結果の確認、という一連の流れ)。
- 結果は `detect.js` の `Recorder` を使って `rec.add({ id, verdict, ... })` で記録する。
  `verdict` は `OK` / `NG` / `要確認` / `対象外` の4種類。

## 5. 計画行とコードの対応を調べる

```
cd tools/stg2-e2e
python test-plan/export-plan.py        # plan.json を生成(openpyxl 必要)
node -e "console.log(require('./test-plan/plan.json').rows.find(r=>r.id==='E-XX'))"
```

計画のIDから該当行を引いて、`note`(経緯・調査結果)・`value`(何を送るか)・`expected`(期待結果)を確認する。
レビュー由来の追加行(E-48〜E-60)は `test-plan/plan-fixes.json` に手で書かれているので、そちらも参照。

## 6. 困ったときは

- **実行結果の集計**: `node report-xss.js` → `test-results/xss-report-*.csv`
- **現状の自動化・未自動化・手動の内訳**: CLAUDE.mdの該当セクション、または `plan.json` を自分で集計する。
- **セキュリティ上の発見事項(不具合まとめ)**: `secure_report/stg2自動テストで発見した不具合_2026-10-07.md`(Git管理)。
  テスト自動化の過程で見つかった、XSS以外も含む不具合をまとめています。新しい画面を自動化する前に目を通しておくと、
  同じ系統の問題(認証チェック漏れ・副作用のある処理など)を避けやすくなります。
- それ以外の `secure_report/` 配下の資料(進捗トラッカー等)は `.gitignore` 対象です。見たい場合はチームに確認してください。
