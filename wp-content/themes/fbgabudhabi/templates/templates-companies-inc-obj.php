<?php

if ( $the_query->have_posts() )
{
	while ( $the_query->have_posts() ) : $the_query->the_post(); 
		$comp_id = get_the_ID();
		$src = get_field('company_picture', $comp_id);

		if (strlen($src) == 0 ) {
			$src = content_url() . "/uploads/2017/01/new-FBG-logo_transparent.png";
		}

		$id_company = get_the_ID();
		$user_detail = "";
		$user_count = 0;

		$result5 = $wpdb->get_results("SELECT * FROM wp_xpksy4skky_usermeta WHERE meta_key='company' AND meta_value={$id_company}");

		foreach ($result5 as $row5)
		{
			$user_id = $row5->user_id;
			$approved = get_user_meta( $user_id , 'pw_user_status', true );
			$paid_unpaid = get_user_meta( $user_id , 'paid_unpaid', true );

			if ($approved != "approved" || $paid_unpaid !="paid") {
				continue;
			}

			$user_count++;

			if ($user_count > 4) {
				break;
			}

			$user_l = get_user_meta( $user_id , 'first_name', true );
			$user_f = get_user_meta( $user_id , 'last_name', true );
			$position = get_user_meta( $user_id , 'position', true );
			$user_picture = get_field('user_picture', 'user_'.$user_id);

			if (strlen($user_picture) == 0) {
				$user_picture = content_url() . "/uploads/2017/01/new-FBG-logo_transparent.png";
			}

			$user_detail .= "
			<div class='member_item'>
				<div class='member_pic member_set_avatar'>
				<a href='" . get_permalink(14559) . "?uid=" . $user_id . "'><img src='{$user_picture}' alt='user_pic'></a>
				</div>
				<div class='member_info'>
					<div class='member_name'><a href='" . get_permalink(14559) . "?uid=" . $user_id . "'>{$user_l} {$user_f}</a></div>
					<div class='member_occupation'>{$position}</div>
				</div>
			</div>";
	}

	?>

	<div class="item_grid">
		<div class="company_wrap">
			<a class="company_img" href="<?php the_permalink(); ?>">
				<img src="<?php echo $src; ?>" alt="picture">
			</a>
			<h5 class="company_name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>

			<?php 
			if ($user_count > 0)
			{ 
	            if (ICL_LANGUAGE_CODE == 'fr') {
	                $strShowMember = "VOIR LES MEMBRES";
	            } else {
                	$strShowMember = "SHOW MEMBERS";
            	}
            ?>
				<div class="show_members_button">
					<a href="<?php the_permalink(); ?>" style="color:#FFF;"><?=$strShowMember?></a>
				</div>
			<?php } ?>
		</div>
		<div class="dd_info">
			<?php
			if (is_user_logged_in() ) {
				echo $user_detail;
			} else {
			?>
			<div class="set_login_img set_new_style_img height_companies" style="float:none!important; margin: auto!important;">
				<a>
					<?php 
					$image = get_field('image_register_companies', 'option');
	                if (empty($image)) {
	                    $image['url'] = "http://fbgabudhabi.com/wp-content/uploads/2017/02/1a.jpg";
	                }
					if (!empty($image) ) { ?>
						<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
					<?php } ?>
				</a>

				<div class="set_content_on_img_login p_15">
					<div class="resgister_now">
						<span class="not_a_member_login m_change_fsz">
							<?php the_field('text_register_companies', 'option'); ?>
						</span>
					</div>
					<div class="disp_flex_wrapper">
						<div class="button_create_account mg_t_30 style_new_button resize_buttons_m">
							<a href="<?echo get_site_url()."/register"?>">
								Create account
							</a>
						</div>
						<div class="button_create_account mg_t_30 style_new_button resize_buttons_m">
							<a href="<?echo get_site_url()."/login"?>">
								Log in
							</a>
						</div>
					</div>
				</div>
			</div>
		<div class="clearfix"></div>
		<?php
		}
		?>
		</div>
	</div>
	<div class="item_grid">
		<div class="company_info_wrap">
			<h6 class="company_name_2">
				<a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h6>
			<?php if( have_rows('company_sector_obj') ): ?>
				<?php $i=0; ?>
				<ul style="margin-bottom: 10px;">
					<?php while( have_rows('company_sector_obj') ): the_row();
						if ($i >= 3) { break; }
						$sector_obj = get_sub_field('sector_obj');
					?>
						<p class="company_row_info">
							<a class="sector_link" href="<?php echo esc_url( add_query_arg( 'cat', $sector_obj->post_name, get_permalink(13750) ) ); ?>">
								<?php echo $sector_obj->post_title; ?>
							</a>
						</p>
						<?php $i++; ?>
					<?php endwhile; ?>
				</ul>
			<?php endif;

			if (is_user_logged_in() )
			{
				$phone = get_field('phone', $id_company);
				$fax = get_field('fax', $id_company);
				$website = get_field('website', $id_company);
				$city = get_field('city', $id_company);

				$phone_icon = '<i class="fa fa-phone" aria-hidden="true"></i>';
				$fax_icon = '<i class="fa fa-print" aria-hidden="true"></i>';
				$web_icon = '<i class="fa fa-globe" aria-hidden="true"></i>';
				$city_icon = '<i class="fa fa-map-marker" aria-hidden="true"></i>';

				$phone_display = $fax_display = $city_display = $web_display = "";

				if(strlen($phone) == 0) { $phone = ""; $phone_icon = ''; $phone_display = ' style="display:none" '; }
				if(strlen($fax) == 0) { $fax = ""; $fax_icon = ''; $fax_display = ' style="display:none" '; }

				if (strlen($city) == 0) {
					$city = ""; $city_icon = ''; $city_display = ' style="display:none" ';
				} else {
					$title = " United Arab Emirates " . get_the_title();
					$title = str_replace(array('&',',','-'), array('+','+','+'), $title);
					$city = "<a href='https://www.google.ae/maps/search/{$title}' target='_blank' class='bottom_buttons'>{$city_icon}</a>";
				}

				if (strlen($website) == 0) {
					$website = $web_icon = "";
					$web_display = ' style="display:none" '; 
				} else {
					$parsed = parse_url($website);
					if (empty($parsed['scheme'])) {
						$website = 'http://' . ltrim($website, '/');
					}
					$website = "<a href='{$website}' target='_blank' class='bottom_buttons'>{$web_icon}</a>";
				}

				$company_details = <<<EOF
				<a onclick="swal1('Phone', '{$phone}', 'OK');" class="bottom_buttons" href="javascript:void(0)" {$phone_display}>
					{$phone_icon}
				</a>
				<a onclick="swal1('Fax', '{$fax}', 'OK');" class="bottom_buttons" href="javascript:void(0)" {$fax_display}>
					{$fax_icon}
				</a>
				
				{$website}
			
				{$city}
EOF;
			} else {
				$link_register = "Click <a href='" . get_permalink(13760) . "'>here</a> and Become a member!";
				$company_details = <<<EOF
				<a  onclick="swal1('Phone', `{$link_register}`, 'OK');" class="bottom_buttons" href="javascript:void(0)">
					<i class="fa fa-phone" aria-hidden="true"></i>
				</a>
				<a  onclick="swal1('Fax', `{$link_register}`, 'OK');" class="bottom_buttons" href="javascript:void(0)">
					<i class="fa fa-print" aria-hidden="true"></i>
				</a>
				<a  onclick="swal1('Web', `{$link_register}`, 'OK');" class="bottom_buttons" href="javascript:void(0)">
					<i class="fa fa-globe" aria-hidden="true"></i>
				</a>
				<a  onclick="swal1('City', `{$link_register}`, 'OK');" class="bottom_buttons" href="javascript:void(0)">
					<i class="fa fa-map-marker" aria-hidden="true"></i>
				</a>
EOF;
			}
?>	
				<div>
					<?php echo $company_details; ?>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
<?php } else { ?>
<div class="item_grid">
	<h2>No results found!</h2>
</div>
<?php } ?>