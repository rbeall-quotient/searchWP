<?php
  /**
   * Class for serving up urls based on query vars
   */
  class esw_url_handler
  {
    /**
     * Get the base url of the page
     *
     * @return string edan search page url
     */
    function get_url()
    {
      $url = trim(esc_url_raw(add_query_arg([])), '/');
      $url = explode('?', $url, 2)[0];
      return $url;
    }

    /**
     * Validate query vars that are non-array values
     *
     * Note: all params besides edan fqs
     * @param  array $q_vars array of query vars
     * @return string query string
     */
    function validate_single_vars($q_vars)
    {
      $query = '?';
      $count  = 0;

      foreach($q_vars as $key => $val)
      {
        if($count > 0)
        {
          $query .= "&";
        }

        if($val)
        {
          $val = urlencode($val);
          $query .= "$key=$val";
        }

        $count++;
      }

      return $query;
    }

    /**
     * Gather edan_fq query vars array and process as query string
     * @param  array  $fqs   array of edan_fq vars
     * @param  boolean $facet name of facet to exclude or false if not necessary
     * @return string  query string of $edan_fq vars
     */
    function validate_fqs($fqs, $facet=false)
    {
      if(!$fqs)
      {
        return "";
      }

      $query = '';

      foreach($fqs as $fq)
      {
        if($fq != $facet)
        {
          $query .= '&edan_fq[]=' . $fq;
        }
      }

      return $query;
    }

    /**
     * Append the listStart var to the query
     *
     * Note: Used for getting links to specific pages of objects in bottom view
     *
     * @param  string $listStart page number for list of objects
     * @return string Url for list items
     */
    function list_url($listStart)
    {
      //$url  = $this->get_url();
      $url = "";
      $vars = array(
        'objectGroupUrl' => get_query_var('objectGroupUrl'),
        'pageUrl' => get_query_var('pageUrl'),
        'edan_q' => get_query_var('edan_q'),
        'listStart' => $listStart
      );

      $url .= $this->validate_single_vars($vars);
      $url .= $this->validate_fqs(get_query_var('edan_fq'));
      return $url;
    }

    /**
     * Url for a specific facet filter
     *
     * @param string $key    Name of facet category
     * @param string $filter Name of filter
     * @return string url string with fqs attached
     */
    function add_facet_url($key, $filter)
    {
      //$url  = $this->get_url();
      $url = "";
      $vars = array(
        'objectGroupUrl' => get_query_var('objectGroupUrl'),
        'pageUrl' => get_query_var('pageUrl'),
        'edan_q' => get_query_var('edan_q')
      );

      $url .= $this->validate_single_vars($vars);
      $url .= $this->validate_fqs(get_query_var('edan_fq'));
      $url .= '&edan_fq[]=' . $key . ':' . $filter;

      return $url;
    }

    /**
     * Remove a specific fqs variable from url
     * @param  string $facet facet to remove
     * @return string url string without passed facet
     */
    function remove_facet_url($facet)
    {
      //$url  = $this->get_url();
      $url = "";
      $vars = array(
        'objectGroupUrl' => get_query_var('objectGroupUrl'),
        'pageUrl' => get_query_var('pageUrl'),
        'edan_q' => get_query_var('edan_q')
      );

      $url .= $this->validate_single_vars($vars);
      $url .= $this->validate_fqs(get_query_var('edan_fq'), $facet);

      return $url;
    }

    /**
     * Generate link to object
     *
     * @param  string $edanUrl url for EDAN object
     * @return string          url with edanUrl appended
     */
    function get_object_url($edanUrl)
    {
      $options = new esw_options_handler();

      $url = "";
      $vars = array(
        'edanUrl' => $edanUrl,
      );

      $url .= $this->validate_single_vars($vars);

      return $url;
    }
  }
?>
