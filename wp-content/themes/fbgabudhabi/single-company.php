<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<?php get_header(); ?>

<?php $full_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.3.2/sweetalert2.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/6.3.2/sweetalert2.js"></script>

<style>
	.fusion-footer {
	   padding-left:240px !important;
	   margin-top:-20px !important;
	}

	.members_overview_title {
		max-width: 260px;
	}

	html[lang="fr-FR"] .members_overview_title  {
		max-width: 300px;
	}

	@media (max-width: 414px)
	{
	   .fusion-footer {
	        padding-left:0px !important;
	   }

	   .m_events_in_future {
		   width:100% !important;
	       max-width:100% !important;
		}
	    .m_name_of_person p {
	      text-align:center !important;
	    }
	    .owl-carousel .owl-item img {
	       object-fit: contain !important;
	    }
	}

@media only screen 
	and (min-width: 414px) 
  	and (max-width: 736px) 
	{ 
	    .fusion-footer {
	        padding-left:0px !important;
	        margin-top:0px !important;
	    }

	   .m_events_in_future {
		   width:100% !important;
	       max-width:100% !important;
		}
	    .m_name_of_person p {
	      text-align:center !important;
	    }
	    .owl-carousel .owl-item img {
	       object-fit: contain !important;
	    }
	}

	@media only screen 
  		and (min-width: 414px) 
  		and (max-width: 768px) 
	{ 
	   .fusion-footer {
			padding-left:0px !important;
		}
	}
</style>

<div class="banner_top_on_list_categories" style="margin-bottom:-5px !important;">
	<div class="set_dimensions_to_banner_top">
		<img src="<?php echo content_url(); ?>/uploads/2017/01/banner_top_img.jpg" alt="">
		<div class="include_search_on_list_categories_top">
			<form action="<?php echo get_permalink(13750); ?>">
				<?php
                if (ICL_LANGUAGE_CODE == 'fr') {
                    $searchLabel = "Rechercher une entreprise";
                } else {
                    $searchLabel = "Search for companies";
                }
				?>
				<input type="text" name="q" placeholder="<?=$searchLabel?>" class="style_input_search_on_page_list" value="<?php if(isset($_GET['q'])) echo $_GET['q'];?>">
				<div class="include_button_submit_on_page_list">
					<button type="submit">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- company -->
<div class="title_categories">
	<div class="m_events_in_future style_events_on_page">
		<h2><?php echo get_the_title(); ?></h2>
	</div>
</div>

<?php
$company_id = $id_companie = get_the_ID();
$link_register = "";
$logged_in_user = get_current_user_id();

$result10 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$logged_in_user}' AND meta_key='paid_unpaid'");	

foreach ($result10 as $row10) {
	$paid_un_paid=$row10->meta_value;
}

if (is_user_logged_in() && $paid_un_paid != "unpaid" )
{
	$phone = get_field('phone', $id_companie);
	$fax = get_field('fax', $id_companie);
	$website = get_field('website', $id_companie);
	$city = get_field('city', $id_companie);

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
		$title = " United Arab Emirates " . get_the_title($id_companie);
		$title = str_replace(array('&',',','-'), array('+','+','+'), $title);
		$city = "<a href='https://www.google.ae/maps/search/{$title}' target='_blank' class='bottom_buttons'>{$city_icon}</a>";
	}

	if (strlen($website) == 0 ) {
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
} elseif ($paid_un_paid=="unpaid") {
	$link_register = "Please <a href='javascript:void(0);'>upgrade</a> your plan to have full access!";
	$company_details = <<<EOF
	<a  onclick="swal1('Phone', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-phone" aria-hidden="true"></i>
	</a>
	<a  onclick="swal1('Fax', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-print" aria-hidden="true"></i>
	</a>
	<a  onclick="swal1('Web', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-globe" aria-hidden="true"></i>
	</a>
	<a  onclick="swal1('City', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-map-marker" aria-hidden="true"></i>
	</a>

EOF;
} else {
	$link_register = "<a href='" . get_permalink(13739) . "'>Login</a> or <a href='" . get_permalink(13760) . "'>Become a member!</a> ";
	$company_details = <<<EOF
	<a  onclick="swal1('Phone', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-phone" aria-hidden="true"></i>
	</a>
	<a  onclick="swal1('Fax', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-print" aria-hidden="true"></i>
	</a>
	<a  onclick="swal1('Web', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-globe" aria-hidden="true"></i>
	</a>
	<a  onclick="swal1('City', `{$link_register}`, 'OK');" class="button_sm" href="javascript:void(0)">
		<i class="fa fa-map-marker" aria-hidden="true"></i>
	</a>
EOF;
}

