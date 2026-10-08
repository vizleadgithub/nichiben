"""
XSS対応_脆弱性テスト計画(xlsx)を、自動テストが読む plan.json に変換する。

使い方（リポジトリ直下から）:
    python tools/stg2-e2e/test-plan/export-plan.py [xlsxのパス]

- 出力: tools/stg2-e2e/test-plan/plan.json（xlsxから再生成できるためコミットしない）
- 1行 = 計画の1行（ID・テストセット・URL・項目名など）。攻撃文字列の正本は xlsx、実行用の定義は ../xss-payloads.js
- 必要: openpyxl (pip install openpyxl)

## plan-fixes.json（レビュー起因の修正パッチ。xlsx本体は直接編集しない）
2026-10-07 の「テストプランレビュー.pdf」で指摘された抜け・問題点のうち、xlsx側の改訂を待たずに
テストコード側で先に反映できるものを、plan.json 生成の後段で機械的に適用する。
- xlsx は直接編集しない（編集の手間・差し戻しの手間を避けるため）。正式反映は別途 xlsx 側で行う。
- ここでの変更は plan.json（生成物）にしか残らないため、xlsx を再編集しても消えない形にする必要がある
  → 変更内容は test-plan/plan-fixes.json（Git管理する）に構造化して置き、ここで自動適用する。
- 適用する内容:
  1. ★行(備考に「★」。計画のエスケープ関数が見当たらない出力候補。191行)に、field名からのヒューリスティックで
     期待結果の仮分類(reviewHint)を付与する(レビュー 4.3)。あくまで仮置きで、要レビュー。
  2. plan-fixes.json の "add"(新規行)・"modify"(既存行への追記)を適用する(レビュー 2.1・3.1・3.2 等)。
"""
import glob
import json
import os
import re
import sys

import openpyxl

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.abspath(os.path.join(HERE, '..', '..', '..'))
FIXES_PATH = os.path.join(HERE, 'plan-fixes.json')
SHEETS = ['管理_CMS', '管理_商品管理', 'フロント_受講者サイト', 'フロント_WordPress',
          '重点項目(全量一覧No別)', '入口以外・HTTP', '入口別の表示先']
ID_RE = re.compile(r'^[A-G]-\d+$')

# ★行の期待結果ヒューリスティック分類（レビュー 4.3）。field名に含まれる語から、どの判定方式を当てるべきかを仮決めする。
# タグを許可する項目(許可リスト外の要素・属性が0件かを見る)か、意図的にHTMLを出力する項目(値の部分だけエスケープ)かを
# 優先して判定し、どちらでもなければ通常どおり「文字として表示」を期待する(最優先で確認すべき候補である点は変えない)
TAG_ALLOWED_HINT = re.compile(r'free_html|caption|contents|memo|note|problem_note|information_caption|html_area', re.I)
INTENTIONAL_HTML_HINT = re.compile(r'\.html$|\.formhtml$|return\.html|return\.formhtml', re.I)


def classify_star_field(field_name):
    if INTENTIONAL_HTML_HINT.search(field_name):
        return '意図的なHTML出力の可能性(値の部分だけエスケープされているかを確認。タグ全体の有無では判定不可)'
    if TAG_ALLOWED_HINT.search(field_name):
        return '許可タグ方式の可能性(許可リスト外の要素・属性・URLスキームが0件かで判定。文字としての表示は期待しない)'
    return '文字として表示を期待(未対策候補。最優先で確認)'


# ★の後の書き方がシート間で異なる(2通り確認済み):
#   管理_CMS: "★ソース上エスケープ関数が見当たらない出力(優先して確認): field1, field2"
#   管理_商品管理・フロント_受講者サイト: "★テンプレート上、エスケープ等の修飾子(…)が見当たらない表示(…)。値は field"
STAR_NOTE_PATTERNS = [
    re.compile(r'★[^:：\n]*[:：]\s*([^\n]+)'),
    re.compile(r'★.*?。\s*値は\s*([^\n]+)'),
]


def apply_star_heuristic(row):
    note = row.get('note', '')
    if '★' not in note:
        return
    m = None
    for pat in STAR_NOTE_PATTERNS:
        m = pat.search(note)
        if m:
            break
    fields = [f.strip() for f in re.split(r'[、,]\s*', m.group(1))] if m else []
    fields = [f for f in fields if f]
    if not fields:
        return
    row['reviewFlag'] = 'unescaped-candidate'
    row['reviewHint'] = '; '.join(f'{f}: {classify_star_field(f)}' for f in fields)


