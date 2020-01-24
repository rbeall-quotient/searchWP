<?php
  class edan_object_call
  {
    /**
     * initialize edan handler and set service
     */
    function __construct()
    {
      $this->edan = new edan_handler();
      $this->service = 'content/v1.1/content/getContent.htm';
    }

    /**
     * Get object JSON Data
     *
     * @return array object json
     */
    function get()
    {
      $results = array();

      //if edan search data is already cached, return cached value
      if(wp_cache_get('edan_search_cache'))
      {
        $cache = wp_cache_get('edan_search_cache');

        if(array_key_exists('object', $cache))
        {
          $object = $cache['object'];

          if($object)
          {
            $results['object'] = $object;

            return $results;
          }
        }
      }

      $obj_vars = array(
        'url' => get_query_var('edanUrl'),
      );

      $results['object'] = json_decode($this->edan->edan_call($obj_vars, $this->service));
      $results['search'] = false;

      wp_cache_set('edan_search_cache', $results);

      return $results;
    }
  }
?>
