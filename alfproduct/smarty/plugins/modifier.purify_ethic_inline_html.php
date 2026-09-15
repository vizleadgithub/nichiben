<?php
/** Render stored answer choices safely inside labels or table cells. */
function smarty_modifier_purify_ethic_inline_html($html)
{
    require_once dirname(__FILE__) . '/../../module/EthicHtml.php';
    return purify_ethic_html($html, true);
}
