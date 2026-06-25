# Ph.5 修正レポート — CI3 ビュー `htmlspecialchars()` 追加（#D）

**実施日:** 2026-06-25  
**対応区分:** 管理画面 XSS（優先度：低）— CI3 ビュー変数の出力エスケープ漏れ

---

## サマリー

| 項目 | 値 |
|------|-----|
| 対象ファイル総数 | 101 |
| 修正（`htmlspecialchars()` 追加） | 97 ファイル |
| 対応済み（既に `htmlspecialchars()` 有） | 4 ファイル |
| スキップ（対象変数なし） | 0 ファイル |
| **追加修正箇所合計** | **580 箇所** |

> **スキップ方針:**  
> `$pagination` 変数（CI3 Pagination ライブラリが生成するHTML断片）、  
> `$this->...` で始まる CI3 オブジェクトメソッド、  
> `base_url()` / `site_url()` 等の CI3 ヘルパー関数は対象外。

---

## 修正パターン

```diff
- <?=$var?>
+ <?= htmlspecialchars( $var, ENT_QUOTES, 'UTF-8') ?>

- <?= nl2br($var) ?>
+ <?= nl2br( htmlspecialchars( $var, ENT_QUOTES, 'UTF-8') ) ?>
```

---

## ファイル別修正一覧

### ルートレベル（エラー画面）

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `book_library_error.php` | 修正済み | 2 |
| `course_class_error.php` | 修正済み | 2 |
| `issue_error.php` | 修正済み | 2 |
| `material_error.php` | 修正済み | 2 |
| `teacher_error.php` | 修正済み | 2 |

### header/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `header/header.php` | 修正済み | 1 |

### admin_top/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `admin_top/classes.php` | 修正済み | 4 |
| `admin_top/index.php` | 修正済み | 9 |
| `admin_top/info_detail.php` | 修正済み | 7 |
| `admin_top/menu_upload.php` | 修正済み | 7 |
| `admin_top/photo_upload.php` | 修正済み | 1 |
| `admin_top/_submenu.php` | 修正済み | 2 |

### cms_auth/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_auth/edit.php` | 修正済み | 2 |
| `cms_auth/index.php` | 対応済み | — |

### cms_book_library/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_book_library/commit.php` | 修正済み | 1 |
| `cms_book_library/edit.php` | 修正済み | 6 |
| `cms_book_library/index.php` | 対応済み | — |

### login/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `login/login_page.php` | 修正済み | 3 |

### cms_class/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_class/confirm.php` | 修正済み | 2 |
| `cms_class/edit.php` | 修正済み | 3 |
| `cms_class/index.php` | 対応済み | — |

### cms_class_material/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_class_material/add_material.php` | 修正済み | 5 |
| `cms_class_material/confirm.php` | 修正済み | 11 |
| `cms_class_material/index.php` | 修正済み | 16 |

### cms_cource/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_cource/commit.php` | 修正済み | 1 |
| `cms_cource/confirm.php` | 修正済み | 9 |
| `cms_cource/edit.php` | 修正済み | 18 |
| `cms_cource/index.php` | 修正済み | 4 |

### cms_exam/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam/confirm.php` | 修正済み | 13 |
| `cms_exam/confirm_answer.php` | 修正済み | 20 |
| `cms_exam/edit.php` | 修正済み | 14 |
| `cms_exam/index.php` | 修正済み | 3 |

### cms_exam2/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam2/answer_set_list.php` | 修正済み | 11 |
| `cms_exam2/answer_set_list_review.php` | 修正済み | 10 |
| `cms_exam2/confirm.php` | 修正済み | 23 |
| `cms_exam2/confirm_answer.php` | 修正済み | 21 |
| `cms_exam2/edit.php` | 修正済み | 13 |
| `cms_exam2/index.php` | 修正済み | 4 |

### cms_exam2_download/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam2_download/confirm.php` | 修正済み | 3 |
| `cms_exam2_download/edit.php` | 修正済み | 1 |

### cms_exam2_problem/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam2_problem/confirm.php` | 修正済み | 7 |
| `cms_exam2_problem/edit.php` | 修正済み | 3 |
| `cms_exam2_problem/index.php` | 修正済み | 2 |

### cms_exam2_problem_group/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam2_problem_group/confirm.php` | 修正済み | 3 |
| `cms_exam2_problem_group/edit.php` | 修正済み | 4 |
| `cms_exam2_problem_group/index.php` | 修正済み | 4 |

### cms_exam2_problem_import/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam2_problem_import/confirm.php` | 修正済み | 3 |
| `cms_exam2_problem_import/edit.php` | 修正済み | 16 |

### cms_exam_problem/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam_problem/confirm.php` | 修正済み | 14 |
| `cms_exam_problem/edit.php` | 修正済み | 6 |
| `cms_exam_problem/index.php` | 修正済み | 4 |

