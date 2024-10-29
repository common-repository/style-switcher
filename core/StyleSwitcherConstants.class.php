<?php
namespace StyleSwitcher;

class StyleSwitcherConstants {

  const SETTINGS = array(
    "show-switcher-options" => array(
      "id" => "show-switcher-options",
      "default" => false,
      "type" => "checkbox",
      "group" => "general",
      "name" => "Show Style Switcher Options",
      "description" => "Check if you want the options to show up on all pages - leave unchecked when not in active development"

    ),
    "switcher-item-handle" => array(
      "id" => "switcher-item-handle",
      "type" => "text",
      "group" => "items",
      "name" => "Class or ID",
      "default" => ''

    ),
    "switcher-item-property" => array(
      "id" => "switcher-item-property",
      "type" => "text",
      "group" => "items",
      "name" => "CSS Property",
      "default" => ''

    ),
    "switcher-item-option" => array(
      "id" => "switcher-item-option",
      "type" => "repeat",
      "group" => "items",
      "section-header" => "Options",
      "name" => "Option",
      "default" => ''
    )
  );

  const SETTINGS_GROUPS = array(
    "general" => array(
      "name" => "General"
    ),
    "items" => array(
      "name" => "Switcher Items"
    )
  );


  function __construct() {

  }

}
