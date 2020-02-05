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
  }

  function edan_search_get_directory_files($path, $ext)
  {
    $files = array();

    if ($dir = opendir($path))
    {
      while (false !== ($entry = readdir($dir)))
      {
        if ($entry != "." && $entry != ".." && strpos($entry, $ext) !== false)
        {
          array_push($files, $entry);
        }
      }
      closedir($handle);
    }

    return $files;
  }
?>
