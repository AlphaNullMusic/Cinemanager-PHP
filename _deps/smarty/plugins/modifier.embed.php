<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty youtube embed url modifier plugin
 *
 * Type:     modifier<br>
 * Name:     ytembedurl<br>
 * Purpose:  get the youtube embed url from a normal youtube url
 */

// http://stackoverflow.com/questions/5830387/how-to-find-all-youtube-video-ids-in-a-string-using-a-regex?answertab=active

function smarty_modifier_embed($string, $default = '') {
  $string = preg_replace('~
      https?://         # Required scheme. Either http or https.
      (?:[0-9A-Z-]+\.)? # Optional subdomain.
      (?:               # Group host alternatives.
        youtu\.be/      # Either youtu.be,
      | youtube\.com    # or youtube.com followed by
        \S*             # Allow anything up to VIDEO_ID,
        [^\w\-\s]       # but char before ID is non-ID char.
      )                 # End host alternatives.
      ([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
      (?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
      (?!               # Assert URL is not pre-linked.
        [?=&+%\w]*      # Allow URL (query) remainder.
        (?:             # Group pre-linked alternatives.
          [\'"][^<>]*>  # Either inside a start tag,
        | </a>          # or inside <a> element text contents.
        )               # End recognized pre-linked alts.
      )                 # End negative lookahead assertion.
      [?=&+%\w-]*       # Consume any URL (query) remainder.
      ~ix', 
      '<div class="modifier_youtube"><iframe width="560" height="315" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div>',
      $string);
  return $string;
}

?>