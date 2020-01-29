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
          $media =  $this->get_media_content($this->{'object'}->{'content'}->{'descriptiveNonRepeating'});
          $content .= $media['content'];
          $nonImageMedia .= $media['nonImages'];

          $content .= '<div id="edan-search-object-media-section">';
          $src = $this->object->{'content'}->{'descriptiveNonRepeating'}->{'online_media'}->{'media'}[0]->{'content'};

          if(strpos($src, "ids.si.edu") !== false)
          {
            $src = str_replace("deliveryService", "dynamic", $src);
            $content .= "<iframe id = \"edan-search-object-idsframe\" src=\"$src" . "&container.fullpage&inline=true\" width=\"1500\" height=\"750\"></iframe>";
          }
          else
          {
            $content .= "<img src=\"$src\" />";
          }

          $content .= '</div>';
          //$content .= "<img src=\"$src\" />";
          //$content .= "<br/><div style=\"border-style:solid;border-color:black\"><iframe id = \"edan-search-object-idsframe\" src=\"$src" . "&container.fullpage&inline=true\" width=\"1500\" height=\"750\"></iframe>";
          $content .= "<a href=\"$src\" target=\"_blank\">View Full Sized Image</a></div>";
        }

        $content .= '<hr/></div>';
        $content .= $this->get_fields();
      }

      return $content;
    }

    function get_media_content($object)
    {
      $res = [
        "content" => "<div>",
        "nonImages" => []
      ];

      if(property_exists($object, 'online_media'))
      {
        $onlineMedia = $object->{'online_media'};
        $mediaCount = $onlineMedia->{'mediaCount'};

        $media = $onlineMedia->{'media'};
        $index = 0;
        $imageExists = false;

        foreach($media as $m)
        {
          if(property_exists($m, 'type') && $m->{'type'} == "Images")
          {
            $index++;
            $imageExists = true;
            if($index == 1)
            {
              $disp = "display:block";
            }
            else
            {
              $disp = "display:none";
            }

            $res["content"] .= "<div id=\"displayMedia$index\" style=\"$disp\">";

            if(strpos($m->{'content'}, 'ids.si.edu') != false)
            {
              $src = str_replace('deliveryService', 'dynamic', $m->{'content'});
              $res['content'] .= "<iframe src=\"$src\" width=\"1500\" height=\"750\"></iframe>";
            }
            else
            {
              $res['content'] .= "<img src=\"" . $m->{'content'} . "\" />";
            }

            $res["content"] .= "</div>";
          }
          else
          {
            array_push($res["nonImages"], $m);
          }
        }

        if($imageExists)
        {
          $res['content'] .= "<input type=\"hidden\" id=\"visualMediaCount\" value=\"$index\"></input>";
          $res['content'] .= "<input type=\"hidden\" id=\"visualMediaIndex\" value=\"1\"></input>";
          if($index > 1)
          {
            $res['content'] .= "<div><a id=\"mediaPrev\" href=\"#\" onclick=\"mediaPrevious()\" style=\"display:none\">previous</a><span><span id=\"mediaIndex\">1</span>/$mediaCount</span><a id=\"mediaNext\" href=\"#\" onclick=\"mediaNext()\">Next</a>";
          }
        }
      }

      $res["content"] .= "</div>";
      return $res;
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

        $content .= "<div class=\"edan-search-object-view-fields\">";

        foreach($labels as $key => $vals)
        {
            $content .= '<div class="edan-search-object-view-field-label edan-search-object-view-field-label.' . $key .'">'. $this->options->replace_label($key) . '</div>';

            foreach($vals as $txt)
            {
              $content .= '<div class="edan-search-object-view-field-content edan-search-object-view-field-label.' . $key . '">' . $txt . '</div>';
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
