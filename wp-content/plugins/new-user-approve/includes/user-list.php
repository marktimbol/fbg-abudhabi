<?php

class pw_new_user_approve_user_list {

	/**
	 * The only instance of pw_new_user_approve_user_list.
	 *
	 * @var pw_new_user_approve_user_list
	 */
	private static $instance;

	/**
	 * Returns the main instance.
	 *
	 * @return pw_new_user_approve_user_list
	 */
	public static function instance() {
		if ( !isset( self::$instance ) ) {
			self::$instance = new pw_new_user_approve_user_list();
		}
		return self::$instance;
	}

	private function __construct() {
		// Actions
		add_action( 'load-users.php', array( $this, 'update_action' ) );
		add_action( 'restrict_manage_users', array( $this, 'status_filter' ), 10, 1 );
		add_action( 'pre_user_query', array( $this, 'filter_by_status' ) );
		add_action( 'admin_footer-users.php', array( $this, 'admin_footer' ) );
		add_action( 'load-users.php', array( $this, 'bulk_action' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'show_user_profile', array( $this, 'profile_status_field' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_status_field' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_profile_status_field' ) );
		add_action( 'admin_menu', array( $this, 'pending_users_bubble' ), 999 );

		// Filters
		add_filter( 'user_row_actions', array( $this, 'user_table_actions' ), 10, 2 );
		add_filter( 'manage_users_columns', array( $this, 'add_column' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'status_column' ), 10, 3 );
	}

	/**
	 * Update the user status if the approve or deny link was clicked.
	 *
	 * @uses load-users.php
	 */
	public function update_action() {
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'approve', 'deny' ) ) && !isset( $_GET['new_role'] ) ) {
			check_admin_referer( 'new-user-approve' );

			$sendback = remove_query_arg( array( 'approved', 'denied', 'deleted', 'ids', 'pw-status-query-submit', 'new_role' ), wp_get_referer() );
			if ( !$sendback )
				$sendback = admin_url( 'users.php' );

			$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );

			$status = sanitize_key( $_GET['action'] );
			$user = absint( $_GET['user'] );

			pw_new_user_approve()->update_user_status( $user, $status );

			if ( $_GET['action'] == 'approve' ) {
				$sendback = add_query_arg( array( 'approved' => 1, 'ids' => $user ), $sendback );
			} else {
				$sendback = add_query_arg( array( 'denied' => 1, 'ids' => $user ), $sendback );
			}

			wp_redirect( $sendback );
			exit;
		}
	}

	/**
	 * Add the approve or deny link where appropriate.
	 *
	 * @uses user_row_actions
	 * @param array $actions
	 * @param object $user
	 * @return array
	 */
	public function user_table_actions( $actions, $user ) {
		if ( $user->ID == get_current_user_id() )
			return $actions;

		$user_status = pw_new_user_approve()->get_user_status( $user->ID );

		$approve_link = add_query_arg( array( 'action' => 'approve', 'user' => $user->ID ) );
		$approve_link = remove_query_arg( array( 'new_role' ), $approve_link );
		$approve_link = wp_nonce_url( $approve_link, 'new-user-approve' );

		$deny_link = add_query_arg( array( 'action' => 'deny', 'user' => $user->ID ) );
		$deny_link = remove_query_arg( array( 'new_role' ), $deny_link );
		$deny_link = wp_nonce_url( $deny_link, 'new-user-approve' );

		$approve_action = '<a href="' . esc_url( $approve_link ) . '">' . __( 'Approve', 'new-user-approve' ) . '</a>';
		$deny_action = '<a href="' . esc_url( $deny_link ) . '">' . __( 'Deny', 'new-user-approve' ) . '</a>';

		if ( $user_status == 'pending' ) {
			$actions[] = $approve_action;
			$actions[] = $deny_action;
		} else if ( $user_status == 'approved' ) {
			$actions[] = $deny_action;
		} else if ( $user_status == 'denied' ) {
			$actions[] = $approve_action;
		}

		return $actions;
	}

	/**
	 * Add the status column to the user table
	 *
	 * @uses manage_users_columns
	 * @param array $columns
	 * @return array
	 */
	public function add_column( $columns ) {
		$the_columns['pw_user_status'] = __( 'Status', 'new-user-approve' );

		$newcol = array_slice( $columns, 0, -1 );
		$newcol = array_merge( $newcol, $the_columns );
		$columns = array_merge( $newcol, array_slice( $columns, 1 ) );

		return $columns;
	}

	/**
	 * Show the status of the user in the status column
	 *
	 * @uses manage_users_custom_column
	 * @param string $val
	 * @param string $column_name
	 * @param int $user_id
	 * @return string
	 */
	public function status_column( $val, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'pw_user_status' :
				$status = pw_new_user_approve()->get_user_status( $user_id );
				if ( $status == 'approved' ) {
					$status_i18n = __( 'approved', 'new-user-approve' );
				} else if ( $status == 'denied' ) {
					$status_i18n = __( 'denied', 'new-user-approve' );
				} else if ( $status == 'pending' ) {
					$status_i18n = __( 'pending', 'new-user-approve' );
				}
				return $status_i18n;
				break;

			default:
		}

		return $val;
	}

	/**
	 * Add a filter to the user table to filter by user status
	 *
	 * @uses restrict_manage_users
	 */
	public function status_filter( $which ) {
		$id = 'new_user_approve_filter-' . $which;

        $idOverview = 'new_user_approve_overview-' . $which;

        $idPaid = 'new_user_approve_payment-' . $which;

        $idBadge = 'new_user_approve_badge-' . $which;



		$filter_button = submit_button( __( 'Filter', 'new-user-approve' ), 'button', 'pw-status-query-submit', false, array( 'id' => 'pw-status-query-submit' ) );
		$filtered_status = $this->selected_status();

		?>
		<label class="screen-reader-text" for="<?php echo $id ?>"><?php _e( 'View all users', 'new-user-approve' ); ?></label>
		<select id="<?php echo $id ?>" name="<?php echo $id ?>" style="float: none; margin: 0 0 0 15px;">
			<option value=""><?php _e( 'View all users', 'new-user-approve' ); ?></option>
		<?php foreach ( pw_new_user_approve()->get_user_statuses() as $status => $users ) : ?>
			<?php if ( count( $users ) ) : ?>
			<option value="<?php echo esc_attr( $status ); ?>"<?php selected( $status, $filtered_status ); ?>><?php echo esc_html( $status ); ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
		</select>
		<?php //echo apply_filters( 'new_user_approve_filter_button', $filter_button ); ?>
		<style>
			#pw-status-query-submit {
				float: right;
				margin: 2px 0 0 5px;
			}
		</style>
        <?
        		//$filter_button1 = submit_button( __( 'Filter', 'new-user-approve' ), 'button', 'pw-overview-query-submit1', false, array( 'id' => 'pw-overview-query-submit1' ) );

        $arrYesNo = array(
                    "0" => "Member Overview",
                    "1" => "Yes",
                    "2" => "No"

            )
        ?>
        <?
        if (true) {
	        $filtered_status = $this->selected_status();
	        $filterMember = $this->selected_memberview();
	        $filtered_payment = $this->selected_payment();
        	$filtered_badge = $this->selected_badge();

        ?>
        	<label class="screen-reader-text" for="lstmemberoverview">Member Overview</label>
	        <select id="<?=$idOverview?>" name="<?=$idOverview?>" style="float: none; margin: 0 0 0 15px;">
		        <?php
		            foreach($arrYesNo as $key => $value){
		                ?><option value="<?=$key?>" <?if($filterMember == $key){?>selected="selected"<?}?>><?=$value?></option><?
		            }
		        ?>
        	</select>
	        <style>
				#pw-status-query-submit {
					float: right;
					margin: 2px 0 0 5px;
				}
			</style>
	         <?php //echo apply_filters( 'new_user_approve_filter_button', $filter_button ); ?>
	        <select id="<?=$idPaid?>" name="<?=$idPaid?>"  style="clear:both; float:none;">
	            <option value="">Payment Status</option>
	            <option value="1" <?if($filtered_payment == "1"){?>selected="selected"<?}?>>Paid</option>
	             <option value="2" <?if($filtered_payment == "2"){?>selected="selected"<?}?>>Unpaid</option>
	        </select>

	        <select id="<?=$idBadge?>" name="<?=$idBadge?>"  style="clear:both; float:none;">
	            <option value="">Badge Name</option>
	            <option value="1" <?if($filtered_badge == "1"){?>selected="selected"<?}?>>Featured</option>
	             <option value="2" <?if($filtered_badge == "2"){?>selected="selected"<?}?>>Sponsored</option>
	        </select>
        	<?php echo apply_filters( 'new_user_approve_filter_button', $filter_button );  ?>
    <?php }
	}

	/**
	 * Modify the user query if the status filter is being used.
	 *
	 * @uses pre_user_query
	 * @param $query
	 */
	public function filter_by_status( $query ) {
		global $wpdb;

		if ( !is_admin() ) {
			return;
		}

		$screen = get_current_screen();
		if ( isset( $screen ) && 'users' != $screen->id ) {
			return;
		}

         $filterMember = $this->selected_memberview();

         $filtered_payment = $this->selected_payment();
         $filtered_badge = $this->selected_badge();



		if ( $this->selected_status() != null ) {
			$filter = $this->selected_status();

			 $query->query_from .= " INNER JOIN {$wpdb->usermeta} ON ( {$wpdb->users}.ID = wp_xpksy4skky_usermeta.user_id ) LEFT JOIN wp_xpksy4skky_usermeta  AS member_overview 
             ON {$wpdb->users}.ID = member_overview.user_id        
			AND member_overview.meta_key='members_overview' 

            LEFT JOIN wp_xpksy4skky_usermeta AS payment_status
                             ON {$wpdb->users}.ID = payment_status.user_id        
                            AND payment_status.meta_key='paid_unpaid'
             LEFT JOIN wp_xpksy4skky_usermeta AS badge_status
                             ON {$wpdb->users}.ID = badge_status.user_id        
                            AND badge_status.meta_key='badge_name' ";

			if ( 'approved' == $filter ) {
				$query->query_fields = "DISTINCT SQL_CALC_FOUND_ROWS {$wpdb->users}.ID";
				$query->query_from .= " LEFT JOIN {$wpdb->usermeta} AS mt1 ON ({$wpdb->users}.ID = mt1.user_id AND mt1.meta_key = 'pw_user_status')";
				



                
                $query->query_where .= " AND ( ( wp_xpksy4skky_usermeta.meta_key = 'pw_user_status' AND CAST(wp_xpksy4skky_usermeta.meta_value AS CHAR) = 'approved' ) OR mt1.user_id IS NULL )";

                if(isset($filterMember)){
                    if($filterMember == '1'){
                        $query->query_where .= " AND member_overview.meta_key = 'members_overview' AND member_overview.meta_value = 'a:1:{i:0;s:7:\"Display\";}' ";
                    }
                }

                if(isset($filtered_payment)){
                    if($filtered_payment == 1){
                        $query->query_where .= " AND payment_status.meta_key = 'paid_unpaid' AND payment_status.meta_value = 'paid' ";
                    }
                    else if($filtered_payment == 2){
                        $query->query_where .= " AND payment_status.meta_key = 'paid_unpaid' AND payment_status.meta_value = 'unpaid' ";
                    }
                }

                if(isset($filtered_badge)){
                    if($filtered_badge == 1){
                        $query->query_where .= " AND badge_status.meta_key = 'badge_name' AND badge_status.meta_value = 'Featured' ";
                    }
                    else if($filtered_badge == 2){
                        $query->query_where .= " AND badge_status.meta_key = 'badge_name' AND badge_status.meta_value = 'Sponsored' ";
                    }
                }


			} else {

                
                

                //$query->query_where .= " AND ( ( wp_xpksy4skky_usermeta.meta_key = 'pw_user_status' AND CAST(wp_xpksy4skky_usermeta.meta_value AS CHAR) = 'approved' ) OR mt1.user_id IS NULL )";

				$query->query_where .= " AND ( (wp_xpksy4skky_usermeta.meta_key = 'pw_user_status' AND CAST(wp_xpksy4skky_usermeta.meta_value AS CHAR) = '{$filter}') )";

                if(isset($filterMember)){
                    if($filterMember == '1'){
                        $query->query_where .= " AND member_overview.meta_key = 'members_overview' AND member_overview.meta_value = 'a:1:{i:0;s:7:\"Display\";}' ";
                    }
                }

                 if(isset($filtered_payment)){
                    if($filtered_payment == 1){
                        $query->query_where .= " AND payment_status.meta_key = 'paid_unpaid' AND payment_status.meta_value = 'paid' ";
                    }
                    else if($filtered_payment == 2){
                        $query->query_where .= " AND payment_status.meta_key = 'paid_unpaid' AND payment_status.meta_value = 'unpaid' ";
                    }
                }

                if(isset($filtered_badge)){
                    if($filtered_badge == 1){
                        $query->query_where .= " AND badge_status.meta_key = 'badge_name' AND badge_status.meta_value = 'Featured' ";
                    }
                    else if($filtered_badge == 2){
                        $query->query_where .= " AND badge_status.meta_key = 'badge_name' AND badge_status.meta_value = 'Sponsored' ";
                    }
                }


              

			}


		}
        else{
            	 $query->query_from .= "  LEFT JOIN wp_xpksy4skky_usermeta  AS member_overview
			    ON {$wpdb->users}.ID = member_overview.user_id        
			    AND member_overview.meta_key='members_overview' 
                LEFT JOIN wp_xpksy4skky_usermeta AS payment_status
                             ON {$wpdb->users}.ID = payment_status.user_id        
                            AND payment_status.meta_key='paid_unpaid' 
                            LEFT JOIN wp_xpksy4skky_usermeta AS badge_status
                             ON {$wpdb->users}.ID = badge_status.user_id        
                            AND badge_status.meta_key='badge_name' ";

               // $query->query_where .= " AND ( ( wp_xpksy4skky_usermeta.meta_key = 'pw_user_status' AND CAST(wp_xpksy4skky_usermeta.meta_value AS CHAR) = 'approved' ) OR mt1.user_id IS NULL )";

                if(isset($filterMember)){
                    if($filterMember == '1'){
                        $query->query_where .= " AND member_overview.meta_key = 'members_overview' AND member_overview.meta_value = 'a:1:{i:0;s:7:\"Display\";}' ";
                    }
                }

                if(isset($filtered_payment)){
                    if($filtered_payment == 1){
                        $query->query_where .= " AND payment_status.meta_key = 'paid_unpaid' AND payment_status.meta_value = 'paid' ";
                    }
                    else if($filtered_payment == 2){
                        $query->query_where .= " AND payment_status.meta_key = 'paid_unpaid' AND payment_status.meta_value = 'unpaid' ";
                    }
                }

                 if(isset($filtered_badge)){
                    if($filtered_badge == 1){
                        $query->query_where .= " AND badge_status.meta_key = 'badge_name' AND badge_status.meta_value = 'Featured' ";
                    }
                    else if($filtered_badge == 2){
                        $query->query_where .= " AND badge_status.meta_key = 'badge_name' AND badge_status.meta_value = 'Sponsored' ";
                    }
                }

        }

       // $filterMember = $this->selected_memberview();




        
	}

	private function selected_status() {
		if ( ! empty( $_REQUEST['new_user_approve_filter-top'] ) || ! empty( $_REQUEST['new_user_approve_filter-bottom'] ) ) {
			return esc_attr( ( ! empty( $_REQUEST['new_user_approve_filter-top'] ) ) ? $_REQUEST['new_user_approve_filter-top'] : $_REQUEST['new_user_approve_filter-bottom'] );
		}

		return null;
	}


    private function selected_payment() {
		if ( ! empty( $_REQUEST['new_user_approve_payment-top'] ) || ! empty( $_REQUEST['new_user_approve_payment-bottom'] ) ) {
			return esc_attr( ( ! empty( $_REQUEST['new_user_approve_payment-top'] ) ) ? $_REQUEST['new_user_approve_payment-top'] : $_REQUEST['new_user_approve_payment-bottom'] );
		}

		return null;
	}

    // selected_badge
   
   private function selected_badge() {
		if ( ! empty( $_REQUEST['new_user_approve_badge-top'] ) || ! empty( $_REQUEST['new_user_approve_badge-bottom'] ) ) {
			return esc_attr( ( ! empty( $_REQUEST['new_user_approve_badge-top'] ) ) ? $_REQUEST['new_user_approve_badge-top'] : $_REQUEST['new_user_approve_badge-bottom'] );
		}

		return null;
	}



    private function selected_memberview() {
		if ( ! empty( $_REQUEST['new_user_approve_overview-top'] ) || ! empty( $_REQUEST['new_user_approve_overview-bottom'] ) ) {
			return esc_attr( ( ! empty( $_REQUEST['new_user_approve_overview-top'] ) ) ? $_REQUEST['new_user_approve_overview-top'] : $_REQUEST['new_user_approve_overview-top'] );
		}

		return null;
	}

	/**
	 * Use javascript to add the ability to bulk modify the status of users.
	 *
	 * @uses admin_footer-users.php
	 */
	public function admin_footer() {
		$screen = get_current_screen();

		if ( $screen->id == 'users' ) : ?>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {
					$('<option>').val('approve').text('<?php _e( 'Approve', 'new-user-approve' )?>').appendTo("select[name='action']");
					$('<option>').val('approve').text('<?php _e( 'Approve', 'new-user-approve' )?>').appendTo("select[name='action2']");

					$('<option>').val('deny').text('<?php _e( 'Deny', 'new-user-approve' )?>').appendTo("select[name='action']");
					$('<option>').val('deny').text('<?php _e( 'Deny', 'new-user-approve' )?>').appendTo("select[name='action2']");
				});
			</script>
		<?php endif;
	}

	/**
	 * Process the bulk status updates
	 *
	 * @uses load-users.php
	 */
	public function bulk_action() {
		$screen = get_current_screen();

		if ( $screen->id == 'users' ) {

			// get the action
			$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
			$action = $wp_list_table->current_action();

			$allowed_actions = array( 'approve', 'deny' );
			if ( !in_array( $action, $allowed_actions ) ) {
				return;
			}

			// security check
			check_admin_referer( 'bulk-users' );

			// make sure ids are submitted
			if ( isset( $_REQUEST['users'] ) ) {
				$user_ids = array_map( 'intval', $_REQUEST['users'] );
			}

			if ( empty( $user_ids ) ) {
				return;
			}

			$sendback = remove_query_arg( array( 'approved', 'denied', 'deleted', 'ids', 'new_user_approve_filter', 'new_user_approve_filter2', 'pw-status-query-submit', 'new_role' ), wp_get_referer() );
			if ( !$sendback ) {
				$sendback = admin_url( 'users.php' );
			}

			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );

			switch ( $action ) {
				case 'approve':
					$approved = 0;
					foreach ( $user_ids as $user_id ) {
						pw_new_user_approve()->update_user_status( $user_id, 'approve' );
						$approved++;
					}

					$sendback = add_query_arg( array( 'approved' => $approved, 'ids' => join( ',', $user_ids ) ), $sendback );
					break;

				case 'deny':
					$denied = 0;
					foreach ( $user_ids as $user_id ) {
						pw_new_user_approve()->update_user_status( $user_id, 'deny' );
						$denied++;
					}

					$sendback = add_query_arg( array( 'denied' => $denied, 'ids' => join( ',', $user_ids ) ), $sendback );
					break;

				default:
					return;
			}

			$sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view' ), $sendback );

			wp_redirect( $sendback );
			exit();
		}
	}

	/**
	 * Show a message on the users page if a status has been updated.
	 *
	 * @uses admin_notices
	 */
	public function admin_notices() {
		$screen = get_current_screen();

		if ( $screen->id != 'users' ) {
			return;
		}

		$message = null;

		if ( isset( $_REQUEST['denied'] ) && (int) $_REQUEST['denied'] ) {
			$denied = esc_attr( $_REQUEST['denied'] );
			$message = sprintf( _n( 'User denied.', '%s users denied.', $denied, 'new-user-approve' ), number_format_i18n( $denied ) );
		}

		if ( isset( $_REQUEST['approved'] ) && (int) $_REQUEST['approved'] ) {
			$approved = esc_attr( $_REQUEST['approved'] );
			$message = sprintf( _n( 'User approved.', '%s users approved.', $approved, 'new-user-approve' ), number_format_i18n( $approved ) );
		}

		if ( !empty( $message ) ) {
			echo '<div class="updated"><p>' . $message . '</p></div>';
		}
	}

	/**
	 * Display the dropdown on the user profile page to allow an admin to update the user status.
	 *
	 * @uses show_user_profile
	 * @uses edit_user_profile
	 * @param object $user
	 */
	public function profile_status_field( $user ) {
		if ( $user->ID == get_current_user_id() ) {
			return;
		}

		$user_status = pw_new_user_approve()->get_user_status( $user->ID );
		?>
		<table class="form-table">
			<tr>
				<th><label for="new_user_approve_status"><?php _e( 'Access Status', 'new-user-approve' ); ?></label>
				</th>
				<td>
					<select id="new_user_approve_status" name="new_user_approve_status">
						<?php if ( $user_status == 'pending' ) : ?>
							<option value=""><?php _e( '-- Status --', 'new-user-approve' ); ?></option>
						<?php endif; ?>
						<?php foreach ( array( 'approved', 'denied' ) as $status ) : ?>
							<option
								value="<?php echo esc_attr( $status ); ?>"<?php selected( $status, $user_status ); ?>><?php echo esc_html( $status ); ?></option>
						<?php endforeach; ?>
					</select>
					<span
						class="description"><?php _e( 'If user has access to sign in or not.', 'new-user-approve' ); ?></span>
					<?php if ( $user_status == 'pending' ) : ?>
						<br/><span
							class="description"><?php _e( 'Current user status is <strong>pending</strong>.', 'new-user-approve' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
		</table>
	<?php
	}

	/**
	 * Save the user status when updating from the user profile.
	 *
	 * @uses edit_user_profile_update
	 * @param int $user_id
	 * @return bool
	 */
	public function save_profile_status_field( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( !empty( $_POST['new_user_approve_status'] ) ) {
			$new_status = esc_attr( $_POST['new_user_approve_status'] );

			if ( $new_status == 'approved' )
				$new_status = 'approve'; else if ( $new_status == 'denied' )
				$new_status = 'deny';

			pw_new_user_approve()->update_user_status( $user_id, $new_status );
		}
	}

	/**
	 * Add bubble for number of users pending to the user menu
	 *
	 * @uses admin_menu
	 */
	public function pending_users_bubble() {
		global $menu;

		$users = pw_new_user_approve()->get_user_statuses();

		// Count Number of Pending Members
		$pending_users = count( $users['pending'] );

		// Make sure there are pending members
		if ( $pending_users > 0 ) {
			// Locate the key of
			$key = $this->recursive_array_search( 'users.php', $menu );

			// Not found, just in case
			if ( ! $key ) {
				return;
			}

			// Modify menu item
			$menu[$key][0] .= sprintf( '<span class="update-plugins count-%1$s" style="background-color:white;color:black;margin-left:5px;"><span class="plugin-count">%1$s</span></span>', $pending_users );
		}
	}

	/**
	 * Recursively search the menu array to determine the key to place the bubble.
	 * 
	 * @param $needle
	 * @param $haystack
	 * @return bool|int|string
	 */
	public function recursive_array_search( $needle, $haystack ) {
		foreach ( $haystack as $key => $value ) {
			$current_key = $key;
			if ( $needle === $value || ( is_array( $value ) && $this->recursive_array_search( $needle, $value ) !== false ) ) {
				return $current_key;
			}
		}
		return false;
	}
}

function pw_new_user_approve_user_list() {
	return pw_new_user_approve_user_list::instance();
}

pw_new_user_approve_user_list();
