<?php
  /**
   * Simple functions for testing, debugging, and other simple,
   * modularized tasks
   */

  /**
   * function for logging php strings to browser console for testing and
   * debugging
   * @param  string $content content to be logged
   */
  function console_log($content)
  {
    echo '<script>console.log("'.$content.'");</script>';
  }

  /**
   * function for logging php strings to browser console for error messages
   * @param  string $content error to be logged
   */
  function console_error($err)
  {
    echo '<script>console.error("'.$err.'");</script>';
  }

  /**
  * Return url stripped of query vars and '/' and '?' characters.
  * This will correspond to the EDAN object groups call.
  *
  * Adapted from: https://roots.io/routing-wp-requests/
  *
  * @return string page url without query variables
  */
  function edan_search_name_from_url()
  {
    $url = trim(esc_url_raw(add_query_arg([])), '/');
    $home_path = trim(parse_url(home_url(), PHP_URL_PATH), '/');

    if ($home_path && strpos($url, $home_path) === 0)
    {
      $url = trim(substr($url, strlen($home_path)), '/');
    }

    $url = str_replace('index.php/', '', $url);

    return trim(explode('?', $url, 2)[0], '/');
  }
?>
