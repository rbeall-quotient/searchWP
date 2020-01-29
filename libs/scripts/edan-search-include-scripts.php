<?php
  /**
   * Inlcude JavaScript and CSS
   */

  add_action('admin_init', 'edan_search_include_admin_scripts');
  add_action('init', 'edan_search_include_object_group_scripts');

  /**
   * CSS and JavaScript for Admin Menu
   */
  function edan_search_include_admin_scripts()
  {
    wp_enqueue_style('edan-search-admin-styles', plugin_dir_url(__FILE__) . 'css/edan-search-admin.css');
  }

  /**
   * CSS and JavaScript for Object Group Display
   */
  function edan_search_include_object_group_scripts()
  {
    /*scripts*/
    wp_enqueue_script('edan-search-mini-field.js', plugin_dir_url(__FILE__) . 'js/edan-search-mini-field.js');//mini field javascript
    wp_enqueue_script('edan-search-facets-list.js', plugin_dir_url(__FILE__) . 'js/edan-search-facets-list.js');//facet list javascript
    wp_enqueue_script('edan-search.js', plugin_dir_url(__FILE__) . 'js/edan-search.js');
    wp_enqueue_script('ids-link-manager.js', plugin_dir_url(__FILE__) . 'js/ids-link-manager.js');

    /*styles*/
    wp_enqueue_style('edan-search-object-display.css', plugin_dir_url(__FILE__) . 'css/edan-search-object-display.css');//css for object display
    wp_enqueue_style('edan-search-navbar.css', plugin_dir_url(__FILE__) . 'css/edan-search-navbar.css');//css for object navbar
  }
?>