### cms_exam_problem_group/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam_problem_group/confirm.php` | 修正済み | 3 |
| `cms_exam_problem_group/edit.php` | 修正済み | 4 |
| `cms_exam_problem_group/index.php` | 修正済み | 3 |

### cms_exam_problem_import/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_exam_problem_import/confirm.php` | 修正済み | 3 |
| `cms_exam_problem_import/edit.php` | 修正済み | 18 |

### cms_information/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_information/confirm.php` | 修正済み | 3 |
| `cms_information/index.php` | 修正済み | 3 |

### cms_information_old/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_information_old/confirm.php` | 修正済み | 6 |
| `cms_information_old/edit.php` | 修正済み | 2 |
| `cms_information_old/index.php` | 修正済み | 4 |

### cms_issue/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_issue/confirm.php` | 修正済み | 18 |
| `cms_issue/edit.php` | 修正済み | 7 |
| `cms_issue/index.php` | 修正済み | 5 |

### cms_material/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_material/commit.php` | 修正済み | 1 |
| `cms_material/confirm.php` | 修正済み | 6 |
| `cms_material/edit.php` | 修正済み | 5 |
| `cms_material/index.php` | 修正済み | 4 |

### cms_report/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_report/book_library.php` | 修正済み | 11 |
| `cms_report/class.php` | 修正済み | 9 |
| `cms_report/user.php` | 修正済み | 9 |
| `cms_report/user_detail_all.php` | 修正済み | 13 |
| `cms_report/user_detail_elearning.php` | 修正済み | 15 |
| `cms_report/user_detail_ethic_training.php` | 修正済み | 12 |
| `cms_report/user_detail_live_training.php` | 修正済み | 13 |
| `cms_report/user_detail_nichibenren_except_host.php` | 修正済み | 12 |
| `cms_report/video.php` | 修正済み | 4 |

### cms_school_manage/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_school_manage/commit.php` | 修正済み | 4 |
| `cms_school_manage/confirm.php` | 修正済み | 18 |
| `cms_school_manage/edit.php` | 修正済み | 4 |
| `cms_school_manage/index.php` | 修正済み | 3 |

### cms_student/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_student/confirm.php` | 修正済み | 7 |
| `cms_student/edit.php` | 修正済み | 12 |
| `cms_student/index.php` | 修正済み | 4 |

### cms_student_group/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_student_group/confirm.php` | 修正済み | 3 |
| `cms_student_group/edit.php` | 修正済み | 1 |
| `cms_student_group/index.php` | 修正済み | 4 |

### cms_student_sub_auth/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_student_sub_auth/confirm.php` | 修正済み | 10 |
| `cms_student_sub_auth/edit.php` | 修正済み | 7 |
| `cms_student_sub_auth/index.php` | 修正済み | 8 |

### cms_teacher/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_teacher/edit.php` | 修正済み | 3 |
| `cms_teacher/index.php` | 対応済み | — |
| `cms_teacher/photo_upload.php` | 修正済み | 6 |

### cms_video/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `cms_video/confirm.php` | 修正済み | 7 |
| `cms_video/edit.php` | 修正済み | 2 |
| `cms_video/edit_moviecut.php` | 修正済み | 8 |
| `cms_video/index.php` | 修正済み | 9 |
| `cms_video/newdata1.php` | 修正済み | 3 |
| `cms_video/newdata2.php` | 修正済み | 1 |

### school_select/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `school_select/index.php` | 修正済み | 7 |

### mail_templates/

| ファイル | status | 修正箇所 |
|---------|--------|---------|
| `mail_templates/class_notification.php` | 修正済み | 5 |

---

## 修正合計

| 区分 | ファイル数 | 修正箇所数 |
|------|-----------|-----------|
| 修正（`htmlspecialchars()` 追加） | 97 | 580 |
| 対応済み（既にエスケープ適用済み） | 4 | 0 |
| スキップ（対象変数なし） | 0 | 0 |
| **合計** | **101** | **580** |

---

## スコープ外・特記事項

| 事項 | 理由 |
|------|------|
| `$pagination` 変数 | CI3 Pagination クラスが生成するHTML断片 |
| `$this->lang->line_or_def(...)` | CI3 言語ヘルパー |
| `base_url()`, `site_url()` 等 | CI3 URLヘルパー関数 |
| `intval()`, `number_format()` 等 | 数値処理済み（XSSリスクなし） |
| `nl2br($var)` | `nl2br( htmlspecialchars($var, ...) )` に変換済み |
| HTMLコメント内の変数 | 非表示のためリスクなし（一部は保守性のため適用） |
| `if(false){}` ブロック内変数 | デッドコードだが一貫性のため修正対象とした場合あり |

---

## 次フェーズ

| フェーズ | 内容 | 対象件数 |
|---------|------|---------|
| **Ph.6** | `AlfSession.php` SameSite=Strict 変更 | 1 ファイル |