$src = get_field('company_picture', $company_id);
if (strlen($src) == 0 ) {
	$src = content_url() . "/uploads/2017/01/new-FBG-logo_transparent.png";
}
?>

<div class="detail_page">
	<div class="top_company_on_page">
		<div class="style_company_and_individual_name_title txtaC">

		</div>
		<div class="content_on_company_on_page">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="left_details_company_on_page">
						<div class="row">
							<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
								<div class="set_img_company">
									<a class="company_img" href="javascript:void(0)">
										<img src="<?php echo $src; ?>" alt="picture">
									</a>
									<h5 class="company_name"><?php echo get_the_title(); ?></h5>
								</div>
							</div>
							<div class="col-md-7 col-sm-12 col-xs-12 pl_0 pr_0">
								<div class="activity_sectors">
									<div class="sectors">
										<?php if( have_rows('company_sector_obj') ): ?>
											<?php while( have_rows('company_sector_obj') ): the_row(); ?>
												<?php
													$sector_obj = get_sub_field('sector_obj');
												?>
												<a href="<?php echo esc_url( add_query_arg( 'cat', $sector_obj->post_name, get_permalink(13750) ) ); ?>">
													<p><?php echo $sector_obj->post_title; ?></p>
												</a>
											<?php endwhile; ?>
										<?php endif; ?>
									</div>
									<div class="member_period">
										<!-- <p>Member since <span> <?php echo get_the_date("F Y"); ?></span></p> -->
									</div>
									<div class="include_icons">
										<div class="bottom_buttons m_set_mt_20">
											<?php echo $company_details; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="desc_company_individual_name mg_b_50">
						<p><?php echo the_content(); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div><!--added-->
</div>

<!-- <div class="title_categories">
	<div class="m_events_in_future style_events_on_page">
		<h2>Company</h2>
	</div>

</div> -->
<!-- <div class="style_company_and_individual_name_title txtaC">
	<h3 class="white">Members of the company</h3>
</div> -->
<?php
if (ICL_LANGUAGE_CODE == 'fr') {
    $strMember = "Membres de l'entreprise";
} else {
    $strMember = "Members of the company";
}
?>

<div class="title_categories" style="padding-top:40px;">
	<div class="m_events_in_future style_events_on_page">
		<h2 data-fontsize="25" data-lineheight="32"><?=$strMember?></h2>
	</div>
</div>

<?php
$user = get_current_user_id();
$result4 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user}' AND meta_key='paid_unpaid'");	
foreach ($result4 as $row4) {
	$paid_unpaid=$row4->meta_value;
}

