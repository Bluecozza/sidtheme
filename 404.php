<?php get_header(); ?>

<div class="content-area">
    <h1><?php _e('404 - Page Not Found', 'sidtheme'); ?></h1>
    <p><?php _e('The page you are looking for does not exist.', 'sidtheme'); ?></p>
    <a href="<?php echo home_url('/'); ?>"><?php _e('Return to Home', 'sidtheme'); ?></a>
</div>

<?php get_footer(); ?>