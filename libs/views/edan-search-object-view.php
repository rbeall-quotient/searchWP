<?php
  /**
   * View class for displaying a standalone object on a page
   */
  class edan_object_view
  {
    /**
     * initialize options handler and store edan object json
     *
     * @param array $cache set of edan json
     */
    function __construct($cache)
    {
      $this->options = new esw_options_handler();
      $this->object = $cache['object'];
      $this->search = new edan_search_view($cache);
    }

    /**
     * Get html for EDAN object
     *
     * @param  object $row row of decoded json data for a particular object
     * @return string html string for object data
     */
    function get_content()
    {
      $content = '';

      if($this->object && property_exists($this->object->{'content'}, 'descriptiveNonRepeating'))
      {
        $content .= $this->search->get_search_bar();
        $content .= '<div class="obj-header">';

        if(property_exists($this->object->{'content'}->{'descriptiveNonRepeating'}, 'online_media'))
        {
          console_log("JSON object: " . json_encode($this->object));
          $src = $this->object->{'content'}->{'descriptiveNonRepeating'}->{'online_media'}->{'media'}[0]->{'content'};
          //$content .= "<img src=\"$src\" />";
          $content .= "<br/><div style=\"border-style:solid;border-color:black\"><iframe src=\"$src" . "&container.fullpage&inline=true\" width=\"1500\" height=\"750\"></iframe>";
          $content .= "<a href=\"$src\" target=\"_blank\">View Full Sized Image</a></div>";
        }

        $content .= '<hr/></div>';
        $content .= $this->get_fields();
      }

      return $content;
    }

    /**
     * Generates and returns HTML for object field labels and values
     *
     * @return string html of object field data
     */
    function get_fields()
    {
      $content = '';

      if(property_exists($this->object->{'content'}, 'freetext'))
      {
        $labels = $this->compile_field_values($this->object->{'content'}->{'freetext'});

        $content .= "<div>";

        foreach($labels as $key => $vals)
        {
            $content .= '<div><strong>'. $this->options->replace_label($key) . '</strong></div>';

            foreach($vals as $txt)
            {
              $content .= '<div>' . $txt . '</div>';
            }

            $content .= '<br/>';
        }

        $content .= "</div>";
      }

      return $content;
    }

    /**
     * compile a two dimensional array that stores all lines offield text as
     * separate array entries under their corresponding label.
     * @return [type] [description]
     */
    function compile_field_values()
    {
      $freetext = $this->object->{'content'}->{'freetext'};

      $display = array();

      foreach($freetext as $key => $val)
      {
        //$display[$key] = array();

        foreach($val as $set)
        {
          if(!array_key_exists($set->{'label'}, $display))
          {
            $display[$set->{'label'}] = array();
          }

          array_push($display[$set->{'label'}], $set->{'content'});
        }
      }

      return $display;
    }
  }
?>
