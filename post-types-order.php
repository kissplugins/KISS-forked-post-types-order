<?php
/*
* Plugin Name: Post Types Order (KISS Fork)
* Plugin URI: https://kissplugins.com
* Description: Category filter addeed. Posts Order and Post Types Objects Order using a Drag and Drop Sortable javascript capability
* Author: Nsp Code Original Authors - Nsp Code, KISS Code
* Author URI: https://kissplugins.com
* Version: 2.9.6
* Text Domain: post-types-order
* Domain Path: /languages/
*/

    define('CPTPATH',   plugin_dir_path(__FILE__));
    define('CPTURL',    plugins_url('', __FILE__));

    define('PTO_VERSION',          '2.9.6');

    include_once(CPTPATH . '/include/class.cpto.php');
    include_once(CPTPATH . '/include/class.functions.php');


    /**
    * Initialize the main class
    *
    */
    function cpto_class_load()
        {

            global $CPTO;
            $CPTO   =   new CPTO();

            // Initialize self-tests (admin only)
            if (is_admin()) {
                include_once(CPTPATH . '/include/class.self-tests.php');
                new PTO_SelfTests();
            }
        }
    add_action( 'plugins_loaded', 'cpto_class_load');


    /**
    * Load the plugin textdomain
    *
    */
    function cpto_load_textdomain()
        {
            load_plugin_textdomain('post-types-order', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages');
        }
    add_action( 'plugins_loaded', 'cpto_load_textdomain');

    /**
    * Add Self Tests link to the plugin row on the Plugins page
    */
    function cpto_add_self_tests_action_link( $links )
        {
            $url = admin_url( 'tools.php?page=pto-self-tests' );
            $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Self Tests', 'post-types-order' ) . '</a>';
            return $links;
        }
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cpto_add_self_tests_action_link' );



    /**
    * Initialize the plugin
    *
    */
    function init_cpto()
        {
	        global $CPTO;

            $options          =     $CPTO->functions->get_options();

            if (is_admin())
                {
                    if(isset($options['capability']) && !empty($options['capability']))
                        {
                            if( current_user_can($options['capability']) )
                                $CPTO->init();
                        }
                    else if (is_numeric($options['level']))
                        {
                            if ( $CPTO->functions->userdata_get_user_level(true) >= $options['level'] )
                                $CPTO->init();
                        }
                        else
                            {
                                $CPTO->init();
                            }
                }
        }
    add_action('wp_loaded', 'init_cpto' );