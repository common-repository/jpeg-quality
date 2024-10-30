<?php
/**
 * @package JPEG Quality
 * @version 1.0.0
 */
/*
Plugin Name: JPEG Qualtiy
Plugin URI: https://invisibledragonltd.com/wordpress/jpeg-quality/
Description: Ability to control JPEG quality in Wordpress
Author: Invisible Dragon
Author URI: https://invisibledragonltd.com/
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Version: 1.0.0
*/
add_action( 'plugins_loaded', array( 'JPEGQuality', 'get_instance' ) );

class JPEGQuality {
	protected static $instance = null;
	private $plugin_name = 'jpeg-quality';

	private function __construct(){
		add_filter( 'wp_image_editors', array( $this, 'wp_image_editors' ) );
		add_action( 'admin_init', array( $this, 'initialize_display_options') );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		$options = get_option( 'jpeg-quality' );
		if( $options ){
		  add_filter( 'jpegquality_all', array( $this, 'get_jpegquality' ), 100, 2 );
		}
	}

	public function get_jpegquality($quality, $size='thumbnail'){
		$options = get_option('jpeg-quality');
		if(!array_key_exists($size, $options)) return $quality;
		return intval($options[$size]);
	}

	public function wp_image_editors($editors){
		require_once plugin_dir_path( __FILE__ ) . 'classes.php';
		return array_merge(
			array('JPEGQuality_Image_Editor_Imagick', 'JPEGQuality_Image_Editor_GD'),
			$editors
		);
	}

	public function admin_menu() {
		add_options_page(
			__( 'JPEG Quality Settings', $this->plugin_name ),
			__( 'JPEG Quality Settings', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'render_settings_page_content' )
		);
	}

	public function options_callback() {
		echo '<p>';
		echo __( 'Override the quality settings for certain thumbnail sizes. Wordpress default is 82', $this->plugin_name );
		echo '</p>';
	}

    public function quality_callback( $args ) {
		$field_options_variable = $args['options_var'];
		$field_name = $args['field_name'];

		$options = get_option( $field_options_variable );

		$label = apply_filters('jpegquality_' . $field_name,
        apply_filters('jpegqquality_all', apply_filters('wp_editor_set_quality', 82), $field_name ) );

		$html = '<input type="number" id="'.$field_name.'" name="'.$field_options_variable.'['.$field_name.']" value="'.$label.'" class="regular-text" />';

		echo $html;
	}

	public function initialize_display_options() {
		add_settings_section(
			'jpeg-quality',
			__('Quality settings for thumbnail size'),
			array($this, 'options_callback' ),
			'jpeg-quality'
		);

		$sizes = get_intermediate_image_sizes();
		foreach ($sizes as $size) {
			$label = ucwords( str_replace( '_', ' ', str_replace( '-', ' ', $size ) ) );
			add_settings_field(
				'jpeg-quality-' . $size,
				$label,
				array( $this, 'quality_callback' ),
				'jpeg-quality',
				'jpeg-quality',
				array(
					'options_var' => 'jpeg-quality',
					'field_name' => $size,
				)
			);
		}
		
		register_setting( 'jpeg-quality', 'jpeg-quality' );
	}

	public function render_settings_page_content(){
		echo '<h1>';
		echo esc_html( get_admin_page_title() );
		echo '</h1>';
		echo '<form method="post" action="options.php">';
		settings_fields( 'jpeg-quality' );
		do_settings_sections( 'jpeg-quality' );
		submit_button();
		echo '</form>';
		echo '<a href="//invisibledragonltd.com/wordpress">';
		echo __( 'Wordpress Plugin by ', $this->plugin_name );
		echo '<img style="height: 24px;vertical-align:middle" src="' . plugin_dir_url( __FILE__ ) . 'clogo.svg" /></a>';
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}
