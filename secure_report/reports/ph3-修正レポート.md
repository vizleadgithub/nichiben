# Ph.3 修正レポート — `target="_blank"` に `rel="noopener noreferrer"` 追加

**実施日:** 2026-06-24  
**対応区分:** STU-M-06〜11（`target="_blank"` の `rel` 属性欠落）

---

## サマリー

| 項目 | 値 |
|------|-----|
| 修正ファイル数 | 20 |
| 修正行数（行の変更） | 22 行 |
| 修正タグ数（anchor タグ実数） | 27 箇所 |
| スキップ | 0 |

> **注:** `payment_card.tpl`（PC/SP）・`payment_bank.tpl` の各 1 行にはそれぞれ 2 つの `target="_blank"` が含まれており、
> 22 行変更で 27 タグを修正。

---

## Smarty テンプレート（15 ファイル）

### 1. `alfproduct/smarty/templates/default/settlement/payment_card.tpl`

**行 161 — 2タグ修正**

```diff
-  <span style="color:#ff6666;"><a href="http://www.nichibenren.or.jp/copyright/privacy.html" target="_blank">プライパシーポリシー</a>及び<a href="/policy" target="_blank">利用規約</a>に同意の上...
+  <span style="color:#ff6666;"><a href="http://www.nichibenren.or.jp/copyright/privacy.html" target="_blank" rel="noopener noreferrer">プライパシーポリシー</a>及び<a href="/policy" target="_blank" rel="noopener noreferrer">利用規約</a>に同意の上...
```

---

### 2. `alfproduct/smarty/templates/smartphone/settlement/payment_card.tpl`

**行 161 — 2タグ修正**（PC版と同一パターン）

```diff
-  ...target="_blank">プライパシーポリシー</a>及び<a href="/policy" target="_blank">利用規約</a>...
+  ...target="_blank" rel="noopener noreferrer">プライパシーポリシー</a>及び<a href="/policy" target="_blank" rel="noopener noreferrer">利用規約</a>...
```

---

### 3. `alfproduct/smarty/templates/smartphone/settlement/payment_bank.tpl`

**行 123 — 2タグ修正**（payment_card と同一パターン）

```diff
-  ...target="_blank">プライパシーポリシー</a>及び<a href="/policy" target="_blank">利用規約</a>...
+  ...target="_blank" rel="noopener noreferrer">プライパシーポリシー</a>及び<a href="/policy" target="_blank" rel="noopener noreferrer">利用規約</a>...
```

---

### 4. `alfproduct/smarty/templates/smartphone/ranking/index.tpl`

**行 66 — 1タグ修正**

```diff
-  <a href="/custom_pages/cp-content/uploads/<!--{$row.file_path|escape}-->" target="_blank">こちら</a>
+  <a href="/custom_pages/cp-content/uploads/<!--{$row.file_path|escape}-->" target="_blank" rel="noopener noreferrer">こちら</a>
```

---

### 5. `alfproduct/smarty/templates/smartphone/mypage/edit.tpl`

**行 65, 70 — 各1タグ修正（計2タグ）**

```diff
@@ 行 65 @@
-  <td><a target="_blank" href="http://search.post.japanpost.jp/zipcode/"><span>郵便番号検索</span></a></td>
+  <td><a target="_blank" rel="noopener noreferrer" href="http://search.post.japanpost.jp/zipcode/"><span>郵便番号検索</span></a></td>

@@ 行 70 @@
-  <a target="_blank" onclick="fnCallAddress(...)" href="javascript:void(0);"><img ... alt="住所自動入力"></a>
+  <a target="_blank" rel="noopener noreferrer" onclick="fnCallAddress(...)" href="javascript:void(0);"><img ... alt="住所自動入力"></a>
```

---

### 6. `alfproduct/smarty/templates/smartphone/member/regist.tpl`

**行 56, 61 — 各1タグ修正（計2タグ）**

```diff
@@ 行 56 @@
-  <a target="_blank" href="http://search.post.japanpost.jp/zipcode/"><span>郵便番号検索</span></a>
+  <a target="_blank" rel="noopener noreferrer" href="http://search.post.japanpost.jp/zipcode/"><span>郵便番号検索</span></a>

@@ 行 61 @@
-  <a target="_blank" onclick="fnCallAddress(...)" href="javascript:void(0);"><img ... alt="住所自動入力"></a>
+  <a target="_blank" rel="noopener noreferrer" onclick="fnCallAddress(...)" href="javascript:void(0);"><img ... alt="住所自動入力"></a>
```

---

### 7. `alfproduct/smarty/templates/admin/main_frame.tpl`

**行 225, 226 — 各1タグ修正（計2タグ）**

```diff
@@ 行 225 @@
-  <li><a target="_blank" href="http://alfredcore.com/">運営会社</a></li>
+  <li><a target="_blank" rel="noopener noreferrer" href="http://alfredcore.com/">運営会社</a></li>

@@ 行 226 @@
-  <li><a target="_blank" href="http://alfredcore.com/privacy">個人情報保護方針</a></li>
+  <li><a target="_blank" rel="noopener noreferrer" href="http://alfredcore.com/privacy">個人情報保護方針</a></li>
```

