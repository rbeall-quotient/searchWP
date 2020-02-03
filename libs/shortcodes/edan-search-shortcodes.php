<?php
  //edan search shortcode
  add_shortcode( 'edan-search', 'edan_search_shortcode' );

  //facets shortcode
  add_shortcode( 'edan-facets', 'edan_search_facet_shortcode');

  //shortcode for object display
  add_shortcode('edan-object', 'edan_search_object_shortcode');

  //shortcode for enhanced edan search
  add_shortcode('edan-search-enhanced', 'edan_search_enhanced');

  /**
   * gather user passed type facet and return shortcode data for appropriate
   * edan search view.
   *
   * @param  array $atts array of user passed parameters
   * @return string      html string
   */
  function edan_search_shortcode($atts)
  {
  extract(shortcode_atts(array(
      'type' => 'edanmdm',
    ), $atts));

    $call = new edan_search_call();
    $view = new edan_search_view($call->get(process_type_fqs($type)));
    //$view = new edan_search_view($call->get());

    return $view->get_content();
  }

  function edan_search_enhanced($atts)
  {
    extract(shortcode_atts(array('type' => 'edanmdm'), $atts));
    if(array_key_exists('fq', $atts))
    {
      $fqs = explode(",", $atts['fq']);
    }
    $view = new edan_search_view_manager();
    return $view->get_content();
  }

  /**
   * show facets menu as a shortcode
   *
   * @return string facets menu html
   */
  function edan_search_facet_shortcode()
  {
    $call = new edan_search_call();
    $facet = new edan_facet_view($call->get()['search']);

    return $facet->get_content();
  }

  /**
   * Show EDAN object content
   *
   * @return string EDAN Object HTML
   */
  function edan_search_object_shortcode()
  {
    $call = new edan_object_call();
    $object = new edan_object_view($call->get());

    return $object->get_content();
  }

  /**
   * Process type fq passed by user
   *
   * @param  string $str string of user passed type fq
   * @return string      json encoded array of fqs
   */
  function process_type_fqs($str)
  {
    $str = trim($str);
    $fqs  = array();

    array_push($fqs, "type:\"$str\"");

    return json_encode($fqs);
  }
?>
