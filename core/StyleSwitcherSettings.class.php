<?php
namespace StyleSwitcher;

class StyleSwitcherSettings {
  public static $initiated = false;

  public $settingsGroups, $options, $itemkey=0, $itemsHTML;

  public $settings = StyleSwitcherConstants::SETTINGS;
  public $groups = StyleSwitcherConstants::SETTINGS_GROUPS;
  public $items = array();


  function __construct( $setting = false ) {
    $this->getSettingsByGroup();
    $this->itemkey = 1;
    $this->options = get_option( 'style-switcher-options' );
    $this->items = get_option( 'style-switcher-items-options' );

    if (!self::$initiated) {

      add_action( 'admin_init', array($this, 'register_settings' ) );
      add_action( 'admin_menu', array($this, 'register_options_page' ) );
      self::$initiated = true;
    }
  }
  function register_settings() {
    register_setting(
        'style-switcher-options', // Option group
        'style-switcher-options-general', // Option name
        array( $this, 'sanitize' ) // Sanitize
    );

    register_setting(
        'style-switcher-options', // Option group
        'style-switcher-items-options', // Option name
        array( $this, 'sanitize' ) // Sanitize
    );

    foreach ( $this->settingsGroups as $group => $arr ) {
      $thisGroup = $this->getGroupName( $group );

      if ($thisGroup == "style-switcher-options-items") {
        $this->prepItemsHTML($arr);
      }
      else {
        add_settings_section(
            $thisGroup, // ID
            $this->groups[$group]["name"], // Title
            array( $this, 'print_section_info' ), // Callback
            'style-switcher-options-general' // Page
        );
        /* LOOP THROUGH GROUP SETTINGS */
        foreach ($arr as $setting => $settingArray) {
          add_settings_field(
              $setting, // ID
              $settingArray["name"], // Title
              array( $this, 'setting_callback' ), // Callback
              'style-switcher-options-general', // Page
              $thisGroup, // Section
              $settingArray
          );
        }
      }
    }
  }

  function register_options_page() {

    add_options_page('Style Switcher Options', 'Style Switcher Options', 'manage_options', 'style-switcher-options', array($this, "options_page"));
  }

  function options_page() {
    ?>
    <h2>Style Switcher Settings</h2>

    <div class="style-switcher-settings-page-wrapper">
      <form action="options.php" method="post">
      <?php

        settings_fields( 'style-switcher-options' );
        do_settings_sections( 'style-switcher-options-general' );


        ?>
        <div class="style-switcher-items-wrapper">
          <?php
          //do_settings_sections( 'style-switcher-items-options' );
          echo $this->itemsHTML;

          ?>
        </div>
        <input type="hidden" id="switcher-item-key" name="switcher-item-key" value="<?php echo sizeof($this->items["items"]) + 1; ?>">
        <button id="style-switcher-addnew-button">Add Another Style Switcher Item</button>

        <?php
        submit_button();
      ?>
    </form>
    </div>
    <?php
  }

  function setting_callback( $args ) {
    $html = '';
    $thisGroup = $args["group"];
    $thisClasses = '';
    if (!empty($args["classes"])) {
      $thisClasses .= $args["classes"];
    }
    if ($args["type"] == "repeat") {
      $thisClasses .= " style-switcher-repeat";
    }
    $html .= '<div class="' . $thisClasses . '">';

    if ($thisGroup == "items") {
      if ($args["type"] == "repeat") {


        $thisName = 'style-switcher-items-options[items][' . $args["itemkey"] . '][' . $args["id"] . '][repeatitems][' . $args["repeatkey"] . ']';
        $thisValue = isset( $this->items['items'][$args["itemkey"]][$args["id"]]['repeatitems'][$args["repeatkey"]] ) ? esc_attr( $this->items['items'][$args["itemkey"]][$args["id"]]['repeatitems'][$args["repeatkey"]] ) : '';
      }
      else {
        $thisName = 'style-switcher-items-options[items][' . $args["itemkey"] . '][' . $args["id"] . ']';
          $thisValue = isset( $this->items['items'][$args["itemkey"]][$args["id"]] ) ? esc_attr( $this->items['items'][$args["itemkey"]][$args["id"]] ) : '';
      }

      //$thisValue = isset( $this->items['items'][$args["itemkey"]][$args["id"]] ) ? esc_attr( $this->items['items'][$args["itemkey"]][$args["id"]] ) : '';
    }
    else {
      $thisName = 'style-switcher-options[' . $args["id"] . ']';
      $thisValue = isset( $this->options[$args["id"]] ) ? esc_attr( $this->options[$args["id"]]) : $this->getSetting( $args["id"]);
    }

    switch ( $args["type"] ) {
      case "text":
        $html .= '<input type="text" id="' . $args["id"] . '" name="' . $thisName . '" value="' . $thisValue . '" />';
        if (!empty($args["description"])) {
          $html .= '<div class="wp-studio-manager-admin-description">' . $args["description"] . '</div>';
        }
        break;
      case "checkbox":
        $thisChecked = !empty( $this->options[$args["id"]] ) ? " checked" : "";
        $html .= '<input type="checkbox" id="' . $args["id"] . '" name="' . $thisName . '"' . $thisChecked . '>';
        $html .= '  ' . $args["description"];
        break;
      case "repeat":
        $html .= '<input type="text" name="' . $thisName . '" value="' . $thisValue . '" />   <span class="style-switcher-remove-item"><a href="#" id="style-switcher-remove-item-' . $args["repeatkey"] . '" data-type="option" data-itemid="' . $args["itemkey"] . '" data-field="' . $args["id"] . '">Remove</a></span>';
        break;


    }
    $html .= '</div>';

    if (!empty($args["storeHTML"])) {
      return $html;
    }
    echo $html;

  }

