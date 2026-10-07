"""
XSS対応_脆弱性テスト計画(xlsx)を、自動テストが読む plan.json に変換する。

使い方（リポジトリ直下から）:
    python tools/stg2-e2e/test-plan/export-plan.py [xlsxのパス]

- 出力: tools/stg2-e2e/test-plan/plan.json（xlsxから再生成できるためコミットしない）
- 1行 = 計画の1行（ID・テストセット・URL・項目名など）。攻撃文字列の正本は xlsx、実行用の定義は ../xss-payloads.js
- 必要: openpyxl (pip install openpyxl)
"""
import glob
import json
import os
import re
import sys

import openpyxl

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.abspath(os.path.join(HERE, '..', '..', '..'))
SHEETS = ['管理_CMS', '管理_商品管理', 'フロント_受講者サイト', 'フロント_WordPress',
          '重点項目(全量一覧No別)', '入口以外・HTTP', '入口別の表示先']
ID_RE = re.compile(r'^[A-G]-\d+$')


def find_xlsx():
    if len(sys.argv) > 1:
        return sys.argv[1]
    hits = glob.glob(os.path.join(REPO, 'secure_report', 'XSS対応_脆弱性テスト計画_*.xlsx'))
    if not hits:
        sys.exit('XSS対応_脆弱性テスト計画_*.xlsx が secure_report に見つかりません')
    return sorted(hits)[-1]


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
    out = os.path.join(HERE, 'plan.json')
    with open(out, 'w', encoding='utf-8') as f:
        json.dump({'source': os.path.basename(path), 'rows': rows}, f, ensure_ascii=False, indent=1)
    by_sheet = {}
    for r in rows:
        by_sheet[r['sheet']] = by_sheet.get(r['sheet'], 0) + 1
    print(f'{os.path.basename(path)} -> {out}')
    for k, v in by_sheet.items():
        print(f'  {k}: {v} 行')


if __name__ == '__main__':
    main()
