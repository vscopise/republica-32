<?php
    $attributes = ( shortcode_atts( array(
            'columns'           => '',
            'show_excerpt'      => '',
            'zone_name'         => '',
            'title_size'        => '',
            'posts_number'      => '',
            'posts_offset'      => '',
    ), $atts ) );

    $columns = $attributes['columns'];
    if ( $columns == '1 Column' ) {
        $bs = 'bsw-7';
    } elseif ( $columns == '2 Column' ) {
        $bs = 'bsw-4';
    } else {
        $bs = 'bsw-3';
    }
    $show_excerpt = $attributes['show_excerpt'];
    $zone_name = $attributes['zone_name'];
    $title_size = $attributes['title_size'];
    $posts_number = $attributes['posts_number'];
    $posts_offset = $attributes['posts_offset'];

    $block_settings = false;

    publisher_set_prop( 'excerpt-limit', $block_settings['excerpt-limit'] );


    $block_settings = publisher_get_option( 'listing-grid-1' );

    $title_style = ('' != $title_size) ? 'style="font-size: '.$title_size.'px"' : '';
    
    $zone_query = z_get_zone_query( 
            $zone_name, 
            array( 
                'posts_per_page'    => $posts_number,
                'offset'            => $posts_offset,
            ) 
    );
?>
<div class="z-listing bs-listing-listing-grid-1 bs-listing-single-tab">
    <div class="listing listing-classic listing-classic-1 clearfix  columns-<?php echo $columns ?>">
        <?php if ( $zone_query->have_posts() ) : ?>
        <div class="listing listing-classic listing-classic-1 clearfix  columns-<?php echo $columns ?>">
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
            <article class="type-post format-standard has-post-thumbnail listing-item listing-item-classic listing-item-classic-1 main-term-2 <?php echo $bs ?>">
                <div class="listing-inner item-inner">
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
                    <h2 class="title" <?php echo $title_style ?>>
                        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>" class="post-url post-title">
                            <?php //publisher_the_title(); ?>
                            <?php publisher_echo_html_limit_words( get_the_title(), $block_settings['title-limit'] ); ?>
                        </a>
                    </h2>
                    <?php if ( $show_excerpt ) : ?>
                    <div class="post-summary"><?php publisher_the_excerpt( publisher_get_prop( 'excerpt-limit', $block_settings['excerpt-limit'] ), NULL, TRUE, FALSE ); ?></div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>