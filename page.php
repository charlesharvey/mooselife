<?php get_header(); ?>



<?php if (have_posts()): while (have_posts()) : the_post(); ?>




    <div id="map_container"></div>
    <aside>
        <?php $locations = get_posts( array('post_type' => 'location', 'posts_per_page' => -1 )); ?>
        <?php foreach ($locations as $location) :  setup_postdata( $location );  ?>
            <?php $gallery = get_field('gallery', $location->ID); ?>
            <?php $day = nice_date($location->post_date, 'j'); ?>
            <?php $month = nice_date($location->post_date, 'M'); ?>
            <div class="location" id="location_<?php echo $location->ID; ?>">

                <div class="date_container">
                    <div class="day"><?php echo $day; ?></div>
                    <div class="month"><?php echo $month; ?></div>
                </div>
                <div class="stuff_container">


                    <h4 class="open_marker"   data-id="<?php echo $location->ID; ?>"><?php echo $location->post_title; ?> </h4>
                    <div class="location_content">
                        <?php the_content(); ?>
                    </div>
                    <?php if ($gallery): ?>
                        <div class="gallery" data-featherlight-gallery data-featherlight-filter="a">
                            <?php foreach ($gallery as $image) : ?>
                                <a target="gallery" href="<?php echo $image['sizes']['large']; ?>">
                                    <img width="<?php echo $image['sizes']['medium-width']; ?>" height="<?php echo $image['sizes']['medium-height']; ?>" class="lazyload" data-src="<?php echo $image['sizes']['medium']; ?>"  alt="" />

                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>





            </div>
        <?php endforeach; ?>
        <?php wp_reset_postdata();?>
    </aside>




    <script type="text/javascript">
    var locations = <?php locations_for_map(); ?>;
    </script>


    <div id="mylightbox">This div will be opened in a lightbox</div>


<?php endwhile; ?>

<?php else: ?>

    <article class="container">
        <h2><?php _e( 'Sorry, nothing to display.', 'webfactor' ); ?></h2>
    </article>

<?php endif; ?>





<?php get_footer(); ?>
