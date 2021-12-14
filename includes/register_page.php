<?php function we_theme_options_panel()
{
    add_menu_page('Theme page title', 'Random Number', 'manage_options', 'theme-options', 'wps_theme_func', 'dashicons-welcome-widgets-menus', 5);
    add_submenu_page('theme-options', 'Settings page title', 'Numbers', 'manage_options', 'theme-op-settings', 'wps_theme_func_settings');
    add_submenu_page('theme-options', 'installation', 'Installation', 'manage_options', 'theme-op-faq', 'wps_theme_func_faq');
}
add_action('admin_menu', 'we_theme_options_panel');

function wps_theme_func()
{ ?>

<div class="wrap">
    <h2>Dashboard</h2>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options') ?>
        <p><strong>Timer in Second(s)</strong><br />
            <input type="number" name="map_title" size="45" value="<?php echo get_option('map_title'); ?>" />
        </p>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="map_title" />
        <?php submit_button(); ?>
    </form>
</div>

<?php }

function wps_theme_func_faq()
{ ?>
<div class="wrap">
    <h2>Installation</h2>
    <p><strong>Customization</strong></p>
    <ol>
        <li>In WordPress dashboard, go to <strong>Appearance &gt; Widgets</strong>. </li>
        <li>Drag and Drop <strong>Text</strong> into your sidebar.</li>
        <li>Click <strong>Text</strong> that is right side of Visual.</li>
        <li>Enter This <code>[submit_random_number]</code> SortCode in the Text Field</li>
        <li>Click <strong>Save</strong> And Next <strong>Done</strong>.</li>
    </ol>
    <p><strong>OR</strong></p>
    <p>Use <code>[submit_random_number]</code> shortcode inside your post or page.</p>
    <p><strong>OR: Use this PHP code AnyWhere In your Template or Page.</strong></p>
    <code> <span style="color: #557799">&lt;?php</span> <span style="color: #008800; font-weight: bold">echo</span>
        do_shortcode(<span style="background-color: #fff0f0">&#39;[submit_random_number]&#39;</span>); <span
            style="color: #557799">?&gt;</span></code>
    <hr>
    <p><strong style="color: red;">IF you face "The page can’t be found" OR 404 Error Try These.</strong></p>
    <div class="s-prose js-post-body" itemprop="text">
        <ol>
            <li>Navigate to Settings -&gt; permalinks</li>
            <li>Change the permalink structure to <em>Default</em></li>
            <li>Save settings</li>
            <li>Change to custom structure or post name (or any other structure)</li>
            <li>Save Settings</li>
        </ol>
        <p>This will re-write the htaccess file and then the re-write should work.</p>
        <hr>
        <p>If the above solution doesn't work - it should be related to server configuration.</p>
        <p>Aapache2</p>
        <p>Run: <code>a2enmod rewrite &amp;&amp; service apache2 reload</code></p>
        <p>Nginx</p>
        <p>Follow: <a href="https://do.co/2LjCF8r" rel="noreferrer">https://do.co/2LjCF8r</a></p>
        <hr>
        <p>I hope this will save your time.</p>
    </div>
</div>
<?php }

function wps_theme_func_settings()
{
    $exampleListTable = new Example_List_Table();
    $exampleListTable->prepare_items();
    ?>
<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <h2>Random Number List Table Page</h2>
    <?php $exampleListTable->display(); ?>
</div>
<?php
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Example_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'title'       => 'Number',
            // 'description' => 'Description',
            'year'        => 'Time',
            // 'director'    => 'Director',
            // 'rating'      => 'Rating'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('title' => array('title', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wesoftpress_random';
        $data =  $wpdb->get_results( "SELECT * FROM $table_name ORDER BY ID DESC");
        $data = json_decode(json_encode($data), true);
        
        foreach ($data as $value){ 
            $data1[] = array(
                'id'          => $value['id'],
                'title'       => $value['term'],
                // 'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'year'        => $value['time'],
                // 'director'    => 'Frank Darabont',
                // 'rating'      => '9.3'
                );
        } 

        return $data1;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'title':
            case 'description':
            case 'year':
            case 'director':
            case 'rating':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
}
?>