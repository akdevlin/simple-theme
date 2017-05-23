<?php

/**  @function get_the_excerpt_max_charlength
 *   @param {int} $charlength - number of characters to allow
 *   @param {object} $daPost - the post to get the content from
 *   @returns {string} - a string with an excerpt of the post content with a link to the actual post
 *   @description A function that clips a section of the content given the desired character length
 */
function get_the_excerpt_max_charlength($charlength, $daPost) {
    $excerpt = $daPost->post_content;
    $charlength++;

    if (mb_strlen($excerpt) > $charlength) {
        $subex = mb_substr($excerpt, 0, $charlength - 5);
        $exwords = explode(' ', $subex);
        $excut = - ( mb_strlen($exwords[count($exwords) - 1]) );
        $a; 
        if ($excut < 0) {
            $a= mb_substr($subex, 0, $excut);
            //echo mb_substr($subex, 0, $excut);
        } else {
            $a = $subex;
            //echo $subex;
        }
        return trim($a) . '... <br><br><a href="' . get_the_permalink($daPost) . '" >' . '{click to read more}</a>';
    } else {
        return trim($excerpt) . '... <br><br><a href="' . get_the_permalink($daPost) . '" >' . '{click to read more}</a>';
    }
}
