<?php
  /**
   * depending on the passed query vars, return specific edan search json
   *
   * edanUrl: return edan object json
   * default: return edan search json
   */
  class esw_cache_handler
  {
    /**
     * return array of edan json objects
     * @return array set of edan json
     */
    function get()
    {
      if(get_query_var('edanUrl'))
      {
        $object_call = new edan_object_call();
        return $object_call->get();
      }
      else
      {
        $search_call = new edan_search_call();
        return $search_call->get();
      }
    }
  }
?>
