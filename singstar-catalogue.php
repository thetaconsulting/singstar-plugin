<?php

/*
  Plugin Name: Singstar song catalogue block
  Version: 1.0
  Author: Theta consulting
  Author URI: https://thetaconsulting.fi
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'SINGSTARPATH', plugin_dir_path( __FILE__ ));

class SingstarPlugin {
  function __construct() {
    global $wpdb;
    $this->charset = $wpdb->get_charset_collate();
    $this->tablename = $wpdb->prefix . "songs";

    add_action('activate_singstar-catalogue/singstar-catalogue.php', array($this, 'onActivate'));
    add_action('admin_post_createsong', array($this, 'createSong'));
    add_action('admin_post_nopriv_createsong', array($this, 'createSong'));
    add_action('admin_post_deletesong', array($this, 'deleteSong'));
    add_action('admin_post_nopriv_deletesong', array($this, 'deleteSong'));
    add_action('wp_enqueue_scripts', array($this, 'loadAssets'));
    add_action( 'wp_enqueue_scripts', array($this, 'enqueue_load_fa'));
  }

  function enqueue_load_fa() {
    wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
  }

  function deleteSong() {
    if (current_user_can('administrator')) {
      $id = sanitize_text_field($_POST['idtodelete']);
      global $wpdb;
      $wpdb->delete($this->tablename, array('id' => $id));
      $location = $_SERVER['HTTP_REFERER'];
      wp_safe_redirect($location);
    } else {
      wp_safe_redirect(site_url());
    }
    exit;
  }

  function createSong() {
    if (current_user_can('administrator')) {
      $song['songname'] = __( stripslashes(sanitize_text_field($_POST['incomingsongname'])));
      $song['game'] = __( stripslashes(sanitize_text_field($_POST['incominggamename'])));
      $song['artist'] = __( stripslashes(sanitize_text_field($_POST['incomingartistname'])));
      global $wpdb;
      $wpdb->insert($this->tablename, $song);
      $location = $_SERVER['HTTP_REFERER'];
      wp_safe_redirect($location);
    } else {
      wp_safe_redirect(site_url());
    }
    exit;
  }

  function onActivate() {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta("CREATE TABLE $this->tablename (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      game varchar(60) NOT NULL DEFAULT '',
      artist varchar(60) NOT NULL DEFAULT '',
      songname varchar(60) NOT NULL DEFAULT '',
      PRIMARY KEY  (id)
    ) $this->charset;");
    /* time to import data  */
    echo "Populating database";
    require_once( SINGSTARPATH . 'inc/Populate.php' );
     $populate = new Populate($this->tablename);

  }
  
  function loadAssets() {

      wp_enqueue_style('singstarcss', plugin_dir_url(__FILE__) . 'singstar.css');

  }
}

$singstarPlugin = new singstarPlugin();

class OurPluginPlaceholderBlock {
  function __construct($name) {
    $this->name = $name;
    add_action('init', [$this, 'onInit']);
  }

  function ourRenderCallback($attributes, $content) {
    ob_start();
    require plugin_dir_path(__FILE__) . 'our-blocks/' . $this->name . '.php';
    return ob_get_clean();
  }

  function onInit() {
    wp_register_script($this->name, plugin_dir_url(__FILE__) . "/our-blocks/{$this->name}.js", array('wp-blocks', 'wp-editor'));
    
    register_block_type("ourdatabaseplugin/{$this->name}", array(
      'editor_script' => $this->name,
      'render_callback' => [$this, 'ourRenderCallback']
    ));
  }
}

new OurPluginPlaceholderBlock("singstar");