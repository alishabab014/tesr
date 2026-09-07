<?php
/**
 * Template Name: About Us
 */

get_header();
?>

<div class="page-title-wrapper about-title-wrapper">
	<div class="container">
		<h1 class="page-title"><?php esc_html_e( 'About Us', 'rozer' ); ?></h1>
	</div>
</div>

<?php echo rozer_breadcrumb(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<div id="content">
	<div class="container">
		<div class="about-us-page">

			<section class="about-intro">
				<div class="about-intro-image">
					<img src="https://aaadiapers.pk/wp-content/uploads/2025/03/1500x1500-1024x1024.png" alt="<?php esc_attr_e( 'AAA Diapers - mother and baby', 'rozer' ); ?>" loading="lazy">
				</div>
				<div class="about-intro-content">
					<h2><?php esc_html_e( 'Company Introduction', 'rozer' ); ?></h2>
					<p><?php esc_html_e( 'In our innovative, modern, technological and environmentally friendly production facility, which we laid the foundations of in 2014, we started our first productions in 2014 with the aim of producing quality products that we can use safely in our own babies and with the aim of enabling people in different geographies of the world to reach this quality. Today, we have become a huge and strong family together with our distributors and business partners, delivering these products to our consumers in many geographies who use our branded products safely.', 'rozer' ); ?></p>
					<p><?php esc_html_e( 'We proudly carry quality and hygiene to every country we go to, with our motto of "happy mothers, happy babies", sustainable quality, innovation and adopting the human element as the main principle on the path of growth. As a big and strong family with our distributors, business partners and suppliers in many countries of the world, we work with all our strength for a more beautiful world, a more beautiful and quality life, with a sense of responsibility, always adopting respect for nature, people and new ideas as our basic principle.', 'rozer' ); ?></p>
				</div>
			</section>

		</div>
	</div>

	<section class="about-values-section">
		<div class="about-values">
			<div class="about-value-card">
				<span class="about-value-badge">01</span>
				<h3><?php esc_html_e( 'Our Goal', 'rozer' ); ?></h3>
				<p><?php esc_html_e( 'We derive our energy on the path of growth from our determination. By showing our determination and courage, we are moving towards our goals with fast steps.', 'rozer' ); ?></p>
			</div>
			<div class="about-value-card">
				<span class="about-value-badge">02</span>
				<h3><?php esc_html_e( 'Success', 'rozer' ); ?></h3>
				<p><?php esc_html_e( 'We know that the first element of success is believing and only people who believe are successful. In order to achieve success, we are advancing towards our goals with determination and relentlessly.', 'rozer' ); ?></p>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
