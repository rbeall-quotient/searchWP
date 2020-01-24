<?php
  /**
   * class handling calls to edan for edan search json
   */
  class edan_search_call
  {
    /**
     * initialize options and edan handler and set service
     */
    function __construct()
    {
      $this->options = new esw_options_handler();
      $this->edan = new edan_handler();

      $this->service = 'metadata/v1.1/metadata/search.htm';
    }

    /**
     * Function to retrieve objectGroup.
     * @return array array containing object group json or false on failure
     */
    function get($fqs=NULL)
    {
      //if edan search data is already cached, return cached value
      if(wp_cache_get('edan_search_cache'))
      {
        $cache = wp_cache_get('edan_search_cache');

        if(array_key_exists('search', $cache))
        {
          $search = $cache['search'];

          if($search)
          {
            $results['search'] = $search;

            return $results;
          }
        }
      }

      $vars = array
      (
        'rows' => $this->options->get_rows(),//num of rows
        'facet' => 'true',//show facets
      );

      if($fqs)
      {
        $vars['fqs'] = $fqs;
      }

      if(get_query_var('edan_q'))
      {
        $vars['q'] = get_query_var('edan_q');
      }

      if(get_query_var('listStart'))
      {
        $start = get_query_var('listStart') * 10;
      }
      else
      {
        $start = 0;
      }

      if(get_query_var('listStart'))
      {
        $vars['start'] = $start;
      }

      $results['search'] = json_decode($this->edan->edan_call($vars, $this->service, 1));

      wp_cache_set('edan_search_cache', $results);

      return $results;
    }
  }
?>
