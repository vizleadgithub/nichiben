<?php
/** Safe formatting for stored ethics training question and answer text. */
function purify_ethic_html($html, $inline = false)
{
    static $blockPurifier = null;
    static $inlinePurifier = null;
    $purifier = $inline ? $inlinePurifier : $blockPurifier;

    if ($purifier === null) {
        require_once dirname(__FILE__) . '/vendor/autoload.php';
        $config = HTMLPurifier_Config::createDefault();
        $allowed = 'br,strong,b,em,i,u,font[color|size|face],span[style]';
        if (!$inline) {
            $allowed = 'p,ul,ol,li,a[href],' . $allowed;
        }
        $config->set('HTML.Allowed', $allowed);
        $purifier = new HTMLPurifier($config);
        if ($inline) {
            $inlinePurifier = $purifier;
        } else {
            $blockPurifier = $purifier;
        }
    }

    return $purifier->purify((string) $html);
}
