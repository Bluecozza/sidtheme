<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        // Tampilkan featured image
        if (has_post_thumbnail()) :
            echo '<div class="featured-image">';
            the_post_thumbnail('large', array('class' => 'post-thumbnail'));
            echo '</div>';
        endif;

        // Judul post
        the_title('<h1 class="entry-title">', '</h1>');

        // Meta data
        echo '<div class="entry-meta">';
        sidtheme_posted_on();
        sidtheme_posted_by();
        echo '</div>';
        ?>
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