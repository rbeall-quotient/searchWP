<?php
  /**
   * Class Handling Calls to EDAN Object Groups. Retrieve JSON.
   */

  require 'edan_core/EDANInterface.php';

  /**
   * class for querying edan, passing a url string of query vars
   */
  class edan_handler
  {
    /**
     * Method to call EDAN API (modified to make a variety of calls based on passed edan_vars)
     * @param  string $edan_vars EDAN query vars
     * @return string            JSON results
     */
    function edan_call($edan_vars, $service, $issearch=false)
    {
      //get creds from options_handler
      $options = new esw_options_handler();
      $_GET   = array();

      $uri_string = "";
      $COUNT=0;

      foreach($edan_vars as $key => $var)
      {
        if($COUNT!=0)
        {
          $uri_string .= "&";
        }

        if($key != "fqs")
        {
          $uri_string .= "$key=$var";

          $_GET[$key] = $var;
          $COUNT++;
        }
      }

      $edan_fqs = get_query_var('edan_fq');

      $fqs = array();
      array_push($fqs, "type:\"edanmdm\"");
      if(array_key_exists('fqs', $edan_vars))
      {
        foreach($edan_vars['fqs'] as $fq)
        {
          $fq = explode(':', $fq, 2);
          if(count($fq) < 2)
          {
            continue;
          }
          array_push($fqs, $fq[0] . ":\"" . str_replace(' ', '+', $fq[1]) . "\"");
        }
      }

      if($edan_fqs && $issearch)
      {
        foreach($edan_fqs as $fq)
        {
          $fq = explode(':', $fq, 2);

          array_push($fqs, $fq[0] . ":\"" . str_replace(' ', '+', $fq[1]) . "\"");
        }
      }

      if(count($fqs) > 0)
      {
        $uri_string .= '&fqs=' . json_encode($fqs);
      }

      // Execute
      $edan = new EDANInterface($options->get_edan_server(), $options->get_app_id(), $options->get_auth_key(), $options->get_tier_type());
      
      // Response
      $info = '';
      $results = $edan->sendRequest($uri_string, $service, FALSE, $info);

      if (is_array($info))
      {
        if ($info['http_code'] == 200)
        {
          return $results;
          exit;
        }
        else
        {
          //if EDAN call fails, return false
          console_error('Request failed: HTTP code ' . $info['http_code'] . ' returned');
          return false;
        }
      }
      else
      {
        //if no response, return false
        console_error('Request failed: ' . $info);
        return false;
      }
    }

    /**
     * Get array containing query vars from url
     *
     * @return array EDAN query vars
     */
    function get_vars()
    {
      $vars = array();

      foreach($_GET as $key => $value)
      {
        if(gettype($value) != 'array')
        {
          $vars[$key] = $value;
        }
      }

      return $vars;
    }

    /**
     * Tests if passed list index is a numeric value within acceptable bounds
     * of dataset. If not, 0 (start index) is returned instead.
     *
     * @param  string $index       passed index query var
     * @param  object $objectGroup json object group data
     *
     * @return string             validated index or 0
     */
    function validate_list_index($index, $objectGroup)
    {
      if($index)
      {
        if(is_numeric($index) && ($index >= 0))
        {
          if(property_exists($objectGroup->{'objects'}, 'size'))
          {
            if($index * 10 < $objectGroup->{'objects'}->{'size'})
            {
              return $index;
            }
          }
        }
      }

      return 0;
    }
  }
?>
