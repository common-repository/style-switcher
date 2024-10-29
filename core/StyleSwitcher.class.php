<?php
namespace StyleSwitcher;

class StyleSwitcher {
  /** @var string The plugin version number */
  var $version = '1.0.1';

  public $settings;
  public $items;

  function __construct() {

    $this->include_before_theme();

    add_action( 'admin_enqueue_scripts', array($this, 'load_admin_scripts') );
    add_action( 'wp_enqueue_scripts', array($this, 'load_front_scripts') );


    $this->settings = get_option('style-switcher-options');
    $this->items = get_option('style-switcher-items-options');

    if ($this->settings["show-switcher-options"] == 'on') {
      add_action( 'wp_footer', array($this, 'display_ui'), 100 );
    }

    $this->addAjaxCalls();

    $this->devDebug();

  }

  private function include_before_theme() {
    /* SETTINGs */
    include_once(STYLESWITCHER__PLUGIN_DIR . 'core/StyleSwitcherConstants.class.php');
    include_once(STYLESWITCHER__PLUGIN_DIR . 'core/StyleSwitcherSettings.class.php');
    include_once(STYLESWITCHER__PLUGIN_DIR . 'core/StyleSwitcherUI.class.php');
  }

  function load_front_scripts( $hook ) {
    wp_enqueue_style( 'style-switcher-css', plugin_dir_url( __FILE__ ) . 'css/style-switcher.css');
    wp_enqueue_script( 'style-switcher-js', plugin_dir_url( __FILE__ ) . 'js/style-switcher.js', array( 'jquery' ), '1.0' );
  }

  function load_admin_scripts( $hook ) {
    if ( $hook != 'settings_page_style-switcher-options' ) {
      return;
    }
    wp_enqueue_script( 'style-switcher-admin-js', plugin_dir_url( __FILE__ ) . 'js/style-switcher-admin.js', array( 'jquery' ), '1.0' );
    wp_enqueue_style( 'style-switcher-admin-css', plugin_dir_url( __FILE__ ) . 'css/style-switcher-admin.css');

  }

  function display_ui() {
    $ui = new StyleSwitcherUI();
    $ui->printALL();
  }

  function addAjaxCalls() {
    add_action( 'wp_ajax_style_switcher_remove_item', array($this, 'style_switcher_remove_item') );
  }

  function style_switcher_remove_item() {
    if (!empty($_POST["id"])) {
      $items = get_option( 'style-switcher-items-options' );

      if ($_POST["type"] == "item") {
        unset($items['items'][$_POST["id"]]);

      }
      elseif ($_POST["type"] == "option" && !empty($_POST["itemID"]) && !empty($_POST["field"])) {

        unset($items['items'][$_POST["itemID"]][$_POST["field"]]["repeatitems"][$_POST["id"]]);
      }


      update_option( 'style-switcher-items-options', $items );
      wp_die();
    }
  }


  private function devDebug() {
    // ADD MORE DEBUGING WHEN YOU NEED LATER
  }

}
