<?php

$query = new WP_Query( array( 'post_type' => 'evenement' ) );

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
	</section><!-- #post-## -->
<?php else : ?>
	<!-- show 404 error here -->
<?php endif; ?>


