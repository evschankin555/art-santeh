<?php
/**
 * Template part for displaying related posts
 *
 * @package Cenos
 */

$related_posts = new WP_Query( apply_filters( 'cenos_related_posts_args', array(
	'post_type'              => 'post',
	'post_status'            => 'publish',
	'posts_per_page'         => 6,
	'orderby'                => 'rand',
	'category__in'           => wp_get_post_categories(),
	'post__not_in'           => array( get_the_ID() ),
	'no_found_rows'          => true,
	'update_post_term_cache' => false,
	'update_post_meta_cache' => false,
	'cache_results'          => false,
	'ignore_sticky_posts'    => true,
) ) );

if ( ! $related_posts->have_posts() ) {
	return;
}
$cenos_breakpoints = cenos_responsive_breakpoints();
$related_responsive = cenos_single_blog_related_responsive();
$data_space = [
    'lg' => 20,
    'xl' => 30,
    'xxl' => 30
];
$data_responsive = [];
if (!empty($related_responsive)){
    foreach ($related_responsive as $key => $col) {
        if (isset($cenos_breakpoints[$key])) {
            $data_responsive[$cenos_breakpoints[$key]] = [
                'slidesPerView' => $col,
                'spaceBetween' => isset($data_space[$key])? $data_space[$key]:15,
            ];
        }
    }
}

$sw_data = [
    'slidesPerView' => 1,
    'spaceBetween' => 0,
    'breakpoints' => $data_responsive,
];
?>

<div class="related-posts">
	<h2><?php esc_html_e( 'You might also like', 'cenos' ) ?></h2>
    <div class="cenos-carousel swiper-container" data-pagination="page" data-swiper="<?php cenos_esc_data(htmlspecialchars(json_encode($sw_data)));?>">
        <div class="posts swiper-wrapper">

            <?php
            $post_class = array('grid__item cenos-post-item post-grid swiper-slide') ;
            while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
                <?php get_template_part( 'template-parts/content-list-grid' ,null,['post_class' => $post_class]); ?>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
wp_reset_postdata();
