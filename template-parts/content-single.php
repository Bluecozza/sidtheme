<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        <div class="entry-meta">
            <?php
            sidtheme_posted_on();
            sidtheme_posted_by();
            ?>
        </div>
    </header>

    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'sidtheme'),
            'after'  => '</div>',
        ));
        ?>
    </div>

    <footer class="entry-footer">
        <?php sidtheme_entry_footer(); ?>
    </footer>
</article>