def apply_plan_fixes(rows):
    if not os.path.exists(FIXES_PATH):
        return rows
    with open(FIXES_PATH, 'r', encoding='utf-8') as f:
        fixes = json.load(f)
    # 'add' を先に適用する(modify が同じファイル内の add 行を対象にできるようにするため)
    by_id = {r['id']: r for r in rows}
    existing_ids = set(by_id)
    for new_row in fixes.get('add', []):
        if new_row['id'] in existing_ids:
            print(f'  警告: plan-fixes.json の add が既存行と重複しています: {new_row["id"]}（スキップ）')
            continue
        base = {'area': '', 'func': '', 'page': '', 'item': '', 'site': '', 'url': '', 'type': '',
                'set': '', 'field': '', 'fieldKind': '', 'value': '', 'expected': '', 'note': '', 'result': ''}
        base.update(new_row)
        base['source'] = 'plan-fixes.json'
        rows.append(base)
        by_id[new_row['id']] = base
        existing_ids.add(new_row['id'])
    for patch in fixes.get('modify', []):
        target = by_id.get(patch['id'])
        if target is None:
            print(f'  警告: plan-fixes.json の modify 対象 ID が見つかりません: {patch["id"]}')
            continue
        target.update(patch.get('set', {}))
    return rows


def find_xlsx():
    if len(sys.argv) > 1:
        return sys.argv[1]
    # ファイル名の日付は付いていても付いていなくてもよい（XSS対応_脆弱性テスト計画[_日付].xlsx）。複数あれば更新日時が新しいもの
    hits = glob.glob(os.path.join(REPO, 'secure_report', 'XSS対応_脆弱性テスト計画*.xlsx'))
    if not hits:
        sys.exit('XSS対応_脆弱性テスト計画*.xlsx が secure_report に見つかりません')
    return max(hits, key=os.path.getmtime)


def cell(v):
    return '' if v is None else str(v).strip()


def main():
    path = find_xlsx()
    wb = openpyxl.load_workbook(path, data_only=True)
    rows = []
    for name in SHEETS:
        for r in wb[name].iter_rows(min_row=1, values_only=True):
            rid = cell(r[0])
            if not ID_RE.match(rid):
                continue
            value, note = cell(r[7]), cell(r[10])
            m_set = re.match(r'【(S\d+)', value)
            m_url = re.match(r'【([^】]+)】(.*)', cell(r[5]), re.S)
            m_name = re.search(r'項目名\(name\):\s*([^\s/]+(?:\[\])?)', note)
            m_kind = re.search(r'種別:\s*(\w+)', note)
            rows.append({
                'id': rid,
                'sheet': name,
                'area': cell(r[1]),
                'func': cell(r[2]),
                'page': cell(r[3]),
                'item': cell(r[4]),
                'site': m_url.group(1) if m_url else '',           # CMS / 受講者サイト / WordPress / 各サイト
                'url': m_url.group(2).strip() if m_url else cell(r[5]),
                'type': cell(r[6]),
                'set': m_set.group(1) if m_set else '',
                'field': m_name.group(1) if m_name else '',
                'fieldKind': m_kind.group(1) if m_kind else '',
                'value': value,
                'expected': cell(r[8]),
                'note': note,
                'result': cell(r[9]),
            })
            apply_star_heuristic(rows[-1])
    n_before = len(rows)
    rows = apply_plan_fixes(rows)
    out = os.path.join(HERE, 'plan.json')
    with open(out, 'w', encoding='utf-8') as f:
        json.dump({'source': os.path.basename(path), 'rows': rows}, f, ensure_ascii=False, indent=1)
    by_sheet = {}
    for r in rows:
        by_sheet[r['sheet']] = by_sheet.get(r['sheet'], 0) + 1
    print(f'{os.path.basename(path)} -> {out}')
    for k, v in by_sheet.items():
        print(f'  {k}: {v} 行')
    if len(rows) != n_before:
        print(f'  (plan-fixes.json により {len(rows) - n_before} 行を追加)')


if __name__ == '__main__':
    main()
