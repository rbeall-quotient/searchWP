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
        array_push($navbar, '<a href="#/" onclick="toggle_all()" id="ogmt-expandall">Expand All</a>');
      }

      $content = '<ul class="ogmt-navbar">';

      foreach($navbar as $item)
      {
        $content .= '<li class="ogmt-navbar">';
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
        $content .= '<ul class="ogmt-navbar">';

        foreach($navbar as $item)
        {
          $content .= '<li class="ogmt-navbar">';
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
        else
        {
          $content .= $this->get_object_group($row);
        }
      }

      $content .= '</ul>';

      return $content;
    }

    /**
     * get html for rendering an object group
     *
     * @param  object $group object group json
     * @return string        html string for rendering object group link
     */
    function get_object_group($group)
    {
      $content  = '<li><span style="display:inline-block;">';
      $content .= '<div class="obj-header">';

      $group_content = '';

      if(property_exists($group, 'feature'))
      {
        if(property_exists($group->{'feature'}, 'media'))
        {
          $group_content .= $group->{'feature'}->{'media'};
        }
        else
        {
          $group_content .= '<img src="' . $group->{'feature'}->{'url'} . '"/>';
        }
      }

      //if ogmt plugin installed, render as a link
      if(class_exists( 'ogmt_url_handler' ))
      {
        $groupUrl = str_replace('objectgroup:', '', $group->{'url'});
        $url = $this->url_handler->group_url($groupUrl);
        $content .= '<a href="' . $url . '">' . $group_content . '</a>';

        $content .= '<div><div><a href="' . $url . '">';
        $content .= '<h4>' . $group->{'title'} . '</h4></a></div></div>';
      }
      else
      {
        $content .= $group_content;
        $content .= '<div><div>';
        $content .= '<h4>' . $group->{'title'} . '</h4></div></div>';
      }

      if(property_exists($group, 'description'))
      {
          $content .= '<div>' . $group->{'description'} . '</div>';
      }

      $content .= '</div></span></li><hr/>';

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

      $content  = '<li id="' . $classname . '-container' . '" class="ogmt-object-container">';
      $content .= '<div class="obj-header">';

      if($this->options->is_minimized())
      {
        $content .= "<a id=\"$classname-expander\" onclick=\"toggle_non_minis('" . $classname . "')\" href=\"#/\" class=\"expander\">Expand</a>";
      }

      if(property_exists($object, 'descriptiveNonRepeating'))
      {
        if(property_exists($object->{'descriptiveNonRepeating'}, 'online_media'))
        {
          $src = $object->{'descriptiveNonRepeating'}->{'online_media'}->{'media'}[0]->{'thumbnail'};
          $content .= "<img src=\"$src\" />";
        }

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

      $content .= '<hr/></div>';

      $content .= $this->get_fields($classname, $object);

      $content .= '</li>';

      return $content;
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
            $fieldclass = "ogmt-object-fields";
            $display = 'none';
          }
          else
          {
            $fieldclass = 'mini';
            $display = 'block';
          }

          $content .= "<div id=\"$field\" class=\"" . $fieldclass . "\" style=\"display:$display\">";

          foreach($vals as $label => $lns)
          {
            $content .= '<div><strong>'. $this->options->replace_label($label) . '</strong></div>';

            foreach($lns as $txt)
            {
              $content .= '<div>' . $txt . '</div>';
            }
          }

          $content .= "</div>";
        }
      }

      $content .= '</li>';

      return $content;
    }

  }
?>
