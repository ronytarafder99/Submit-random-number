<?php
function soft_diwp_custom_metabox()
{

add_meta_box('diwp-metabox', 'continue link', 'soft_diwp_custom_metabox_callback', 'post', 'normal');
}

 add_action('add_meta_boxes', 'soft_diwp_custom_metabox');


function soft_diwp_custom_metabox_callback()
{
    global $post;
?>
<div class="row">
    <div class="label">Enter the Link:</div>
    <div class="fields">
        <input type="text" name="_diwp_reading_time"
            value="<?php echo get_post_meta($post->ID, 'post_reading_time', true) ?>" />
    </div>
</div>
<?php

}

function soft_diwp_save_custom_metabox()
{
    global $post;
    if (isset($_POST["_diwp_reading_time"])) :
        update_post_meta($post->ID, 'post_reading_time', $_POST["_diwp_reading_time"]);
    endif;
}

add_action('save_post', 'soft_diwp_save_custom_metabox');