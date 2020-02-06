<?php
  /**
   * Options handler class processes and returns specific options data,
   * formatting it to be useful if need be.
   */
  class esw_options_handler
  {
    /**
     * Constructor for options_handler
     *
     * @param array  $options array of admin settings
     * @param boolean $facets selector for initializing facets arrays
     */
    function __construct()
    {
      $this->options = get_option('edan_search_settings');

      $this->edanserver = $this->options['edanserver'];
      $this->appid = $this->options['appid'];
      $this->authkey = $this->options['authkey'];
      $this->tiertype = $this->options['tiertype'];
      $this->fnames  = $this->options['fnames'];
      $this->hfacets = $this->options['hfacets'];
      $this->fields  = $this->options['fields'];
      $this->labels  = $this->options['labels'];
      $this->mini    = $this->options['mini'];
    }

    /**
     * Get site creds
     *
     * @return string edan server
     */
    function get_edan_server()
    {
      return trim($this->edanserver);
    }

    /**
     * Get site creds
     *
     * @return string app id
     */
    function get_app_id()
    {
      return trim($this->appid);
    }

    /**
     * Get auth key
     *
     * @return string auth key
     */
    function get_auth_key()
    {
      return trim($this->authkey);
    }

    /**
     * Get tier type
     *
     * @return string tiertype
     */
    function get_tier_type()
    {
      return trim($this->tiertype);
    }

    /**
     * Get path to page used for rendering object groups
     *
     * @return string path to object group page
     */
    function get_path()
    {
      return $this->options['path'];
    }

    /**
     * Get title for page listing object groups
     *
     * @return string Title for object group page
     */
    function get_title()
    {
      return $this->options['title'];
    }

    /**
     * Get message above selected facets
     *
     * @return string message for removal of selected facets
     */
    function get_remove_message()
    {
      return $this->options['remove'];
    }

    /**
     * Get number of rows returned for search
     *
     * @return int number of objects to return
     */
    function get_rows()
    {
      return $this->options['rows'];
    }

    /**
     * Test for minimizing
     *
     * @return boolean true if yes, false if not
     */
    function is_minimized()
    {
      if($this->options['mini'] == "")
      {
        return false;
      }

      return true;
    }

    /**
     * Get the results message to display above search results with values
     * put in place of tokens
     *
     * @param  int $count Number of items
     * @return string Formatted Results Message.
     */
    function get_results_message($count)
    {
      $message = $this->options['resultsmessage'];
      $message = str_replace('@count', $count, $message);

      return $message;
    }

    function get_no_results_message()
    {
      return $this->options['noresults'];
    }

    /**
     * If replacement for facet exists, return it for display
     *
     * @param  string $facet original facet name
     * @return string replacement facet name or original if no replacement exists
     */
    function replace_facet($facet)
    {
      if(!$this->fnames || !array_key_exists($facet, $this->fnames))
      {
        return $facet;
      }
      else
      {
        return $this->fnames[$facet];
      }
    }

    /**
     * Check if label has a replacement
     *
     * Note: flatten $label to lowercase (replacements are case insensitive)
     *
     * @param  string $label object field label
     * @return string        replacement label
     */
    function replace_label($label)
    {
      if(!$this->labels || !array_key_exists(strtolower($label), $this->labels))
      {
        return $label;
      }
      else
      {
        return $this->labels[strtolower($label)];
      }
    }

    /**
     * Check if the facet should be ignored, tracking against the list of
     * facets to ignore.
     *
     * @param  string $facet facet value to check
     * @return string        false to ignore facet, true to show it
     */
    function ignore_facet($facet)
    {
      if($this->hfacets && in_array($facet, $this->hfacets))
      {
        return false;
      }
      else
      {
        return true;
      }
    }

    /**
     * Parse through object fields and append to an array
     *
     * @param  object $freetext object of ordered object fields and field values
     * @return array           array of parsed field values
     */
    function get_display_data($freetext)
    {
      $display  = array();

      if($this->fields != NULL && count($this->fields) >= 0 && $this->fields[0] != '')
      {
        $show_all = false;

        if(in_array('*', $this->fields))
        {
          $show_all = true;
        }

        foreach($this->fields as $f)
        {
          if($f != '*' && property_exists($freetext, $f))
          {
            $display[$f] = array();

            foreach($freetext->{$f} as $set)
            {
              if(!array_key_exists($set->{'label'}, $display))
              {
                $display[$f][$set->label] = array();
              }

              array_push($display[$f][$set->{'label'}], $set->{'content'});
            }

            unset($freetext->{$f});
          }
        }

        if($show_all)
        {
          foreach($freetext as $key => $val)
          {
            $display[$key] = array();

            foreach($val as $set)
            {
              if(!array_key_exists($set->{'label'}, $display[$key]))
              {
                $display[$key][$set->label] = array();
              }

              array_push($display[$key][$set->{'label'}], $set->{'content'});
            }
          }
        }
      }

      return $display;
    }

    /**
     * Test if field should be displayed or not.
     *
     * @param  string $field Name of field
     * @return boolean        False if field is a mini-field, True if not
     */
    function get_mini($field)
    {
      if(in_array($field, $this->mini))
      {
        return false;
      }

      return true;
    }
  }
?>
