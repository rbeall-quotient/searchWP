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
    $cssdir = 'wp-content/plugins/edanSearchWP/libs/scripts/css';
    $jsdir = 'wp-content/plugins/edanSearchWP/libs/scripts/js';

    $css_files = edan_search_get_directory_files($cssdir, ".css");
    $js_files = edan_search_get_directory_files($jsdir, ".js");

    foreach($css_files as $f)
    {
      wp_enqueue_style($f, plugin_dir_url(__FILE__) . "css/$f");
    }

    foreach($js_files as $f)
    {
      wp_enqueue_script($f, plugin_dir_url(__FILE__) . "js/$f");
    }

    /*scripts*/
    /*wp_enqueue_script('edan-search-mini-field.js', plugin_dir_url(__FILE__) . 'js/edan-search-mini-field.js');//mini field javascript
    wp_enqueue_script('edan-search-facets-list.js', plugin_dir_url(__FILE__) . 'js/edan-search-facets-list.js');//facet list javascript
    wp_enqueue_script('edan-search.js', plugin_dir_url(__FILE__) . 'js/edan-search.js');
    wp_enqueue_script('ids-link-manager.js', plugin_dir_url(__FILE__) . 'js/ids-link-manager.js');*/

    /*styles*/
    /*wp_enqueue_style('edan-search-object-display.css', plugin_dir_url(__FILE__) . 'css/edan-search-object-display.css');//css for object display
    wp_enqueue_style('edan-search-navbar.css', plugin_dir_url(__FILE__) . 'css/edan-search-navbar.css');//css for object navbar*/
  }

  function edan_search_get_directory_files($path, $ext)
  {
    $files = array();

    if ($dir = opendir($path))
    {
      while (false !== ($entry = readdir($dir)))
      {
        console_log($entry);
        if ($entry != "." && $entry != ".." && strpos($entry, $ext) !== false)
        {
          //console_log($entry);
          array_push($files, $entry);
        }
      }
      closedir($handle);
    }
    else
    {
      console_log("Failed to open directory");
      console_log("pluging dir url: " . plugin_dir_url(__FILE__));
    }

    return $files;
  }
?>
