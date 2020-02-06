<?php
/*
  Plugin Name: EDAN Search
  Description: EDAN Search WordPress Integration
  Version:     1.0
  Author:      Robert Beall
  */

  //ADMIN
  require 'libs/admin/edan-search-admin-links.php';
  require 'libs/admin/edan-search-admin-menu.php';

  //EDAN
  //require 'libs/edan/edan_core/EDANInterface.php';
  require 'libs/edan/edan-search-edan-handler.php';
  require 'libs/edan/edan_calls/edan_search_call.php';
  require 'libs/edan/edan_calls/edan-search-cache-handler.php';
  require 'libs/edan/edan_calls/edan-search-object-call.php';

  //Page
  require 'libs/page/edan-search-content.php';
  require 'libs/page/edan-search-query-init.php';
  require 'libs/page/edan-search-titles.php';

  //Scripts
  require 'libs/scripts/edan-search-include-scripts.php';

  //Utilities
  require 'libs/utilities/edan-search-options-handler.php';
  require 'libs/utilities/edan-search-sanitizer-handler.php';
  require 'libs/utilities/edan-search-url-handler.php';
  require 'libs/utilities/edan-search-utilities.php';

  //Shortcodes
  require 'libs/shortcodes/edan-search-shortcodes.php';

  //Views
  require 'libs/views/edan-search-view.php';
  require 'libs/views/edan-search-view-manager.php';
  require 'libs/views/edan-search-facet-view.php';
  require 'libs/views/edan-search-json-view.php';
  require 'libs/views/edan-search-object-view.php';
?>