  function prepItemsHTML($arr) {
    $html = '';
    $html .= '<h3>Style Switcher Items</h3>';

    if (!empty($this->items)) {
      foreach($this->items["items"] as $itemKey => $itemArray) {
        $i = 1; //reset for first item in group
        $html .= '<div class="style-switcher-item-section"><h3>Item  <span class="style-switcher-remove-item"><a href="#" id="style-switcher-remove-item-' . $itemKey . '" data-type="item">Remove</a></span></h3>';

        foreach ($arr as $setting => $settingArray) {

          //$settingArray["itemkey"] = $this->itemkey;

          $settingArray["repeatkey"] = 1;
          $settingArray["itemkey"] = $itemKey;

          $settingArray["classes"] = 'switcher-items-field';
          $settingArray["classes"] .= ' switcher-item-order-' . $i;
          $settingArray["storeHTML"] = true;
          $settingID = $setting . '-' . $itemKey;
          $thisClasses = '';
          if ($settingArray["type"] == "repeat") {
            $html .= '<div class="style-switcher-repeat-field-wrapper">';
              $html .= '<h4>' . $settingArray["section-header"] . '</h4>';
            $j = 1;

            foreach ($this->items['items'][$itemKey][$setting]['repeatitems'] as $repeatKey => $repeatArray) {
              $settingArray["repeatkey"] = $j;

              $html .= $this->getItemSettingHTML($settingArray);
              $j++;
            }
            $html .= '<button class="style-switcher-repeat-add" data-key="' . ($j-1) . '">Add Another Option</button>';
            $html .= '</div>';


          }
          else {
              $html .= $this->getItemSettingHTML($settingArray);
          }

          $i++;

        }
        $this->itemKey = $itemKey+1;

        $html .= '</div>';
      }
    }



    $this->itemsHTML = $html;
  }

  public function getItemSettingHTML($settingArray = array()) {
    $html = '';

    $thisData = '';
    if ($settingArray["type"] == "repeat") {
      $thisData = 'data-repeat-number="' . $settingArray["repeatkey"] . '" data-field-key="' . $settingArray["id"] . '"';
    }

    $html .= '<div class="style-switcher-item-field" ' . $thisData . '>';
      $html .= '<div class="style-switcher-item-field-header">';
      $html .= $settingArray["name"];
      $html .= '</div>';
      $html .=  $this->setting_callback($settingArray);
    $html .= '</div>';
    return $html;
  }


  public function getSetting( $setting=false ) {

    if ( !empty($setting) ) {
      $wpssOptions = get_option('style-switcher-options');
      $option = false;
      if (!empty($wpssOptions[ $setting ])) {
          $option = $wpssOptions[ $setting ];
      }


      if ( empty( $option )  ) {
        return $this->settings[$setting]["default"];
      }
      return $option;

    }

  }



  function getSettingsByGroup() {
    $a = array();
    foreach ($this->settings as $key => $arr) {
      $a[$arr["group"]][$key] = $arr;
    }

    $this->settingsGroups = $a;
  }

  function getGroupName( $group ) {
    return 'style-switcher-options-' . $group;
  }

  function print_section_info() {
    return;
  }

  function sanitize( $input ) {
    return $input;
  }

}

$style_switcher_settings = new StyleSwitcherSettings();
