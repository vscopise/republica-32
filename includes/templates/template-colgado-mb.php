<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

global $post;

$colgado = get_post_meta($post->ID, '_COLGADO', true);

wp_nonce_field(basename(__FILE__), 'colgado_nonce');
?>
<p>
    <input type="text" name="colgado" id="colgado" value="<?php echo $colgado ?>" style="width: 100%;" />
</p>