---

### 8〜15. 管理側 CSV ダウンロード・CSV リンク系（1タグ/ファイル）

同一パターン（CSV ダウンロード img リンク）：

```diff
-  <a href="csv.php?data=<!--{...}-->" target="_blank"><img src="...abtn_csv.png" alt="CSVダウンロード"></a>
+  <a href="csv.php?data=<!--{...}-->" target="_blank" rel="noopener noreferrer"><img src="...abtn_csv.png" alt="CSVダウンロード"></a>
```

| # | ファイル | 修正行 |
|---|---------|------|
| 8 | `admin/report_product/index.tpl` | 209 |
| 9 | `admin/report_product/info.tpl` | 88 |
| 10 | `admin/product_live/index.tpl` | 217 |
| 11 | `admin/amount_passport/index.tpl` | 69 |
| 12 | `admin/amount_product/index.tpl` | 68 |
| 13 | `admin/amount_user/index.tpl` | 56 |
| 14 | `admin/amount_user/info.tpl` | 37 |
| 15 | `admin/amount_order/index.tpl` | 91 |

---

## CMS ビュー PHP（5 ファイル）

### 16. `alflearning-cms/application/views/header/body_header.php`

**行 162, 164 — 各1タグ修正（計2タグ）** PHP 文字列連結内

```diff
@@ 行 162 @@
-  $temp_li = $temp_li.'><a href="'.$user_auth['url'].'" target="_blank"><img src="...logo_eLearningManager.png" />'.$user_auth['name'].'</a></li>';
+  $temp_li = $temp_li.'><a href="'.$user_auth['url'].'" target="_blank" rel="noopener noreferrer"><img src="...logo_eLearningManager.png" />'.$user_auth['name'].'</a></li>';

@@ 行 164 @@
-  $temp_li = $temp_li.'><a href="'.$user_auth['url'].'" target="_blank">'.$user_auth['name'].'</a></li>';
+  $temp_li = $temp_li.'><a href="'.$user_auth['url'].'" target="_blank" rel="noopener noreferrer">'.$user_auth['name'].'</a></li>';
```

---

### 17. `alflearning-cms/application/views/admin_top/update_history.php`

**行 204 — 1タグ修正**

```diff
-  <a href="http://elearningmanager.jp/" target="_blank">eLearning Manager</a>との連携に対応しました...
+  <a href="http://elearningmanager.jp/" target="_blank" rel="noopener noreferrer">eLearning Manager</a>との連携に対応しました...
```

---

### 18. `alflearning-cms/application/views/cms_exam/confirm.php`

**行 226 — 1タグ修正** JavaScript 文字列内

```diff
-  problem_kind_detail = '...'+'<a style="text-decoration: none;" href="'+problem_kind_link+'" target="_blank">'+response['exam_problems_problem_contents']+'</a>';
+  problem_kind_detail = '...'+'<a style="text-decoration: none;" href="'+problem_kind_link+'" target="_blank" rel="noopener noreferrer">'+response['exam_problems_problem_contents']+'</a>';
```

---

### 19. `alflearning-cms/application/views/cms_exam2/confirm.php`

**行 226 — 1タグ修正**（cms_exam と同一パターン、変数名のみ `exam2_` プレフィックス）

---

### 20. `alflearning-cms/application/views/cms_issue/confirm.php`

**行 166 — 1タグ修正**

```diff
-  <a href="<?= $this->config->item('stream_get_url'); ?>/school_...?token=..." target="_blank"><?= $issue['issue_submit_logic_name'][$id]; ?></a>
+  <a href="<?= $this->config->item('stream_get_url'); ?>/school_...?token=..." target="_blank" rel="noopener noreferrer"><?= $issue['issue_submit_logic_name'][$id]; ?></a>
```

---

## 修正合計

| 区分 | ファイル数 | 修正行数 | 修正タグ数 |
|------|----------|---------|---------|
| Smarty テンプレート | 15 | 17 行 | 21 タグ |
| CMS ビュー PHP | 5 | 5 行 | 6 タグ |
| **合計** | **20** | **22 行** | **27 タグ** |

---

## スコープ外

`alfproduct/admin/testlogin/` 配下 2 ファイル（`body_header.php` 行 138, 140 / `body_footer.php` 行 19, 20）は
`alfproduct/admin/` が DocumentRoot 外のため Web 非到達 → 除外済み（Ph.2 同様の admin 除外方針）。

---

## 次フェーズ

| フェーズ | 内容 | 対象件数 |
|---------|------|---------|
| **Ph.4** | Smarty `\|escape` 追加（#A） | 77 ファイル |
| **Ph.5** | CI3 ビュー `htmlspecialchars()` 追加（#D） | 84 ファイル |
| **Ph.6** | `AlfSession.php` SameSite=Strict 変更 | 1 ファイル |
