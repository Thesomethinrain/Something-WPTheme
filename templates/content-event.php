<?php

add_filter('posts_orderby','customorderby');


$argnext = array(
	
    
	'post_type' => 'evenement',
	'meta_key'		=> 'evt_date',
	//'orderby'		=> 'meta_value_num',
	//'order'			=> 'ASC',	
	'meta_query' 	=> array(
		array(
            'key' => 'evt_date',
            'value' => date('Y-m-d'),
            'type' => 'DATE',
            'compare' => '>='
            )
		)
);


$query = new WP_Query($argnext);

?>

<header class="entry-header">
	<h1>TEST-EVENT</h1>
</header><!-- .entry-header -->

<section class="grid">
	<?php if ( $query->have_posts() ) : ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>   



			<article class="grid-item">
				<div>
					<h2><?php the_title(); ?></h2>
					<h4><?php echo date_fr(); ?></h4>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
		<!-- show pagination here -->
		<?php else : ?>
		<!-- show 404 error here -->
	<?php endif; ?>

</section><!-- #post-## -->

<?php wp_reset_query(); ?>

<?php

$argpast = array(
	
    
	'post_type' => 'evenement',
	'meta_key'		=> 'evt_date',
	'orderby'		=> 'meta_value_num',
	'order'			=> 'ASC',	
	'meta_query' 	=> array(
		array(
            'key' => 'evt_date',
            'value' => date('Y-m-d'),
            'type' => 'DATE',
            'compare' => '<='
            )
		)
);


$query = new WP_Query($argpast);

?>


<section>
	<?php if ( $query->have_posts() ) : ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>   
			<p><?php the_title(); ?> - <?php echo date_fr(); ?></p>
		<?php endwhile; wp_reset_postdata(); ?>
		<!-- show pagination here -->
		<?php else : ?>
			<!-- show 404 error here -->
	<?php endif; ?>
</section>


