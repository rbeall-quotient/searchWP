<?php
  /**
   * class for displaying edan search data
   */
  class edan_search_view
  {
    /**
     * initialize url and options handlers
     * store edan search json object
     *
     * @param array $cache set of edan search json
     */
    function __construct($cache)
    {
      $this->url_handler = new esw_url_handler();
      $this->options = new esw_options_handler();

      $this->results = $cache['search'];
    }

    /**
     * get edan search content processed in html
     *
     * @return string edan search html string
     */
    function get_content()
    {
      if(!$this->results)
      {
        return '';
      }

      if(get_query_var('jsonDump'))
      {
        print_r("<pre>");
        echo htmlspecialchars(json_encode($this->results, JSON_PRETTY_PRINT));
        print_r("</pre>");
      }
      else
      {
        $facets = new edan_facet_view($this->results);

        $content  = '<div style="width: 100%; overflow: hidden;">';
        $content .= $this->get_search_bar();
        if(get_query_var("edan_q") || get_query_var("edanUrl"))
        {
          $content .= '<div style="width: 65%; float: left;">';
          $content .= $this->get_top_nav();
          $content .= $this->get_object_list();
          $content .= $this->get_bottom_nav();
          $content .= '</div>';
          $content .= '<div style="float: right;"><div>'.$facets->get_content().'</div></div>';
        }
        $content .= '</div>';

        return $content;
      }

      return '';
    }

    /**
     * return html for displaying a basic text input form with submit button
     *
     * @return string search bar html string
     */
    function get_search_bar()
    {
      $content  = '<form onsubmit="return edan_search_redirect();">';
      $content .= '<input type="text" id="edan-search-bar"></input>';
      $content .= '<input type="submit"></input>';
      $content .= '</form>';

      return $content;
    }

    /**
     * Display nav links above objects list
     *
     * @param  string $info current and total page numbers
     * @return string html string of nav links
     */
    function get_top_nav()
    {
      $info = $this->obj_page_info();

      if(!$info['total'])
      {
        return '';
      }

      $navbar = array();

      $firstprev = $info['current'] != 1; //display "first" and "preview" links
      $nextlast  = $info['current'] != $info['total']; //display "next" and "last" links
      $expandall = $this->options->is_minimized(); //whether to add an "Expand All" link

      if($firstprev)
      {
        array_push($navbar, '<a href="'.$this->url_handler->list_url(0).'">First</a>');
        array_push($navbar, '<a href="'.$this->url_handler->list_url($info['current']-2).'">Previous</a>');
      }

      if($nextlast)
      {
        array_push($navbar, '<a href="'.$this->url_handler->list_url($info['current']).'">Next</a>');
        array_push($navbar, '<a href="'.$this->url_handler->list_url($info['total']-1).'">Last</a>');
      }

      if($expandall)
      {
        array_push($navbar, '<a href="#/" onclick="toggle_all()" id="edan-search-expandall">Expand All</a>');
      }

      $content = '<ul class="edan-search-navbar">';

      foreach($navbar as $item)
      {
        $content .= '<li class="edan-search-navbar">';
        $content .= $item;
        $content .= '</li>';
      }

      $content .= '</ul>';

      return $content;
    }

    /**
     * Get navigation for bottom of object list
     * @return string navigation content
     */
    function get_bottom_nav()
    {
      $navbar = array();

      $info = $this->obj_page_info();
      $pagelist = $this->get_page_list($info);

      if(count($pagelist) < 1)
      {
        return '';
      }

      $min = $pagelist[0];
      $max = $pagelist[count($pagelist) - 1];

      $firstprev = $info['current'] != 1;//display "first" and "preview" links
      $mindots   = ($min > 1); //display "..." prior to num list
      $maxdots   = ($max < $info['total']); //display "..." after num list
      $nextlast  = $info['current'] != $info['total'];//display "next" and "last" links

      if($firstprev)
      {
        array_push($navbar, '<a href="'.$this->url_handler->list_url(0).'">First</a>');
        array_push($navbar, '<a href="'.$this->url_handler->list_url($info['current']-2).'">Previous</a>');
      }

      if($mindots)
      {
        array_push($navbar, '...');
      }

      foreach($pagelist as $page)
      {
        if($page == $info['current'])
        {
          array_push($navbar, $page);
        }
        else
        {
          array_push($navbar, '<a href="'.$this->url_handler->list_url($page-1).'">' . $page . '</a>');
        }
      }

      if($maxdots)
      {
        array_push($navbar, '...');
      }

      if($nextlast)
      {
        array_push($navbar, '<a href="'.$this->url_handler->list_url($info['current']).'">Next</a>');
        array_push($navbar, '<a href="'.$this->url_handler->list_url($info['total']-1).'">Last</a>');
      }

      $content = "";

      if($info["total"] > 1)
      {
        $content .= '<ul class="edan-search-navbar">';

        foreach($navbar as $item)
        {
          $content .= '<li class="edan-search-navbar">';
          $content .= $item;
          $content .= '</li>';
        }

        $content .= '</ul>';
      }

      return $content;
    }

    /**
     * Get list of pages of objects returned for search
     *
     * @param  array $info array of page information (current page and total pages)
     * @return array       array of numbers to render as page links
     */
    function get_page_list($info)
    {
      $total   = $info['total'];
      $current = $info['current'];

      $median = 5;

      $nums = array();

      if($current <= 4)
      {
        for($i = 1; $i <= 9; $i++)
        {
          if($i <= $total)
          {
            array_push($nums, $i);
          }
        }
      }
      elseif(($current + 4) >= $info['total'])
      {
        for($i = $info['total'] - 8; $i <= $info['total']; $i++)
        {
          if($i > 0)
          {
            array_push($nums, $i);
          }
        }
      }
      else
      {
        for($i = ( $current - 4 ); $i <= ($current + 4); $i++)
        {
          if($i > 0 && $i <= $total)
          {
            array_push($nums, $i);
          }
        }
      }

      return $nums;
    }

    /**
     * Get array with current page number and total number of pages
     *
     * Note:
     * info[current] = page of objects user is on
     * info[total]   = total number of pages of objects (10 objects per page)
     *
     * @return array array of page values
     */
    function obj_page_info()
    {
      $rows = $this->options->get_rows();

      $info = array();
      $index = get_query_var('listStart');

      if($index && is_numeric($index) && $index < ($this->results->{'numFound'}/$rows))
      {
        $info['current'] = ($index + 1);
      }
      else
      {
        $info['current'] = 1;
      }

      if($index < $this->results->{'numFound'})
      {
        $num = $this->results->{'numFound'}/$rows;

        if(($num - intval($num)) > 0)
        {
          $num = intval($num) + 1;
        }
        else
        {
          $num = intval($num);
        }

        $info['total'] = $num;
      }
      else
      {
        $info['total'] = false;
      }

      return $info;
    }

    /**
     * Retrieve html string of objects
     *
     * @return string html string of objects
     */
    function get_object_list()
    {
      $content  = '<ul style="list-style:none;">';
      $obs      = $this->results->{'rows'};
      $index    = 0;

      foreach($obs as $row)
      {
        if($row->{'type'} != 'objectgroup')
        {
          $content .= $this->get_object($row->{'content'}, $row->{'url'}, $index++) . '<br/>';
        }
      }

      $content .= '</ul>';

      return $content;
    }

    /**
     * Get html for individual objects
     *
     * @param  object $row row of decoded json data for a particular object
     * @return string html string for object data
     */
    function get_object($object, $url, $index)
    {
      $classname = $index;

      $content  = '<li id="' . $classname . '-container' . '" class="edan-search-object-container">';
      $content .= '<div class="edan-search-obj-header">';

      if($this->options->is_minimized())
      {
        $content .= "<a id=\"$classname-expander\" onclick=\"toggle_non_minis('" . $classname . "')\" href=\"#/\" class=\"edan-search-expander\">Expand</a>";
      }

      $media = $this->get_media_section($object);
      //$content .= json_encode($media);

      if($media)
      {
        foreach($media->{'media'} as $m)
        {
          if(property_exists($m, 'type') && $m->{'type'} == "Images")
          {
            if(property_exists($m, 'thumbnail'))
            {
              $src = $m->{'thumbnail'};
            }
            elseif(property_exists($m, 'content'))
            {
              $src = $m->{'content'};
            }
            else
            {
              $src = "";
            }

            $content .= "<img src=\"$src\" />";
            break;
          }
        }
      }

      if(property_exists($object, 'descriptiveNonRepeating'))
      {
        $content .= '<h4><a href="' . $this->url_handler->get_object_url($url) . '">' . $object->{'descriptiveNonRepeating'}->{'title'}->{'content'} . '</a></h4>';
      }
      elseif(property_exists($object, 'title'))
      {
        if(property_exists($object->{'title'}, 'content'))
        {
          $content .= '<h4><a href="' . $this->url_handler->get_object_url($url) . '">' . $object->{'title'}->{'content'} . '</a></h4>';
        }else
        {
          $content .= '<h4><a href="' . $this->url_handler->get_object_url($url) . '">' . $object->{'title'} . '</a></h4>';
        }
      }
      elseif(property_exists($object, 'Collection_Title'))
      {
        $title = $object->{'Collection_Title'};
        $content .= $content .= '<h4><a href="' . $this->url_handler->get_object_url($url) . '">' . $title . '</a></h4>';
      }

      $content .= '</div>';

      $content .= $this->get_fields($classname, $object);

      $content .= '</li>';

      return $content;
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
        return $object->{'online_media'};
      }
      else
      {
        return null;
      }
    }

    /**
     * Get fields for objects
     *
     * @param  string $classname unique classname for each object
     * @param  object $object    edan object json
     * @return string            html string for rendering object link
     */
    function get_fields($classname, $object)
    {
      $content = '';

      if(property_exists($object, 'freetext'))
      {
        $labels = $this->options->get_display_data($object->{'freetext'});

        foreach($labels as $field => $vals)
        {
          if(!$this->options->is_minimized())
          {
            $fieldclass = $field;
            $display = 'block';
          }
          elseif($this->options->get_mini($field))
          {
            $fieldclass = "edan-search-object-fields";
            $display = 'none';
          }
          else
          {
            $fieldclass = 'mini';
            $display = 'block';
          }

          $fieldclass .= " edan-search-field-$field";

          $content .= "<div id=\"$field\" class=\"" . $fieldclass . "\" style=\"display:$display\">";

          foreach($vals as $label => $lns)
          {
            $content .= '<div class="edan-search-label-' . str_replace(" ", "-", $label) . '">'. $this->options->replace_label($label) . '</div>';

            foreach($lns as $txt)
            {
              $content .= '<div class="edan-search-field-content-' . str_replace(" ", "-", $label) . '">' . $txt . '</div>';
            }
          }
          $content .= "</div>";
        }

        $content .= $this->get_online_media($object);
      }

      $content .= '</li>';

      return $content;
    }

    function get_online_media($object)
    {
      $onlineMedia = $this->get_media_section($object);

      if(!$onlineMedia)
      {
        return "";
      }

      $content = "<div class=\"edan-search-list-media\">";

      $mediaCount = $onlineMedia->{'mediaCount'};
      //$content .= "<div>$mediaCount</div>";
      $media = $onlineMedia->{'media'};
      $index = 1;

      foreach($media as $m)
      {
        if($index == 1)
        {
          $index++;
          continue;
        }
        if(property_exists($m, 'type'))
        {
          $type = $m->{'type'};
        }

        if(property_exists($m, 'content'))
        {
          $src  = $m->{'content'};
        }

        if(property_exists($m, 'caption'))
        {
          $caption = $m->{'caption'};
        }

        if(property_exists($m, 'thumbnail'))
        {
          $thumbnail  = $m->{'thumbnail'};
        }

        $css = " edan-search-object-fields ";

        if(!$this->options->is_minimized())
        {
          $display = 'display:block';
        }
        else
        {
          $display = 'display:none';
        }

        $css .= " edan-search-media-anchor ";

        if($type)
        {
          $css .= "edan-search-media-anchor-$type";
        }

        $alt = "";

        if($caption)
        {
          $alt = $caption;
        }
        else
        {
          if(property_exists($object, 'title'))
          {
            if(property_exists($object->{'title'}, 'content'))
            {
              $title = $object = $object->{'title'}->{'content'};
            }
            else
            {
              $title = $object->{'title'};
            }
          }
          elseif(property_exists($object, 'descriptiveNonRepeating') && property_exists($object->{'descriptiveNonRepeating'}, 'title'))
          {
            if(property_exists($object->{'descriptiveNonRepeating'}->{'title'}, 'content'))
            {
              $title = $object->{'descriptiveNonRepeating'}->{'title'}->{'content'};
            }
            else
            {
                $title = $object->{'descriptiveNonRepeating'}->{'title'};
            }
          }

          $alt .= "media object $index of $mediaCount for record $title";
        }

        $content .= "<a class=\"$css\" href=\"$src\" alt=\"$alt\" style=\"$display\">";
        if($type == "Images")
        {
          if($thumbnail)
          {
            $content .= "<img src=\"$thumbnail\" />";
          }
          else
          {
            $content .= "<img src=\"$content\" />";
          }
        }
        else
        {
          if($caption)
          {
            $content .= $caption;
          }
          else
          {
            $content .= "Media Object $index for $title";
          }
        }
        $content .= "</a>";
        $index++;
      }

      $content .= '</div>';
      return $content;
    }
}
?>
