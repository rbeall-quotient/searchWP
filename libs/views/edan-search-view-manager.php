<?php
  /**
   * Class that serves content views for edan search
   */
  class edan_search_view_manager
  {
    /**
     * Retrieve the proper view based on passed query vars
     *
     * if jsonDump passed, print JSON to the page.
     *
     * @return string edan search content
     */
    function get_content($fqs = NULL, $ini = False, $edanQ = NULL, $hidesearch = False, $hideresults = False, $hidefacets = False)
    {
      //check if jsonDump set
      if(get_query_var('jsonDump'))
      {
        $view = new esw_json_view();
        return $view->display_json();
      }
      else
      {
        $call = new esw_cache_handler();

        if(get_query_var('edanUrl'))
        {
          $view = new edan_object_view($call->get());
        }
        else
        {
          //otherwise, serve search bar and results (if applicable)
          $view = new edan_search_view($call->get($fqs, $edanQ), $ini, $hidesearch, $hideresults, $hidefacets);
        }

        //serve up gathered content
        return $view->get_content();
      }
    }
  }
?>
