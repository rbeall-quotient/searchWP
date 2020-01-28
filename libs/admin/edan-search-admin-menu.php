<?php
  /**
   * File for rendering and processing admin menu data for EDAN Search
   */

   //add admin menu
  add_action( 'admin_menu', 'edan_search_add_menu');

  //register admin menu settings
  add_action( 'admin_init', 'edan_search_register_settings' );
  add_action( 'admin_init', 'edan_search_update_options' );

  /**
   * Function to add a submenu under "settings" that corresponds to EDAN Search plugin
   */
  function edan_search_add_menu()
  {
    add_menu_page('EDAN Search', 'EDAN Search Settings', 'manage_options', 'edan-search-settings', 'edan_search_admin_menu');
  }

  /**
   * Register EDAN Search settings options array
   */
  function edan_search_register_settings()
  {
    register_setting( 'edan_search_option_group', 'edan_search_settings', 'edan_search_sanitize_values' );
  }

  function edan_search_update_options()
  {
    if(!get_option('edan_search_settings'))
    {
      $options = array(
        'creds' => '',
        'config' => '',
        'path' => '',
        'title' => '',
        'resultsmessage' => '',
        'noresults' => '',
        'rows' => 10,
        'remove' => '',
        'fnfield' => '',
        'hffield' => '',
        'ffield' => '',
        'lfield' => '',
        'mfield' => ''
      );

      update_option('edan_search_settings', $options);
    }
  }

  /**
   * Sanitize settings data
   *
   * Note: need to build out later
   *
   * @param  array $settings array of settings for edan search
   * @return array Array of sanitized data
   */
  function edan_search_sanitize_values($settings)
  {
    $sanitizer = new esw_sanitizer_handler();
    return $sanitizer->sanitize($settings);
  }

  /**
   * Echo EDAN Search html to admin plugin settings page with nmah settings
   */
   function edan_search_admin_menu()
   {
   	$settings = get_option( 'edan_search_settings' );
    console_log("TYPE: " . gettype($settings));
   	?>
    <h1>EDAN Search Settings</h1>
    <br/><br>
   	<form method="post" action="options.php" id="edan-search-admin">
   		<?php settings_fields( 'edan_search_option_group' ); ?>
      <fieldset>
        <legend class="edan-search-header"><strong>EDAN Search Configuration:</strong></legend><br/>
        <div class=edan-search-field-label>Creds:</div>
        <div>
          <input type="text" name="edan_search_settings[creds]" value="<?php echo $settings[ 'creds' ]; ?>" />
          <div class="description">Enter creds for the specific repository</div>
        </div>
        <br/>
        <div class=edan-search-field-label>Config</div>
        <div>
          <textarea form ="edan-search-admin" name="edan_search_settings[config]" id="config" cols="100"><?php echo $settings[ 'config' ]; ?></textarea>
          <div class="description">Use this box to copy and paste the edan .config.ini information</div>
        </div>
        <br/>
        <div class=edan-search-field-label>EDAN Search Path:</div>
        <div>
          <input type="text" name="edan_search_settings[path]" value="<?php echo $settings[ 'path' ] ?>" />
          <div class="description">The base path for object group pages. If the Pathauto module is installed, those settings may override the base path.</div>
        </div>
        <br/>
        <div class=edan-search-field-label>EDAN Search Title:</div>
        <div>
          <input type="text" name="edan_search_settings[title]" value="<?php echo $settings[ 'title' ]; ?>" />
          <div class="description">The title used in breadcrumbs and menu.</div>
        </div>
      </fieldset>
      <br/><hr><br/><br/>
      <fieldset>
        <legend class="edan-search-header"><strong>Search Configuration:</strong></legend><br/>
 				<div class=edan-search-field-label>"Search Results" Message:</div>
 				<div>
          <input type="text" name="edan_search_settings[resultsmessage]" size=50 value="<?php echo $settings[ 'resultsmessage' ]; ?>" />
          <div class="description">The message that's shown when a search returns results. Tokens for @count (number of items)</div>
        </div>
        <br/>
        <div class=edan-search-field-label>"No Results" Message:</div>
 				<div>
          <input type="text" name="edan_search_settings[noresults]" size=50 value="<?php echo $settings[ 'noresults' ]; ?>" />
          <div class="description">The message that's shown when a search returns results. Tokens for @count (number of items)</div>
        </div>
        <br/>
        <div class=edan-search-field-label>Results Per Page:</div>
        <div>
          <input type="text" name="edan_search_settings[rows]" size=3 value="<?php echo $settings[ 'rows' ]; ?>" />
          <div class="description">A number between 1 and 100.</div>
        </div>
      </fieldset>
      <br/><hr><br/><br/>
      <fieldset>
        <legend class="edan-search-header"><strong>Facets Configuration: </strong></legend><br/>
        <div class=edan-search-field-label>Remove facets message:</div>
        <div>
          <input type="text" name="edan_search_settings[remove]" size=50 value="<?php echo $settings[ 'remove' ]; ?>" />
          <div class="description">You can modify the message that gets displayed above the list of currently selected facets.</div>
        </div>
        <br/>
        <div class=edan-search-field-label>Facet Names:</div>
        <div>
          <textarea form ="edan-search-admin" name="edan_search_settings[fnfield]" id="fnfield" cols="100"><?php echo $settings[ 'fnfield' ]; ?></textarea>
          <div class="description">Use this box to change the order of facets and replace facet names with different names. Use the facet name and the new name/label for the facet, separated by a pipe character. Enter one facet per line. For example to rename the facet name data_source enter "data_source | Data Source" without the quotes. Notice the pipe "|" character between the name and desired replacement. Replacements are case sensitive. By default, any facets not listed here will be shown at the end of the list. You can explicitly remove facets using the "Facets to Hide" box below.</div>
        </div>
        <br/>
        <div class=edan-search-field-label>Facets To Hide:</div>
        <div>
          <textarea form ="edan-search-admin" name="edan_search_settings[hffield]" id="hffield" cols="100"><?php echo $settings[ 'hffield' ]; ?></textarea>
          <div class="description">Use this box to indicate any facets which should be hidden. Enter one facet per line, and enter only the facet name such as "data_source" without the quotes.</div>
        </div>
      </fieldset>
      <br/><hr><br/><br/>
      <fieldset>
        <legend class="edan-search-header"><strong>Fields and Labels Configuration: </strong></legend><br/>
        <div class=edan-search-field-label>Field Order:</div>
        <div>
          <textarea form ="edan-search-admin" name="edan_search_settings[ffield]" id="ffield" cols="100"><?php echo $settings[ 'ffield' ]; ?></textarea>
          <div class="description">Metadata to show in search results. Each field should be on its own line. Leave blank to hide all field, or * to show all fields. If you want to specify a set of fields and then show the remaining add an * as the last line. Examples of topics: creditLine dataSource objectType.</div>
        </div>
        <br/>
        <div class=edan-search-field-label>Label Replacements:</div>
        <div>
          <textarea form ="edan-search-admin" name="edan_search_settings[lfield]" id="labels" cols="100"><?php echo $settings[ 'lfield' ]; ?></textarea>
          <div class="description">Replace the labels shown with a different label. When making this list, do not list the "facet" name, but the "label." For example the metadata facet, physicalDescription, has a label "Physical Description" -- For this to appear on the object listing as "Phys. Descr." you enter the following line (without quotes) "Physical Description | Phys. Descr." -- notice the pipe "|" character between the label and desired replacement. Replacements are not case sensitive.</div>
        </div>
        <div class=edan-search-field-label>Mini Fields:</div>
        <div>
          <textarea form ="edan-search-admin" name="edan_search_settings[mfield]" id="mini" cols="100"><?php echo $settings[ 'mfield' ]; ?></textarea>
          <div class="description">Fields listed here will be marked with the mini class. By default this will cause non-mini fields to be hidden and add a "expand" button to each record to show non-mini fields. Each field should be on its own line. Leave blank for all.</div>
        </div>
        <br/>
      </fieldset>
      <br/><hr>
      <div><?php echo submit_button(); ?></div>
   	</form>
   	<?php
   }
?>
