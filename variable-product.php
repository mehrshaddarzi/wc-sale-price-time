<?php

add_action( 'woocommerce_variation_options_pricing', 'action_woocommerce_variation_options_pricing', 10, 3 );
function action_woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {

	// Set Default
	$_sale_price_from = '';
	$_sale_price_to   = '';

	// Check edit post action
	if ( isset( $variation->ID ) and $variation->ID > 0 ) {
		$_sale_price_from_meta = get_post_meta( $variation->ID, '_sale_price_dates_from', true );
		if ( $_sale_price_from_meta != "" ) {
			$_sale_price_from = date( "H:i:s", $_sale_price_from_meta );
		}
		$_sale_price_to_meta = get_post_meta( $variation->ID, '_sale_price_dates_to', true );
		if ( $_sale_price_to_meta != "" ) {
			$_sale_price_to = date( "H:i:s", $_sale_price_to_meta );
		}
	}
	?>
    <div class="form-field sale_price_dates_fields hidden">
        <p class="form-row form-row-first">
            <label><?php echo __( 'Sale start time', 'woocommerce' ); ?></label>
            <input type="time" class="sale_price_dates_from" name="variable_sale_price_time_from[<?php echo $loop; ?>]"/>
        </p>
        <p class="form-row form-row-last">
            <label><?php echo __( 'Sale end time', 'woocommerce' ); ?></label>
            <input type="time" class="sale_price_dates_to" name="variable_sale_price_time_to[<?php echo $loop; ?>]"/>
        </p>
    </div>
    <script>
        jQuery(document).ready(function () {
            var _sale_price_from<?php echo $loop; ?> = jQuery("input[name='variable_sale_price_time_from[<?php echo $loop; ?>]']");
            var _sale_price_to<?php echo $loop; ?> = jQuery("input[name='variable_sale_price_time_to[<?php echo $loop; ?>]']");
            _sale_price_from<?php echo $loop; ?>.datepicker("destroy");
            _sale_price_to<?php echo $loop; ?>.datepicker("destroy");
            _sale_price_from<?php echo $loop; ?>.val("<?php echo $_sale_price_from; ?>");
            _sale_price_to<?php echo $loop; ?>.val("<?php echo $_sale_price_to; ?>");
        });
    </script>
	<?php
}

add_action( 'woocommerce_save_product_variation', 'action_save_custom_field_variations', 10, 2 );
function action_save_custom_field_variations( $variation_id, $i ) {
	//https://businessbloomer.com/woocommerce-add-custom-field-product-variation/

	$_sale_price_time_from = $_POST['variable_sale_price_time_from'][ $i ];
	if ( isset( $_POST['variable_sale_price_dates_from'][ $i ] ) and ! empty( $_POST['variable_sale_price_dates_from'][ $i ] ) and ! empty( $_sale_price_time_from ) ) {
		$value = strtotime( $_POST['variable_sale_price_dates_from'][ $i ] . ' ' . $_sale_price_time_from );
		update_post_meta( $variation_id, '_sale_price_dates_from', esc_attr( $value ) );
	}

	$_sale_price_time_to = $_POST['variable_sale_price_time_to'][ $i ];
	if ( isset( $_POST['variable_sale_price_dates_to'][ $i ] ) and ! empty( $_POST['variable_sale_price_dates_to'][ $i ] ) and ! empty( $_sale_price_time_from ) ) {
		$value = strtotime( $_POST['variable_sale_price_dates_to'][ $i ] . ' ' . $_sale_price_time_to );
		update_post_meta( $variation_id, '_sale_price_dates_to', esc_attr( $value ) );
	}
}