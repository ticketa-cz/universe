<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

class universe_woo{

    private $scripts = array();
    public $woo_path;
    public $version = '1.0';

    public function __construct(){

        $this->woo_path = get_template_directory() . '/core/woo/';
        $this->king_woo_url = get_template_directory_uri().'/assets/woocommerce/';

        $this->init();

    }

    public function init(){

        universe_incl_core( '/core/woo/template-functions.php', 'ro' );

        add_filter( 'woocommerce_template_path', array( $this, 'woo_templates_path' ), 1, 1 );

        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
    }

    function woo_templates_path($path){
		return 'templates'.DS.'woocommerce'.DS;
	}

    public function disable_woo_plugin_style(){
        wp_dequeue_style('woocommerce-general');
		wp_dequeue_style('woocommerce-smallscreen');
        wp_dequeue_style('woocommerce-layout');
    }

    public function get_styles(){
        return apply_filters( 'universe_woo_enqueue_styles', array(
			'universe-woocommerce-general' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', $this->king_woo_url ) . 'css/woo-custom-style.css',
				'deps'    => '',
				'version' => $this->version,
				'media'   => 'all'
			),
            'universe-woocommerce-addon' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', $this->king_woo_url ) . 'css/woo-addon-style.css',
				'deps'    => '',
				'version' => $this->version,
				'media'   => 'all'
			),
		) );
    }

    public function load_scripts() {
		$this->disable_woo_plugin_style();

        $this->register_script('universe-magnifier-min', $this->king_woo_url. 'js/magnifier.min.js');
		$this->register_script('universe-carouFredSel-min', $this->king_woo_url. 'js/jquery.carouFredSel.min.js');
		$this->register_script('universe-woo', $this->king_woo_url. 'js/universe-woo.js');

		if ( $enqueue_styles = $this->get_styles() ) {
			foreach ( $enqueue_styles as $handle => $args ) {
				wp_enqueue_style( $handle, $args['src'], $args['deps'], $args['media'] );
			}
		}
	}

    private function register_script( $handle, $path, $deps = array( 'jquery' ), $in_footer = true ) {

        $version = $this->version;

		$this->scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );

	}

	private function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $in_footer = true ) {

        $version = $this->version;

		if ( ! in_array( $handle, $this->scripts ) && $path ) {
			$this->register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );

	}

}

new universe_woo();


