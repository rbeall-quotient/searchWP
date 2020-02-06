<?php
  //edan search shortcode
  add_shortcode( 'edan-search', 'edan_search_shortcode' );

  //facets shortcode
  add_shortcode( 'edan-facets', 'edan_search_facet_shortcode');

  //shortcode for object display
  add_shortcode('edan-object', 'edan_search_object_shortcode');

  /**
   * gather user passed type facet and return shortcode data for appropriate
   * edan search view.
   *
   * Shortcode Parameters:
   *
   * fq: apply a list of facet values to shortcode separated by a double pipe (||)
   * format - fq="type:edanmdm||media_usage:CC0"
   * NOTE: Do not apply quotations to the facet value. do type:edanmdm instead of type:"edanmdm"
   *
   * edanq: apply an edan query value to the search shortcode.
   * format - edanq="Civil War"
   * NOTE: applying an edanQ will disable the searchbar, only allowing results matching the edanQ
   *
   * initialresults: have the search shortcode display initial edan results without having to enter an edan query value
   * format - initialresult="true"
   *
   * restrictsearch: have the search shortcode only display results, page navigation, and facets, omitting the search bar
   * format - restrictsearch="true"
   *
   * justfacets: have the search shortcode only display the facets section and nothing else
   * format - justfacets="true"
   *
   * @param  array $atts array of user passed parameters
   * @return string      html string
   */
  function edan_search_shortcode($atts)
  {
    extract(shortcode_atts(array('type' => 'edanmdm'), $atts));

    $fqs = NULL;
    $edanQ = NULL;
    $ini = False;
    $hidesearch = False;
    $hideresults = False;
    $hidefacets = False;

    if($atts)
    {
      if(array_key_exists('fq', $atts))
      {
        $fqs = explode("||", $atts['fq']);
      }

      if(array_key_exists('initialresults', $atts))
      {
        if($atts['initialresults'] == "true")
        {
          $ini = True;
        }
      }

      if(array_key_exists('hidesearch', $atts))
      {
        if($atts['hidesearch'] == "true")
        {
          $hidesearch = True;
          $ini = True;
        }
      }

      if(array_key_exists('hideresults', $atts))
      {
        if($atts['hideresults'] == "true")
        {
          $hideresults = True;
          $hidesearch = True;
        }
      }

      if(array_key_exists('hidefacets', $atts))
      {
        if($atts['hidefacets'] == "true")
        {
          $hidefacets = True;
        }
      }

      if(array_key_exists('edanq', $atts))
      {
        $edanQ = $atts['edanq'];
        $hidesearch = True;
        $ini = True;
      }
    }

    $view = new edan_search_view_manager();
    return $view->get_content($fqs, $ini, $edanQ, $hidesearch, $hideresults, $hidefacets);
  }

  /**
   * Show EDAN object content
   *
   * Shortcode Parameters:
   *
   * url: Display an object corresponding to a specific edan URL
   * format: url="edanmdm:nmah_1927282"
   * NOTE: If you do not specify the url, you can display an object with the object
   * shortcode by providing an edanUrl query param in the link:
   *
   * http://mywpsite/object-shortcode-page?edanUrl=edanmdm%3Achndm_1938-57-1517
   *
   * specifying an edan URL as a shortcode parameter overrides any applied query var.
   *
   * @return string EDAN Object HTML
   */
  function edan_search_object_shortcode($atts)
  {
    $edanURL = NULL;
    $nosearch = False;

    if($atts)
    {
      if(array_key_exists('url', $atts))
      {
        $edanURL = $atts['url'];
        $nosearch = True;
      }
    }

    $call = new edan_object_call();
    $object = new edan_object_view($call->get($edanURL), $nosearch);

    return $object->get_content();
  }
?>
