<?php
/**
 * The front page template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
