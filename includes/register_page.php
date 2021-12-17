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

function wps_theme_func_settings(){
    global $wpdb;

    $table = new we_Table_Example_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>Item Deleted</p></div>';
    }
    ?>
    <div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Random Number', 'cltd_example')?></h2>
    <?php echo $message; ?>

    <form id="persons-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $table->search_box('search', 'search_id'); ?>
        <?php $table->display() ?>
    </form>

    </div>
    <?php
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class we_Table_Example_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'person',
            'plural' => 'persons',
        ));
        
    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     * method name must be like this: "column_[column_name]"
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_age($item)
    {
        return '<em>' . $item['time'] . '</em>';
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_email($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array(
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'cltd_example')),
        );

        return sprintf('%s %s',
            $item['id'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'email' => __('ID', 'cltd_example'),
            'term' => __('Number', 'cltd_example'),
            'age' => __('Time', 'cltd_example'),
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'term' => array('term', true),
            'email' => array('id', false),
            'age' => array('time', false),
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wesoftpress_random'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
           
    }

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wesoftpress_random'; // do not forget about tables prefix

        $per_page = 99999999999999999; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'term';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array

        if( ! empty( $_REQUEST['s'] ) ){
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE term LIKE '%{$search}%' OR id LIKE '%{$search}%'", $per_page, $paged), ARRAY_A);
        }else{
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        }

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}
?>