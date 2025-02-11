<?php
/**
 * Renders the WPEC tickets table/form
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/wpectickets/tickets.php
 *
 * @version 4.3.5
 *
 * @var bool $must_login
 */
ob_start();

$is_there_any_product         = false;
$is_there_any_product_to_sell = false;
$unavailability_messaging     = is_callable( array( $this, 'do_not_show_tickets_unavailable_message' ) );

foreach ( $tickets as $ticket ) {
	/**
	 * Changing any HTML to the `$ticket` Arguments you will need apply filters
	 * on the `wpectickets_get_ticket` hook.
	 */

	if ( $ticket->date_in_range( current_time( 'timestamp' ) ) ) {

		$is_there_any_product = true;

		echo '<tr>';

		echo "<td width='115' class='wpec quantity' data-product-id='" . esc_attr( $ticket->ID ) . "'>";
		if ( wpsc_product_has_stock( $ticket->ID ) ) {

			$is_there_any_product_to_sell = true;
			if ( get_option( 'addtocart_or_buynow' ) == '1' ) {
				echo wpsc_buy_now_button( $ticket->ID );
			} else {
				?>
				<fieldset>
					<legend><?php esc_html_e( 'Quantity', 'event-tickets-plus' ); ?></legend>
					<div class="wpsc_quantity_update">
						<input type="number" id="wpec_tickets_quantity_<?php echo esc_attr( $ticket->ID ); ?>" name="wpec_tickets_quantity[]" size="2" value="0" min="0" <?php disabled( $must_login ); ?>/>
						<input type="hidden" value="<?php echo esc_attr( $ticket->ID ); ?>" name="wpec_tickets_product_id[]" />
					</div>
					<!--close wpsc_quantity_update-->
				</fieldset>
				<?php
			}

			$remaining = $ticket->remaining();

			if ( $remaining ) {
				?>
				<span class="tribe-tickets-remaining">
					<?php
					echo sprintf( esc_html__( '%1$s out of %2$s available', 'event-tickets-plus' ), esc_html( $remaining ), esc_html( $ticket->original_stock() ) );
					?>
				</span>
				<?php
			}
		} else {
			echo '<span class="tickets_nostock">' . esc_html__( 'Sold Out', 'event-tickets-plus' ) . '</span>';
		}
		echo '</td>';

		echo '<td class="tickets_name">' . $ticket->name . '</td>';

		echo '<td class="tickets_price">' . $this->get_price_html( $ticket->ID ) . '</td>';

		echo '<td class="tickets_description">' . $ticket->description . '</td>';

		echo '</tr>';

		if ( class_exists( 'Tribe__Tickets_Plus__Attendees_List' ) && ! Tribe__Tickets_Plus__Attendees_List::is_hidden_on( get_the_ID() ) ) {
			echo
			'<tr class="tribe-tickets-attendees-list-optout">' .
				'<td colspan="4">' .
					'<input type="checkbox" name="wpec_tickets_attendees_optout[]" id="tribe-tickets-attendees-list-optout-wpec">' .
					'<label for="tribe-tickets-attendees-list-optout-wpec">' .
						esc_html__( 'Don\'t list me on the public attendee list', 'event-tickets' ) .
					'</label>' .
				'</td>' .
			'</tr>';
		}

		include Tribe__Tickets_Plus__Main::instance()->get_template_hierarchy( 'meta.php' );
	}
}

$contents = ob_get_clean();

if ( $is_there_any_product ) { ?>

	<?php if ( $is_there_any_product_to_sell && ( get_option( 'addtocart_or_buynow' ) != 1 ) ) { ?>
		<form action="<?php echo esc_url( get_option( 'shopping_cart_url' ) ); ?>" class="cart" method="post" enctype="multipart/form-data">
	<?php } else { ?>
		<div class="cart">
	<?php } 
            if(ICL_LANGUAGE_CODE=='fr') {
                ?><h2 class="tribe-events-tickets-title">Inscrivez vous a cette evenement</h2><?
            }
            else{
                ?><h2 class="tribe-events-tickets-title">Register for this event</h2><?
            }

			?><h2 class="tribe-events-tickets-title"><?php esc_html_e( 'Tickets', 'event-tickets-plus' ); ?></h2>

			<table width="100%" class="tribe-events-tickets">

				<?php echo $contents; ?>

				<?php if ( $is_there_any_product_to_sell && ( get_option( 'addtocart_or_buynow' ) != 1 ) ) { ?>
					<tr>
						<td colspan="4" class="wpeccommerce">
							<?php if ( $must_login ): ?>
								<?php include Tribe__Tickets_Plus__Main::instance()->get_template_hierarchy( 'login-to-purchase' ); ?>
							<?php else: ?>
								<button type="submit" class="button alt">
									<?php esc_html_e( 'Add to cart', 'event-tickets-plus' ); ?>
								</button>
							<?php endif; ?>
						</td>
					</tr>
				<?php } ?>
			</table>

	<?php if ( $is_there_any_product_to_sell && ( get_option( 'addtocart_or_buynow' ) != 1 ) ) {
		// @todo remove safeguard in 4.3 or later
		if ( $unavailability_messaging ) {
			// If we have rendered tickets there is generally no need to display a 'tickets unavailable' message
			// for this post
			$this->do_not_show_tickets_unavailable_message();
		}
		?>
		</form>
	<?php
	} else {
		?>
		</div><!-- .cart -->
		<?php
	}

} else {
	// @todo remove safeguard in 4.3 or later
	if ( $unavailability_messaging ) {
		$unavailability_message = $this->get_tickets_unavailable_message( $tickets );

		// if there isn't an unavailability message, bail
		if ( ! $unavailability_message ) {
			return;
		}
	}
}
