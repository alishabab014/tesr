<?php
	$demo = $_POST['demo'];
	$demo_name = $_POST['name'];
?>

<div class="rdt-popup-import__inner">
	<div class="rdt-btn-close">X</div>
	<div class="rdt-popup-import__image">
		<h3><?php echo esc_attr($demo_name); ?></h3>
		<img src="<?php echo ROZER_THEME_URI; ?>/inc/admin/themepanel/images/<?php echo esc_attr($demo); ?>.png" alt="<?php echo esc_attr($demo_name); ?>"/>
		<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
			<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
		</svg>
	</div>
	<div class="rdt-popup-import__childtheme rdt-setup-step">
		<?php 
			$is_child_theme = is_child_theme();
		?>
		<h4 class="rdt-step-title"><?php esc_html_e( 'Step 1/3 : Install child theme', 'rozer' ); ?></h4>
		<div class="rdt-step-notes childtheme-notes"></div>
		<div class="rdt-step-content">
			<?php if ( ! $is_child_theme ) : ?>
				<p> <?php esc_html_e( 'We recommned you using child theme to editting.', 'rozer' ); ?></p>
				<a class="rdt-button" href="https://developer.wordpress.org/themes/advanced-topics/child-themes/" target="_blank">Learn about child themes</a>
			<?php else: ?>
				<p><strong><?php esc_html_e( 'Great ! The child theme is used', 'rozer' ); ?></strong></p>
			<?php endif; ?>
		</div>
		<div class="rdt-step-buttons">
			<?php if ( ! $is_child_theme ) : ?>
				<a href="#" class="button-primary button button-large rdt-btn-childtheme" ><?php esc_html_e( 'Install', 'rozer' ); ?></a>
				<a href="#" class="rdt-btn-skip" ><?php esc_html_e( 'Skip this step', 'rozer' ); ?></a>
			<?php else: ?>
				<a href="#" class="button-primary button button-large rdt-btn-childtheme-next" ><?php esc_html_e( 'Next', 'rozer' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="rdt-popup-import__plugins rdt-setup-step" style="display: none;">
		<h4 class="rdt-step-title"><?php esc_html_e( 'Step 2/3 : Install plugins', 'rozer' ); ?></h4>
		<div class="rdt-step-content">
			<?php
			$road_setup = new Rdt_Theme_Panel();
			$plugins = $road_setup->get_tgmpa_plugins();
			//echo '<pre>'; print_r($plugins); echo '</pre>'; die('x_x');
			$count = count( $plugins['all'] );
			if($count) : ?>
			<p><?php echo esc_html__('Your website needs a few essential plugins. The following plugins will be installed or updated', 'rozer'); ?></p>
			<form action="" method="post">
				<ul class="roadthemez-plugins"><?php
					$action_class ="";
					foreach ( $plugins['all'] as $slug => $plugin ) :
						?><li data-slug="<?php echo esc_attr( $slug ); ?>">
							<span class="plugin-status"></span>
							<b><?php echo esc_html( $plugin['name'] ); ?></b>
							<span class="plugin-action"><?php
								$keys = array();
								if ( isset( $plugins['install'][$slug] ) ) {
									$keys[] = ' Installation';
									$action_class='install';
								}
								if ( isset( $plugins['update'][$slug] ) ) {
									$keys[] = ' Update';
									$action_class='update';
								}
								if ( isset( $plugins['activate'][$slug] ) ) {
									$keys[] = ' Activation';
									$action_class='active';
								}
								echo implode( ' and ', $keys );
							?></span>
						</li><?php
					endforeach;
				?></ul>
						
			</form>
		
			<?php else : ?>
				<p class="lead success"><strong><?php esc_html_e( 'Great ! All plugins has already been installed and up to date.', 'rozer' ) ?></strong></p>
			<?php endif; ?>	
		</div>
		<div class="rdt-step-buttons">
			<?php if($count) : ?>
				<a href="#" class="button-primary button button-large rdt-btn-plugins" ><?php esc_html_e( 'Install plugins', 'rozer' ); ?></a>
			<?php else: ?>
				<a href="#" class="button-primary button button-large rdt-btn-next" ><?php esc_html_e( 'Next', 'rozer' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="rdt-popup-import__content rdt-setup-step" style="display: none;">
		<h4 class="rdt-step-title"><?php echo esc_html__('Step 3/3 : Data import', 'rozer'); ?></h4>
		<div class="rdt-step-notes">
			<p> <?php esc_html_e('Note:', 'rozer'); ?> <br>
				<?php esc_html_e('1. It is recommended to run import on fresh WordPress installation (You can use Wordpress Database Reset plugin).', 'rozer'); ?><br>
				<?php esc_html_e('2. Importing does not delete any pages or posts. However, it can overwrite your existing content.', 'rozer'); ?>
			</p>
			
		</div>
		<div class="rdt-step-content">
			<h4>Select content for importing.</h4>
			<form class="rdt_install-demo-form" action="" data-demo="<?php echo esc_attr($demo); ?>">
				<ul>
					<li class="import_content_item" data-content="pages">
						<input type="checkbox" name="pages" class="checkbox checkbox-pages" id="default_content_pages" value="1" checked>
						<label for="default_content_pages"><i></i><span><?php esc_html_e('Pages', 'rozer'); ?></span></label>
					</li>
					<li class="import_content_item" data-content="posts">
						<input type="checkbox" name="posts" class="checkbox checkbox-posts" id="default_content_posts" value="1" checked>
						<label for="default_content_posts"><i></i><span><?php esc_html_e('Posts', 'rozer'); ?></span></label>
					</li>
					<li class="import_content_item" data-content="products">
						<input type="checkbox" name="products" class="checkbox checkbox-products" id="default_content_products" value="1" checked>
						<label for="default_content_products"><i></i><span><?php esc_html_e('Products', 'rozer'); ?></span></label>
					</li>
					<li class="import_content_item" data-content="media">
						<input type="checkbox" name="media" class="checkbox checkbox-media" id="default_content_media" value="1" checked>
						<label for="default_content_media"><i></i><span><?php esc_html_e('Media', 'rozer'); ?></span></label>
					</li>
					<li class="import_content_item" data-content="widgets">
						<input type="checkbox" name="widgets" class="checkbox checkbox-widgets" id="default_content_widgets" value="1" checked>
						<label for="default_content_widgets"><i></i><span><?php esc_html_e('Widgets', 'rozer'); ?></span></label>
					</li>
					<li class="import_content_item" data-content="options">
						<input type="checkbox" name="options" class="checkbox checkbox-options" id="default_content_options" value="1" checked>
						<label for="default_content_options"><i></i><span><?php esc_html_e('Themeoptions', 'rozer'); ?></span></label>
					</li>
					<li class="import_content_item" data-content="after_import" style="display:none">
						<input type="checkbox" name="options" class="checkbox checkbox-options" id="default_after_import" value="1" checked>
					</li>
				</ul>
				<input type="hidden" name="selected_demo" value="<?php echo esc_attr($demo); ?>" />
			</form>
		</div>
		<div class="rdt-step-buttons">
			<button class="button-primary button button-large rdt-import"><?php esc_html_e('Start import', 'rozer'); ?></button>
		</div>
	</div>
	
	<div class="rdt-popup-import__success rdt-setup-step" style="display: none;">
		<h4 class="rdt-step-title"><?php esc_html_e('Completed', 'rozer'); ?></h4>
		<div class="rdt-step-content">
			<p><?php esc_html_e('Your theme has been all set up. Enjoy your new theme !!!', 'rozer'); ?></p>
		</div>
		<div class="rdt-step-buttons">
			<a href="<?php echo get_home_url(); ?>" target="_blank" class="button-primary button button-large"><?php esc_html_e('View website', 'rozer'); ?></a>
		</div>
	</div>
</div>
