<?php get_header(); ?>

<div class="content-area">
    <div class="container">
        <?php
        while (have_posts()) : 
            the_post();
            get_template_part('template-parts/content', 'single');
            
            // Navigasi post
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'sidtheme') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'sidtheme') . '</span> <span class="nav-title">%title</span>',
            ));

            // Komentar
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>