<?php
/** Safe formatting for stored ethics training question and answer text. */
require_once dirname(__FILE__) . '/HtmlPolicy.php';

function purify_ethic_html($html, $inline = false)
{
    return purify_html($html, $inline ? 'ethic_inline' : 'ethic');
}
