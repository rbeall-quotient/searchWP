<?php
  /**
   * Show facets menu
   */
  class edan_facet_view
  {
    /**
     * Initialize handlers and validate facets.
     * Gather number of objects retrieved from passed json.
     *
     * @param array $cache set of edan search or edan search json objects
     */
    function __construct($cache)
    {
      $this->url_handler = new esw_url_handler();
      $this->options = new esw_options_handler();
      $this->facets = NULL;
      $this->numfound = 0;

      $results = $cache;

      if($results && property_exists($results, 'facets'))
      {
        $this->numfound = $results->{'numFound'};
        $this->facets = $results->{'facets'};
      }
    }

    /**
     * Get menu of facets to filter object search
     *
     * @return string html string of facet menu
     */
    function get_content()
    {
      $content = "";

      if($this->facets && $this->numfound > 0)
      {
        $edan_fqs = get_query_var('edan_fq');

        if($edan_fqs)
        {
          $content .= '<h4>' . $this->options->get_remove_message() . '</h4>';
          $content .= '<ul style="list-style:none;">';

          foreach($edan_fqs as $fq)
          {
            $content .= '<li><a href="' . $this->url_handler->remove_facet_url($fq) . '">[X]' . $fq . '</a></li>';
          }

          $content .= '</ul>';
        }

        $content .= '<h3>Filter Your Results</h3>';
        $content .= '<ul style="list-style:none;">';

        foreach($this->facets as $key => $val)
        {
          if(count($val) != 0 && $this->options->ignore_facet($key))
          {
            $content .= '<li>';
            $content .= '<a href="#/" onclick="toggle_facet_view(' . "'$key'" . ')" id = "' . $key . '-link">&#9658;' . $this->options->replace_facet($key) . '</a>';
            $content .= $this->get_facet($key, $val);
            $content .= '</li>';
          }
        }

        $content .= '</ul>';

        return $content;
      }

    }

    /**
     * Get html string of specific facet
     *
     * @param  string $key   category of filter
     * @param  string $facet filter to retrieve link for
     *
     * @return string html for a specific filter
     */
    function get_facet($key, $facet)
    {
      $content = '<ul id="facet-'. $key.'" style="list-style:none; display: none;">';

      foreach($facet as $vals)
      {
        if($vals[0] != "")
        {
          $content .= '<span><div>';
          $content .= '<a href="' . $this->url_handler->add_facet_url($key, $vals[0]) . '">' . $vals[0] . '</a>   ';
          $content .= $vals[1]; ' </div>';
          $content .= '</span>';
        }
      }

      $content .= '</ul>';

      return $content;
    }
  }
?>