if ($user=="" || $paid_unpaid=="unpaid") {
?>
<div class="members_company_on_page p_t_0">
	<div class="content_on_company_on_page dispInline p_b_0">
		<div class="set_login_img set_new_style_img" style="float:none!important; margin: auto!important;">
			<a>
				<?php 
				$image = get_field('image_register_companies', 'option');
                 if (empty($image)) {
                    $image['url'] = "http://fbgabudhabi.com/wp-content/uploads/2017/02/1a.jpg";
                }
				if( !empty($image) ): ?>
					<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
				<?php endif; ?>
			</a>
			<div class="set_content_on_img_login">
				<div class="resgister_now">
					<span class="not_a_member_login">
						<?php the_field('text_register_companies', 'option'); ?>
					</span>
				</div>
				<div class="disp_flex_wrapper">
					<div class="button_create_account mg_t_30 style_new_button">
						<a href="<?echo get_site_url()."/register"?>">
							Create account
						</a>
					</div>
					<div class="button_create_account mg_t_30 style_new_button">
						<a href="<?echo get_site_url()."/login"?>">
							Log in
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php
} else {
?>
<div class="members_company_on_page">
	<div class="content_on_company_on_page">
		<div class="row">
			<?php
			$id_companie=get_the_ID();
			$result5 = $wpdb->get_results("SELECT * FROM wp_xpksy4skky_usermeta WHERE meta_key='company' AND meta_value={$id_companie}");	

			foreach ($result5 as $row5)
			{
				$user_id = $row5->user_id;
				$approved = false;
				$result9 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='pw_user_status' AND meta_value='approved' ");	

				foreach ($result9 as $row9) {
					$approved = true;
				}

				$user_f_un="";

				if (!$approved) {
					continue;
				} 

				$paid_unpaid_wm=false;
				$result22 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='paid_unpaid' AND meta_value='paid' ");	

				foreach ($result22 as $row22) {
					$paid_unpaid_wm = true;
				}

				if(!$paid_unpaid_wm) {
					continue;
				}

				$user_l = get_user_meta( $user_id , 'first_name', true );
				$user_f = get_user_meta( $user_id , 'last_name', true );
				$position = get_user_meta( $user_id , 'position', true );
				$user_picture = get_field('user_picture', 'user_'.$user_id);

				if (strlen($user_picture) == 0) {
					$user_picture = content_url() . "/uploads/2017/01/new-FBG-logo_transparent.png";
				}

				$udata = get_userdata( $user_id );
				$registered = date( "F Y", strtotime($udata->user_registered) );
				$mobile = get_user_meta( $user_id , 'mobile', true );
				$mailadr = $udata->user_email;

				if(strlen($mobile) == 0) $mobile = "Not set..";
				if(strlen($mailadr) == 0) $mailadr = "Not set..";

				if (is_user_logged_in() ) {
					$user_details = <<<EOF
					<a onclick="swal1('Mobile', '{$mobile}', 'OK');" class="button_sm">
						<i class="fa fa-phone" aria-hidden="true"></i>
					</a>
					<a onclick="swal1('Mail', '{$mailadr}', 'OK');" class="button_sm btn_white">
						<i class="fa fa-envelope-o" aria-hidden="true"></i>
					</a>
EOF;
				} else {
					$user_details = <<<EOF
					<a onclick="swal1('Mobile', 'Become a member!', 'OK');" class="button_sm">
						<i class="fa fa-phone" aria-hidden="true"></i>
					</a>
					<a onclick="swal1('Mail', 'Become a member!', 'OK');" class="button_sm btn_white">
						<i class="fa fa-envelope-o" aria-hidden="true"></i>
					</a>
EOF;
				}
				?>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="left_details_company_on_page">
						<div class="row">
							<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
								<div class="set_img_company custom_style_for_img">
									<a class="company_img" href="<?php echo get_permalink(14559) . "?uid=" . $user_id; ?>">
										<img src="<?php echo $user_picture; ?>" alt="">
									</a>
								</div>
							</div>
							<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 pl_0 pr_0">
								<div class="activity_sectors">
									<div class="members">
										<a href="<?php echo get_permalink(14559) . "?uid=" . $user_id; ?>">
											<div class="members_name">
												<p><?php echo $user_l ." ". $user_f; ?></p>
											</div>
										</a>
										<div class="members_function">
											<p><?php echo $position; ?></p>
										</div>
									</div>
									<div class="member_period white">
										<!-- <p>Member since <span> <?php echo $registered;?></span></p> -->
									</div>
									<div class="include_icons">
										<div class="bottom_buttons">
											<?php echo $user_details;?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
<?php
}
?>
	</div>
</div>
</div>
<div class="m_member_to_events mt_0" style="display:none;">
	<div class="m_text_find_event">
		<?php the_field('text_become_a_member_banner', 'option'); ?>
	</div>
	<div class="holding_card">
		<?php
		$image = get_field('image_become_a_member_banner', 'option');
		if( !empty($image) ): ?>
			<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
		<?php endif; ?>
	</div>
</div>
<?php
if (ICL_LANGUAGE_CODE == 'fr') {
    $strMbrOverview = "Aperçu des Membres";
} else {
    $strMbrOverview = "Members Overview";
}
?>

<div class="m_events_in_future members_overview_title" style="margin-top:15px;">
	<h2 style="font-weight:normal !important;"><?=$strMbrOverview?></h2>
</div>

<div class="m_slider_on_page" style="margin-bottom:20px !important;">
	<div class="owl_carousel_slider">
		<?php suggestion_profile(); ?>
	</div>
</div>

<div class="m_due_cols" style="display:none;">
	<div class="m_col1">
		<div class="m_image_col1">
			<?php
			$image = get_field('image_bottom_banner_left', 'option');
			if( !empty($image) ): ?>
				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
			<?php endif; ?>
		</div>
		<div class="m_present_consult txtaC">
			<?php the_field('text_bottom_banner_left', 'option'); ?>
		</div>
	</div>
	<div class="m_col2">
		<div class="m_benefits">
			<?php the_field('text_bottom_banner_right', 'option'); ?>
		</div>
		<div class="m_obtain_cart p_r m_t_20">
			<a href="<?php the_field('button_link', 'option'); ?>">
				<img src="http://fbgabudhabi.com/wp-content/uploads/2017/01/blue-sticker.png" alt="sticker">
				<p><?php the_field('button_title', 'option'); ?></p>
			</a>
		</div>
		<div class="m_cart_img">
			<?php
			$image = get_field('image_bottom_banner_right', 'option');
			if( !empty($image) ): ?>
				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="fusion-clearfix"></div>
</div>
</div>
</div>
</div>

<!-- <link rel="stylesheet" href="<?php echo content_url(); ?>/themes/Avada/assets/css/owl.carousel.css"> -->
<!-- <link rel="stylesheet" href="<?php echo content_url(); ?>/themes/Avada/assets/css/owl.theme.css"> -->

<style>
/*@-moz-document url-prefix() {
    .fusion-footer-copyright-area > .fusion-row, .fusion-footer-widget-area > .fusion-row {
	padding-left: 298px!important;
   }
}*/
@media and (-webkit-min-device-pixel-ratio:0) {
	   .fusion-footer-copyright-area > .fusion-row, .fusion-footer-widget-area > .fusion-row {
		padding-left: 298px!important;
	   }
}

</style>

<!-- <script type='text/javascript' src='<?php echo content_url(); ?>/themes/Avada/assets/js/owl.carousel.min.js'></script>  -->

<script>
	jQuery(document).ready(function() {
		jQuery('.owl_carousel_slider').owlCarousel({
			items: 5,
			itemsDesktop: [1299, 4],
			itemsDesktopSmall: [991, 3],
			itemsTablet: [768, 2],
			itemsMobile: [480, 1],
			navigation: true,
			pagination: false,
			autoPlay: false,
			navigationText: ['<i class="icon_left"></i>','<i class="icon_right"></i>']
		});
		jQuery(".show_members_button").click(function(){
			jQuery(this).closest(".company_wrap").next(".dd_info").fadeToggle();
		});
	});
	function swal1(title, text, confirmButtonText){
		swal({
			title: title,
			html: text,
			confirmButtonText: confirmButtonText
		})
	};
</script>


<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();