"""
secure_report/脆弱性テスト計画_最終結果一覧.csv (自動テスト・コードレビューの最終判定) を、
secure_report/XSS対応_脆弱性テスト計画.xlsx の OK/NG列・備考列に書き戻す。

- 既存の値と一致する行は触らない(同僚の記入をそのまま残す)。
- 一致しない行だけ、OK/NG列を更新し、備考列には既存の内容を残したまま末尾に追記する
  (【2026-10-12 自動テスト・コードレビューで確認】のマーカー付き)。
- plan-fixes.json由来でxlsxに実体行が無いIDは対象外(更新リストに含まれない)。

使い方: python tools/stg2-e2e/test-plan/apply-results-to-xlsx.py tmp-xlsx-updates.json
"""
import json
import sys
import os

import openpyxl

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.abspath(os.path.join(HERE, '..', '..', '..'))
XLSX_PATH = os.path.join(REPO, 'secure_report', 'XSS対応_脆弱性テスト計画.xlsx')
SHEETS = ['管理_CMS', '管理_商品管理', 'フロント_受講者サイト', 'フロント_WordPress',
          '重点項目(全量一覧No別)', '入口以外・HTTP', '入口別の表示先']

updates_path = sys.argv[1] if len(sys.argv) > 1 else os.path.join(HERE, '..', 'tmp-xlsx-updates.json')
with open(updates_path, encoding='utf-8') as f:
    updates = {u['id']: u for u in json.load(f)}

wb = openpyxl.load_workbook(XLSX_PATH)
applied = 0
not_found = set(updates.keys())
for sheet_name in SHEETS:
    ws = wb[sheet_name]
    for row in ws.iter_rows(min_row=5):
        cell_id = row[0].value
        if cell_id not in updates:
            continue
        u = updates[cell_id]
        okng_cell = row[9]
        note_cell = row[10]
        okng_cell.value = u['newResult']
        existing = note_cell.value or ''
        marker = '【2026-10-12 自動テスト・コードレビューで確認】'
        if marker not in existing:
            note_cell.value = (existing + ('\n' if existing else '') + marker + u['reason'])
        applied += 1
        not_found.discard(cell_id)

wb.save(XLSX_PATH)
print(f'更新: {applied} 件')
if not_found:
    print(f'xlsxに見つからなかったID({len(not_found)}件):', ', '.join(sorted(not_found)))
