

<?php if (have_posts()):  ?>

    <?php while (have_posts()) : the_post(); ?>
        <div class="post">
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

            <?php the_content(); ?>
        </div>
    <?php  endwhile; ?>
<?php else: ?>
    <div>
        <h2><?php _e( 'Sorry, nothing to display.', 'webfactor' ); ?></h2>
    </div>
<?php endif; ?>
