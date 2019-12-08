<?php

add_action( 'woocommerce_product_options_pricing', 'action_woocommerce_product_options_pricing' );
function action_woocommerce_product_options_pricing() {
	global $post;

	// Set Default
	$_sale_price_from = '';
	$_sale_price_to   = '';

	// Check edit post action
	if ( isset( $post->ID ) and $post->ID > 0 ) {
		$_sale_price_from_meta = get_post_meta( $post->ID, '_sale_price_dates_from', true );
		if ( $_sale_price_from_meta != "" ) {
			$_sale_price_from = date( "H:i:s", $_sale_price_from_meta );
		}
		$_sale_price_to_meta = get_post_meta( $post->ID, '_sale_price_dates_to', true );
		if ( $_sale_price_to_meta != "" ) {
			$_sale_price_to = date( "H:i:s", $_sale_price_to_meta );
		}
	}
	?>
	<div class="form-field sale_price_dates_fields" id="sale_price_timer" style="display: none;">
		<label for="_sale_price_time_from"><?php echo esc_html__( 'Sale price time', 'woocommerce' ); ?></label>
		<input type="time" class="short" name="_sale_price_time_from" id="_sale_price_time_from" value=""/>
		<input type="time" class="short" name="_sale_price_time_to" id="_sale_price_time_to" value=""/>
	</div>
	<style>
		.woocommerce_options_panel fieldset.form-field, .woocommerce_options_panel div.form-field {
			padding: 5px 20px 5px 162px!important;
		}
		.woocommerce_options_panel div {
			margin: 9px 0;
		}
	</style>
	<script>
        jQuery(document).ready(function () {
            var _sale_price_from = jQuery("#_sale_price_time_from");
            var _sale_price_to = jQuery("#_sale_price_time_to");
            _sale_price_from.datepicker("destroy");
            _sale_price_to.datepicker("destroy");
            _sale_price_from.val("<?php echo $_sale_price_from; ?>");
            _sale_price_to.val("<?php echo $_sale_price_to; ?>");
        });
	</script>
	<?php
}

add_action( 'updated_postmeta', 'wc_sale_price_time_save', 10, 4 );
add_action( "added_post_meta", 'wc_sale_price_time_save', 10, 4 );
function wc_sale_price_time_save( $meta_id, $object_id, $meta_key, $meta_value ) {

	if ( ! in_array( $meta_key, array( "_sale_price_dates_to", "_sale_price_dates_from" ) ) ) {
		return;
	}

	if ( ! isset( $_POST['_sale_price_dates_from'] ) || ! isset( $_POST['_sale_price_dates_to'] ) || ! isset( $_POST['_sale_price_time_from'] ) || ! isset( $_POST['_sale_price_time_to'] ) || ( isset( $_POST['_sale_price_time_to'] ) and empty( $_POST['_sale_price_time_to'] ) ) || ( isset( $_POST['_sale_price_time_from'] ) and empty( $_POST['_sale_price_time_from'] ) ) ) {
		return;
	}

	if ( $meta_key == "_sale_price_dates_from" ) {
		$value = strtotime( $_POST['_sale_price_dates_from'] . ' ' .  $_POST['_sale_price_time_from'] );
	}
	if ( $meta_key == "_sale_price_dates_to" ) {
		$value = strtotime( $_POST['_sale_price_dates_to'] . ' ' .  $_POST['_sale_price_time_to'] );
	}

	update_post_meta( $object_id, $meta_key, $value );
}
