<?php global $locations; ?>
<?php $home_url =  home_url(); ?>
<div id="map_container"></div>
<aside>

    <?php $days_travelled = days_travelled($locations); ?>
    <?php $countries = get_terms(['taxonomy' => 'country', 'hide_empty' => true, 'orderby' => 'name']); ?>
    <?php $trips = get_terms(['taxonomy' => 'trip', 'hide_empty' => false, 'orderby' => 'slug']); ?>
    <div id="map_functions">
        <ul>
            <li>
                <a class="button" href="#">Points</a>
                <ul>
                    <li><a href="#" class="button" id="see_latest">View latest</a></li>
                    <li><a class="button country_picker" data-country="all" id="see_all" href="#">View all</a></li>
                </ul>

            </li>
            <li>
                <a href="#" class="button">Countries</a>

                <?php if ($countries) : ?>
                    <ul>
                        <?php foreach ($countries as $country) : ?>
                            <li>
                                <a href="<?php echo $home_url; ?>/country/<?php echo $country->slug; ?>" class="button country_picker" data-country="<?php echo $country->slug; ?>">
                                    <?php echo $country->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><a href="<?php echo $home_url; ?>" class="button">All trips</a></li>




                    </ul>
                <?php endif; ?>
            </li>
            <li>
                <a class="button" href="#">Trips</a>
                <ul>
                    <?php foreach ($trips as $trip) : ?>
                        <li>
                            <a href="<?php echo $home_url; ?>/trip/<?php echo $trip->slug; ?>" class="button country_picker">
                                <?php echo $trip->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li><a class="button country_picker" href="<?php echo $home_url; ?>/?all_trips">All trips</a></li>

                </ul>

            </li>
        </ul>
        <ul class="right">
            <li><a class="button" id="days_spent" href="#"><?php echo $days_travelled; ?></a></li>
            <li><a class="button" id="total_distance" href="#"> ... </a></li>
        </ul>
    </div>


    <?php foreach ($locations as $location) :  setup_postdata($location);  ?>
        <?php $gallery = get_field('gallery', $location->ID); ?>
        <?php $day = nice_date($location->post_date, 'j'); ?>
        <?php $month = nice_date($location->post_date, 'M'); ?>
        <div class="location" data-id="<?php echo $location->ID; ?>" id="location_<?php echo $location->ID; ?>">

            <div class="date_container">
                <div class="day"><?php echo $day; ?></div>
                <div class="month"><?php echo $month; ?></div>
            </div>
            <div class="stuff_container">


                <h4 class="open_marker"><?php echo $location->post_title; ?> </h4>

                <?php // $countries = get_the_terms( $location->ID, 'country'); 
                ?>
                <?php // $country = ( $countries ) ?  $countries[0] : false; 
                ?>
                <?php // if ($country)  echo '<span class="country">' .   $country->name .'</span>'; 
                ?>

                <div class="location_content">
                    <?php the_content(); ?>
                </div>
                <?php if ($gallery) : ?>
                    <div class="gallery" data-featherlight-gallery data-featherlight-filter="a">
                        <?php foreach ($gallery as $image) : ?>


                            <?php if ($image['type'] == 'video') : ?>
                                <video controls>
                                    <source src="<?php echo $image['url']; ?>" type="<?php echo $image['mime_type']; ?>" />
                                </video>
                            <?php else : ?>
                                <a target="gallery" class="lightbox_link" href="<?php echo $image['sizes']['large']; ?>">
                                    <img width="<?php echo $image['sizes']['medium-width']; ?>" height="<?php echo $image['sizes']['medium-height']; ?>" class="lazyload" data-src="<?php echo $image['sizes']['medium']; ?>" alt="" />

                                </a>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>
</aside>




<script type="text/javascript">
    var locations = <?php locations_for_map($locations); ?>;
</script>


<div id="lightbox_outer">
    <div id="lightbox_inner">Loading...</div>
</div>