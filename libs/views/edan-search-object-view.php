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
      //return "<div>hello world</div>";
      $content = '';
      $content .= $this->search->get_search_bar();

      try
      {
        if(!$this->{'object'})
        {
          return $content;
        }
        elseif(property_exists($this->object, 'Collection_Title'))
        {
          return $this->get_collection_object();
        }

        $content .= '<div class="edan-search-obj-header">';

        $media =  $this->get_media_content($this->object);
        $content .= $media['content'];
        $content .= "<h1 class=\"edan-search-object-title\">" . $this->get_title() . "</h1>";
        $nonImageMedia = $media['nonImages'];

        $content .= '<div id="edan-search-object-media-section">';
        $content .= '</div>';

        $content .= '<hr/></div>';
        $content .= $this->get_fields();
        if($nonImageMedia && count($nonImageMedia) > 0)
        {
          $content .= $this->other_media($nonImageMedia);
        }
      }
      catch(Exception $e)
      {
          $content .= "\n" . $e->getMessage();
      }

      return $content;
    }

    function get_title()
    {
      $title = "";

      if(property_exists($this->object, 'title'))
      {
        return $this->object->title;
      }
      elseif(property_exists($this->object, 'content') && property_exists($this->object->content, 'descriptiveNonRepeating') && property_exists($this->object->content->descriptiveNonRepeating, 'title'))
      {
        return $this->object->content->descriptiveNonRepeating->title;
      }

      return $title;
    }

    function other_media($media)
    {
      if(!$media || count($media) == 0)
      {
        console_log("Empty media");
        return "";
      }

      $content = "<div class=\"edan-search-non-image-media-block\">";
      $index = 0;
      foreach($media as $m)
      {
        $index++;
        if(property_exists($m, 'content'))
        {
          $src = $m->{'content'};

          if(property_exists($m, "caption"))
          {
            $caption = $m->{'caption'};
          }
          else
          {
            $caption = "Non-Image Media $index";
          }

          $content .= "<div class=\"edan-search-non-image-media\"><a href=\"$src\" alt=\"$caption\">$caption</a></div>";
        }
      }
      $content .= "</div>";
      return $content;
    }

    function get_media_content($object)
    {
      $res = [
        "content" => "<div>",
        "nonImages" => []
      ];

      $onlineMedia = $this->get_media_section($object);

      if(!$onlineMedia)
      {
        console_log("Online Media was null!");
        return $res;
      }

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
          $res['content'] .= "<div><span><a id=\"mediaPrev\" href=\"#\" onclick=\"mediaPrevious()\" style=\"display:none\">previous</a><span><span id=\"mediaIndex\">1</span>/$mediaCount</span><a id=\"mediaNext\" href=\"#\" onclick=\"mediaNext()\">Next</a></span></div>";
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
        $labels = $this->compile_field_values();

        $content .= "<div class=\"edan-search-object-view-fields\">";

        foreach($labels as $key => $vals)
        {
            foreach($vals as $k=>$txt)
            {
              $content .= '<div class="edan-search-object-view-field-label edan-search-object-view-field-label-' . $key .'">'. $this->options->replace_label($k) . '</div>';
              foreach($txt as $t)
              {
                $content .= '<div class="edan-search-object-view-field-content edan-search-object-view-field-content-' . $key . '">' . $t . '</div>';
              }
            }
        }

        $content .= "</div>";
      }

      return $content;
    }

    /**
     * compile a two dimensional array that stores all lines of field text as
     * separate array entries under their corresponding label.
     * @return [type] [description]
     */
    function compile_field_values()
    {
      $freetext = $this->object->{'content'}->{'freetext'};

      $display = array();

      foreach($freetext as $key => $val)
      {
        $display[$key] = [];

        foreach($val as $set)
        {
          if(!array_key_exists($set->{'label'}, $display[$key]))
          {
            $display[$key][$set->{'label'}] = array();
          }

          array_push($display[$key][$set->{'label'}], $set->{'content'});
        }
      }

      return $display;
    }

    function get_collection_object()
    {
      return "";
    }

    function get_media_section($object)
    {
      if(property_exists($object, 'descriptiveNonRepeating') && property_exists($object->{'descriptiveNonRepeating'}, 'online_media'))
      {
        return $object->{'descriptiveNonRepeating'}->{'online_media'};
      }
      elseif(property_exists($object, 'content') && property_exists($object->{'content'}, 'descriptiveNonRepeating') && property_exists($object->{'content'}->{'descriptiveNonRepeating'}, 'online_media'))
      {
        return $object->{'content'}->{'descriptiveNonRepeating'}->{'online_media'};
      }
      elseif(property_exists($object, 'online_media'))
      {
        return $object->{'online_media'};
      }
      elseif(property_exists($object, 'content') && property_exists($object->{'content'}, 'online_media'))
      {
        return $object->{'content'}->{'online_media'};
      }
      else
      {
        console_log("null");
        return null;
      }
    }
  }
?>
