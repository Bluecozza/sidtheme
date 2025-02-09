        </main>
        <footer class="site-footer">
            <div class="container">
                <div class="footer-content">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu',
                        ));
                        ?>
                    </nav>
                </div>
            </div>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>