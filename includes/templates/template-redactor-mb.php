<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

global $post;

$redactor = get_post_meta( $post->ID, 'AUTOR', true );

wp_nonce_field(basename(__FILE__), 'redactor_nonce');
?>
<p>
    <input type="text" name="redactor" id="redactor" value="<?php echo $redactor ?>" style="width: 100%;" />
</p>