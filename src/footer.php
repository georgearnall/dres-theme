<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Expound
 */
?>

</div><!-- #main -->

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info">
        &copy; Copyright <?php echo date('Y'); ?>
        | <?php bloginfo('name'); ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>