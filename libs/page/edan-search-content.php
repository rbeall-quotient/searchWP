<?php
  /**
   * Filter page content if stripped down URL matches designated EDAN Search page.
   */

  //Filter page content
  add_filter( 'the_content', 'edan_search_insert_content');

  /**
  * Callback function for inserting EDAN content into
  * EDAN Search page
  */
  function edan_search_insert_content( $content )
  {
    //get options from admin menu and plug them into the options handler
    $options = new esw_options_handler();

    /*Using stripped down url instead of page title because we
    * we are changing the title and this title filter might be called before
    * we access content.
    */
    if(edan_search_name_from_url() == $options->get_path())
    {
      $view = new edan_search_view_manager();
      $content = $view->get_content();
    }

    return $content;
  }
?>
