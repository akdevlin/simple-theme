<?php
/* Template Name: Basic */
/**
 * The basic page template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package 
 */
get_header();
?>
<?php $pageID = get_the_ID(); ?>
<main data-ad-pageid="<?php echo $pageID; ?>" >
    
</main>
<?php
get_footer();
