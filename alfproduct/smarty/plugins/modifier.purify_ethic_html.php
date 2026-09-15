<?php
/**
 * Render stored ethics training questions without exposing scripts.
 */
function smarty_modifier_purify_ethic_html($html)
{
    require_once dirname(__FILE__) . '/../../module/EthicHtml.php';
    return purify_ethic_html($html);
}
