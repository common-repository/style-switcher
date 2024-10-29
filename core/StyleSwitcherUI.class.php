<?php
namespace StyleSwitcher;

class StyleSwitcherUI {

  public $settings;
  public $items;

  public function __construct() {
    $this->settings = get_option('style-switcher-options');
    $this->items = get_option('style-switcher-items-options');

    if ($this->settings["show-switcher-options"] == 'on') {

    }
  }

  public function getJS() {
    $html = '';
    $html .= '<script>';
      $html .= 'jQuery(function() {';
        $html .= "switcherObject = [];\n\t";

        foreach ($this->items["items"] as $key => $item) {
          $handle = str_replace('"', '', $item["switcher-item-handle"]);
          $handle = str_replace("'", "", $handle);
          $property = str_replace('"', '', $item["switcher-item-property"]);
          $property = str_replace("'", "", $property);
          $html .= "switcherObject[" . $key . "] = [];\n\t";
          $html .= "switcherObject[" . $key . "]['handle'] = '" . $handle . "';\n\t";
          $html .= "switcherObject[" . $key . "]['property'] = '" . $property . "';\n\t";
        }
        $html .= "jQuery(document).on('submit', '.style-switcher-form', function(e) {\n\t";
          $html .= "e.preventDefault();\n\t";
          $html .= "var thiskey = jQuery(this).attr('id').replace('style-switcher-form-', '');\n\t";
          $html .= "var handle = switcherObject[thiskey]['handle'];\n\t";
          $html .= "var property = switcherObject[thiskey]['property'];\n\t ";
          $html .= "var s = jQuery(this).find('select');\n\t";
          $html .= "var thisval = s.val();\n\t";

          $html .= "jQuery(handle).css(property, thisval);\n\t";

        $html .= "});";



      $html .= '});';
    $html .= '</script>';

    return $html;
  }

  public function getHTML() {
    $html = '';
    $html .= '<div class="style-switcher-controls">';
      $html .= '<div class="style-switcher-open"><a href="#"><span id="style-switcher-toggle-text">Show</span> Style Switching Options</a></div>';
      $html .= '<div class="style-switcher-content">';

      foreach ($this->items["items"] as $key => $item) {
        $html .= '<div class="style-switcher-item">';
        $html .= 'Change the ' . $item["switcher-item-property"] . ' for ' . $item["switcher-item-handle"];
        $html .= '<form class="style-switcher-form" id="style-switcher-form-' . $key . '">';

          $html .= '<select>';
            $html .= '<option value="--NONE--">Select One';

            foreach ($item["switcher-item-option"]["repeatitems"] as $k => $option) {
              if ($option != '') {
                $html .= '<option value="' . $option . '">' . $option;
              }

            }
            $html .= '<input type="submit" value="change" />';
            $html .= '</select>';
        $html .= '</form>';
        $html .= '</div>';
      }

      $html .= '</div>';
    $html .= '</div>';

    return $html;
  }

  public function printALL() {

    echo $this->getHTML();
    echo $this->getJS();
  }

}
