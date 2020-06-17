<?php
    $attributes = ( shortcode_atts( array(
            'zone_name'       => '',
            'title'       => '',
            'hide_block'       => '',
    ), $atts ) );

    $zone_name = $attributes['zone_name'];
    $title = $attributes['title'];
    $hide_block = $attributes['hide_block'];
?>
<?php if ( $hide_block != true ) : ?>
<div class="bs-listing bs-listing-listing-grid-1 bs-listing-single-tab">
    <?php if ( '' != $title ) : ?>
    <h3 class="section-heading sh-t1 sh-s5">
        <span class="h-text"><?php echo $title ?></span>
    </h3>
    <?php endif; ?>
    <?php $zone_query = z_get_zone_query( $zone_name, array( 'posts_per_page' => 4 ) ); ?>
    <?php if ( $zone_query->have_posts() ) : ?>
    <div class="listing listing-grid listing-grid-1 clearfix columns-4">
        <?php while ( $zone_query->have_posts() ) : ?>
            <?php $zone_query->the_post(); ?>
            <?php publisher_set_post_cache( 'title_attribute', get_the_title() ); ?>
            <?php
                $category  = get_the_category();
                if ( $category  ){
                    $category_display = '';
                    $category_link = '';
                    if ( class_exists('WPSEO_Primary_Term') ) {
                        $wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_ID() );
                        $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
                        $term = get_term( $wpseo_primary_term );
                        if ( is_wp_error( $term ) ) { 
                                $category_display = $category[0]->name;
                                $category_link = get_category_link( $category[0]->term_id );
                                $category_slug = $category[0]->term_id;
                        } else { 
                                $category_display = $term->name;
                                $category_link = get_category_link( $term->term_id );
                                $category_slug = $term->term_id;
                        }
                    } else {
                        $category_display = $category[0]->name;
                        $category_link = get_category_link( $category[0]->term_id );
                        $category_slug = $category[0]->term_id;
                    }
                    $cat_term = 'term-' . $category_slug;
                }
            ?>
            <article class="type-post format-standard has-post-thumbnail listing-item listing-item-grid listing-item-grid-1">
                <div class="item-inner">
                    <?php if ( has_post_thumbnail( ) ) : ?>
                        <div class="featured clearfix">
                            <div class="term-badges floated">
                                <span class="term-badge <?php echo $cat_term ?>">
                                    <a href="<?php echo $category_link ?>"><?php echo $category_display ?></a>
                                </span>
                            </div>
                            <a <?php publisher_the_thumbnail_attr( array( 'size' => 'publisher-md', 'attachment_id' => get_post_thumbnail_id() ) ) ?>
                                class="img-holder" 
                                href="<?php the_permalink() ?>"></a>
                            <?php if ( has_post_format( 'video' ) ) : ?>
                                <span class="format-icon format-video"><i class="fa fa-play"></i></span>
                            <?php elseif ( has_post_format( 'gallery' ) ) : ?>
                                <span class="format-icon format-gallery"><i class="fa fa-camera"></i></span>
                            <?php elseif ( has_post_format( 'audio' ) ) : ?>
                                <span class="format-icon format-audio"><i class="fa fa-music"></i></span>
                            <?php elseif ( has_post_format( 'aside' ) ) : ?>
                                <span class="format-icon format-aside"><i class="fa fa-pencil"></i></span>
                            <?php elseif ( has_post_format( 'image' ) ) : ?>
                                <span class="format-icon format-image"><i class="fa fa-camera"></i></span>
                            <?php elseif ( has_post_format( 'quote' ) ) : ?>
                                <span class="format-icon format-quote"><i class="fa fa-quote-left"></i></span>
                            <?php elseif ( has_post_format( 'status' ) ) : ?>
                                <span class="format-icon format-status"><i class="fa fa-refresh"></i></span>
                            <?php elseif ( has_post_format( 'chat' ) ) : ?>
                                <span class="format-icon format-chat"><i class="fa fa-coffee"></i></span>
                            <?php elseif ( has_post_format( 'link' ) ) : ?>
                                <span class="format-icon format-link"><i class="fa fa-link"></i></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h2 class="title">
                    <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                        <span class="post-title"><?php the_title() ?></span>
                    </a>
                </h2>
                <div class="post-summary"><?php publisher_the_excerpt( publisher_get_prop( 'excerpt-limit', 115 ), NULL, TRUE, FALSE ); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
