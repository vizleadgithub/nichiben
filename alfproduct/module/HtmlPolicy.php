<?php
/**
 * 許可タグ方式(HTMLPurifier)の共通定義。許可タグ以外・危険な属性/スキームは除去する。
 *
 * profile:
 *   general       お知らせ・固定ページ・自由入力欄 (No.28/31/32)
 *   product       商品説明・商品の備考 (No.29/30)    = general + u
 *   ethic         倫理研修・アンケート/問題の設問 (No.33/34)
 *   ethic_inline  設問の選択肢 (label内に出すため br/font のみ)
 */

function html_policy_definitions()
{
    $general = 'h1[style],h2[style],h3[style],h4[style],h5[style],h6[style],'
        . 'p[style],br[style],ul[style],li,div[id|class|style],span[style],'
        . 'a[href|target|title],img[src|alt|title|width|height|class|style],'
        . 'strong,b,font[color],del';

    $general_css = array(
        'margin', 'margin-left', 'margin-bottom', 'padding', 'padding-bottom',
        'font-size', 'font-weight', 'color', 'text-align', 'text-decoration',
        'clear', 'float', 'width', 'border', 'background-color',
    );

    // 実データで使われているclass/idのみ許可 (WordPress標準の配置クラスを含む)
    $general_classes = array(
        'file', 'pankuzu', 'single_title', 'single_main',
        'aligncenter', 'alignleft', 'alignright', 'size-full',
    );
    $general_id_reject = '/^(?!single_title$|single_main$)/';

    return array(
        'general' => array(
            'allowed' => $general,
            'css'     => $general_css,
            'classes' => $general_classes,
            'id_reject_regexp' => $general_id_reject,
        ),
        'product' => array(
            'allowed' => $general . ',u',
            'css'     => $general_css,
            'classes' => $general_classes,
            'id_reject_regexp' => $general_id_reject,
        ),
        'ethic' => array(
            'allowed' => 'h1,h2,h3,h4,h5,h6,p[style],br,a[href|target|title],font[color]',
            'css'     => array('color'),
        ),
        'ethic_inline' => array(
            'allowed' => 'br,font[color]',
            'css'     => array('color'),
        ),
    );
}

function html_policy_purifier($profile)
{
    static $purifiers = array();
    if (isset($purifiers[$profile])) {
        return $purifiers[$profile];
    }

    $defs = html_policy_definitions();
    if (!isset($defs[$profile])) {
        throw new InvalidArgumentException('unknown html policy profile: ' . $profile);
    }
    $def = $defs[$profile];

    require_once dirname(__FILE__) . '/vendor/autoload.php';

    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', $def['allowed']);
    $config->set('CSS.AllowedProperties', $def['css']);
    $config->set('Attr.AllowedFrameTargets', array('_blank', '_self'));
    // リンク先は http / https のみ許可(mailto: tel: は実データに使用実績がないため対象外)
    $config->set('URI.AllowedSchemes', array('http' => true, 'https' => true));
    if (!empty($def['classes'])) {
        $config->set('Attr.AllowedClasses', $def['classes']);
    }
    if (!empty($def['id_reject_regexp'])) {
        $config->set('Attr.EnableID', true);
        $config->set('Attr.IDBlacklistRegexp', $def['id_reject_regexp']);
    }

    $cache_dir = sys_get_temp_dir() . '/htmlpurifier_cache';
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0755, true);
    }
    if (is_dir($cache_dir) && is_writable($cache_dir)) {
        $config->set('Cache.SerializerPath', $cache_dir);
    }

    $purifiers[$profile] = new HTMLPurifier($config);
    return $purifiers[$profile];
}

function purify_html($html, $profile = 'general')
{
    return html_policy_purifier($profile)->purify((string) $html);
}

/**
 * 無害化済みHTMLのうち、リンク(<a>)の外にあるテキスト中のURLだけを自動でリンク化する。
 * 手書きのアンカーや属性値は変更しない。必ずpurify_html()の後に呼ぶこと。
 */
function autolink_html($html)
{
    $parts = preg_split('/(<[^>]*>)/', (string) $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    $anchor_depth = 0;
    $out = '';
    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }
        if ($part[0] === '<') {
            if (preg_match('/^<a[\s>]/i', $part)) {
                $anchor_depth++;
            } elseif (preg_match('/^<\/a\s*>/i', $part)) {
                $anchor_depth = max(0, $anchor_depth - 1);
            }
            $out .= $part;
            continue;
        }
        if ($anchor_depth > 0) {
            $out .= $part;
            continue;
        }
        $out .= preg_replace_callback(
            '~https?://(?:(?!&(?:lt|gt|quot);)[A-Za-z0-9\-._\~:/?#\[\]@!$&\'()*+,;=%])+~',
            function ($m) {
                $url = $m[0];
                $trail = '';
                while ($url !== '' && preg_match('/[.,;:!?)]$/', $url)) {
                    if (substr($url, -1) === ')' && strpos($url, '(') !== false) {
                        break;
                    }
                    $trail = substr($url, -1) . $trail;
                    $url = substr($url, 0, -1);
                }
                $text = htmlspecialchars(html_entity_decode($url, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                return '<a href="' . $text . '" target="_blank" rel="noopener noreferrer">' . $text . '</a>' . $trail;
            },
            $part
        );
    }
    return $out;
}

/** 商品説明・商品の備考用: 許可タグ方式で無害化し、テキスト中のURLを自動リンク化する */
function render_product_text($html)
{
    return autolink_html(purify_html($html, 'product'));
}
