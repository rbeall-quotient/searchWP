<?php
  /**
   * Register EDAN Search settings link under the plugin listing
   */

  //add edan search admin settings link
  add_filter( 'plugin_action_links_edan-search-wp/edan-search-wp.php', 'edan_search_add_action_link' );

  /**
   * Add a settings link on the plugin page for EDAN Search linking to settings page
   *
   * @param array $links array of action links with new link appended.
   * @return array merged list of links
   */
  function edan_search_add_action_link( $links )
  {
    $settings_link = array(
      '<a href="' . admin_url( 'admin.php?page=edan-search-settings') . '">' . __('Settings', 'edan-search-settings') . '</a>',
    );

    return array_merge( $links, $settings_link);
  }
?>
