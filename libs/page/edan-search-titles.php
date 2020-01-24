<?php
  /**
   * Filter the title of the page based on EDAN Search data.
   */

  //Modify document title
  add_filter('pre_get_document_title', 'edan_search_set_doc_title');

  //Modify page title
  add_filter( 'the_title', 'edan_search_set_title', 10);

  /**
   * Modify title to match EDAN Search or Object information
   *
   * @param string $title title for display
   */
  function edan_search_set_title( $title )
  {
    $options = new esw_options_handler();
    $cache   = new esw_cache_handler();

    /**
     * if in the loop and the title is cached (or if object group is retrieved successfully)
     * modify the page title on display.
     */
    if(in_the_loop() && edan_search_name_from_url() == $options->get_path() && $options->get_title() != '')
    {
      if(get_query_var('edanUrl'))
      {
        $object = $cache->get()['object'];

        if($object)
        {
          if(property_exists($object, 'content') && property_exists($object->{'content'}, 'descriptiveNonRepeating'))
          {
            if(property_exists($object->{'content'}->{'descriptiveNonRepeating'}, 'title'))
            {
              $title = $object->{'content'}->{'descriptiveNonRepeating'}->{'title'}->{'content'};
            }
          }
          elseif(property_exists($object, 'title'))
          {
            if(property_exists($object->{'title'}, 'content'))
            {
              $title = $this->object->{'title'}->{'content'};
            }
            else
            {
              $title = $this->object->{'title'};
            }
          }
        }
      }
      else
      {
        $title = $options->get_title();
      }
    }

    return $title;
  }

  /**
   * Modify title to match ObjectGroup information
   *
   * Note: used for both doc title and display title
   *
   * @param string $title title for display
   */
  function edan_search_set_doc_title( $title )
  {
    $options = new esw_options_handler();
    $cache   = new esw_cache_handler();

    if(edan_search_name_from_url() == $options->get_path() && $options->get_title() != '')
    {
      if(get_query_var('edanUrl'))
      {
        $object = $cache->get()['object'];

        if($object)
        {
          if(property_exists($object, 'content') && property_exists($object->{'content'}, 'descriptiveNonRepeating'))
          {
            if(property_exists($object->{'content'}->{'descriptiveNonRepeating'}, 'title'))
            {
              $title = $object->{'content'}->{'descriptiveNonRepeating'}->{'title'}->{'content'};
            }
          }
          elseif(property_exists($object, 'title'))
          {
            if(property_exists($object->{'title'}, 'content'))
            {
              $title = $this->object->{'title'}->{'content'};
            }
            else
            {
              $title = $this->object->{'title'};
            }
          }
        }
      }
      else
      {
        $title = $options->get_title();
      }
    }

    $sitename = get_bloginfo('name');
    return $title . " - $sitename";
  }

?>
