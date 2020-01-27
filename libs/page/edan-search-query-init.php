<?php
  /**
   * Initialize query data and variables
   */

  //register edan search query vars.
  add_action('init', 'edan_search_add_tags');

  /**
  * Callback for adding custom query variables corresponding to
  * EDAN call.
  */
  function edan_search_add_tags()
  {
    //EDAN search term
    add_rewrite_tag("%edan_q%", '(.*)');

    //EDAN Query Fqs
    add_rewrite_tag('%edan_fq%', '(.*)');

    //Object EDAN Url
    add_rewrite_tag('%edanUrl%', '(.*)');

    //Object Listing Page Index
    add_rewrite_tag('%listStart%', '(.*)');

    //Display Result JSON Instead
    add_rewrite_tag('%jsonDump%', '(.*)');
  }
?>
