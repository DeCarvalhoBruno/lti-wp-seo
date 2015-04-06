<?php

/**
 * Lti + i8n = ltint
 *
 * @param $text
 * @param string $domain
 *
 * @return string|void
 */
function ltint($text, $domain = 'lti-seo')
{
    return __($text, $domain);
}
