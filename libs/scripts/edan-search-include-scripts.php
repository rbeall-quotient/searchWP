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
    wp_enqueue_style('edan-search-admin-styles', plugin_dir_url(__FILE__) . 'admin-css/edan-search-admin.css');
  }

  /**
   * CSS and JavaScript for Object Group Display
   */
  function edan_search_include_object_group_scripts()
  {
    wp_enqueue_style("edan-search.css", plugin_dir_url(__FILE__) . "css/edan-search.css");
    wp_enqueue_script("edan-search.js", plugin_dir_url(__FILE__) . "js/edan-search.js");
  }
?>
