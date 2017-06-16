<?php
/**
 * Logic for a modal dialog on the homepage using ThickBox
 */
class Learn_Homepage_Modal {
	private $cookie_name = 'learn_homepage_modal';

	/**
	 * Register all our things, and enqueue the necessary scripts
	 */
	function __construct() {
		if ( $this->conditional() ) {
			wp_enqueue_script( 'thickbox' );
			add_action( 'wp_footer', array( $this, 'template' ) );
			add_action( 'wp_footer', array( $this, 'load' ) );
		}
	}

	/**
	 * Condition on whether to output the stuff
	 *
	 * the idea of making this a separate function is that maybe we can do cookie detection here instead of in JS
	 */
	private function conditional() {
		return is_home();
	}

	function template() {
		?>
			<script type="text/template" id="tmpl-homepage-modal">
				<div id="TB_inline">
					<h2>Test</h2>
				</div>
			</script>
		<?php
	}

	function load() {
		?>
			<script type="text/javascript">
				jQuery(document).ready( function() {
					console.log('trying');
					tb_show('HAI', '#TB_inline?height=240&width=240&inlineId=TB_inline&modal=true', null);
				});
			</script>
		<?php
	}
}

add_action( 'template_redirect', function() {
	$Learn_Homepage_Modal = new Learn_Homepage_Modal();
} );
