<?php

function get_sector_id($sector)
{
	global $wpdb;

	$post_sector = $wpdb->get_results("
		SELECT
			*
		FROM
			wp_xpksy4skky_posts
		WHERE
			post_title LIKE '%{$sector}%'
			AND post_type LIKE 'sector'
			AND post_status LIKE 'publish'
		LIMIT 1
		");

	foreach ($post_sector as $row_sector) {
		return $row_sector->ID;
		break;
	}
}


function suggestion_profile($type="list")
{
	global $wpdb;

	$user_id = $user_item = $date_reg = $date = $user_shortcode_block = "";

	$result = $wpdb->get_results("
		SELECT 
			wp_users.ID as ID,
			wp_users.user_registered as user_registered
		FROM
			wp_xpksy4skky_users wp_users
		ORDER BY RAND()"
	);

	$count_wm=0;
	
	foreach ($result as $row)
	{
        $adminuser = false;
        $resultadmin = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='wp_xpksy4skky_capabilities' AND meta_value !='a:1:{s:13:\"administrator\";b:1;}' ");	

		foreach ($resultadmin as $rowadmin) {	
			$adminuser = true;
		}

		if( ! $adminuser ) {
			continue;
		} 
		
        $approved = false;
		$result9 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='pw_user_status' AND meta_value='approved' ");

		foreach ($result9 as $row9) {
			$approved = true;
		}

		$user_f_un="";
		if( ! $approved ) {
			continue;
		}

		$paid_unpaid_wm = false;
		$result22 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='paid_unpaid' AND meta_value='paid' ");

		foreach ($result22 as $row22) {
			$paid_unpaid_wm=true;
		}

		$user_id_wm = $row->ID;
		if ( ! $paid_unpaid_wm ) {
			continue;
		}

		$resultOverview = $wpdb->get_results(" SELECT `user_id` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='members_overview' AND meta_value='a:1:{i:0;s:7:\"Display\";}' ");

        if( empty($resultOverview) ) {
            continue;
        }

		$current_user_id = get_current_user_id();
		if ($current_user_id <> "")
		{
			$result70 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$current_user_id}' AND meta_key='paid_unpaid' AND meta_value='paid'");

			foreach ($result70 as $row70) {
				$user_id = $user_id_wm;
			}
		} else {
			$result377 = $wpdb->get_results(" SELECT `user_id` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='members_overview' AND meta_value='a:1:{i:0;s:7:\"Display\";}' ");

			if (empty($result377) ) {
				continue;
			}

			$user_id = $result377[0]->user_id;
		}

		$date_reg = $row->user_registered;
		$date = (current_time('timestamp')-strtotime($date_reg))/86400;
		$first_name="";
		$result1 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='first_name'");

		foreach ( $result1 as $row1 ) {
			$first_name = $row1->meta_value;
		}

		$last_name="";
		$result3 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='last_name'");	
		
		foreach ($result3 as $row3) {
			$last_name = $row3->meta_value;
		}

		$badge_name="";
		$result4 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='badge_name'");	
		
		foreach ($result4 as $row4) {
			$badge_name = $row4->meta_value;
		}

		$result8 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='badge_color'");	
		foreach ($result8 as $row8) {
			$badge_color = $row8->meta_value;
		}

		$result9 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='badge_background'");
		foreach ($result9 as $row9) {
			$badge_background = $row9->meta_value;
		}

		$user_picture="";
		$result5 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='user_picture'");	
		
		foreach ($result5 as $row5) {
			$user_picture = $row5->meta_value;
		}

		$user_image = content_url()."/uploads/2017/01/new-FBG-logo_transparent.png";
		$result2 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_postmeta WHERE post_id='{$user_picture}' AND meta_key='_wp_attached_file'");

		foreach ($result2 as $row2) {
			$user_image = content_url()."/uploads/".$row2->meta_value;
		}


		$position = "";
		$resPosition = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='position'");	
		
		foreach ( $resPosition as $rowPos ) {
			$position = $rowPos->meta_value;
		}

        $count_wm++;
		if ($count_wm == 10) {
			break;
		}

		$full_name = $first_name." ".$last_name;

       if( ICL_LANGUAGE_CODE == 'fr' ) {
           $strNew = "Nouveau";
       } else {
            $strNew = "New";
       }

		$corner_badge="";	
		if ( $date < 31 )
		{
			$corner_badge=<<<EOF
			<div class="m_features_name">
				<p class="m_right_date m_right_corner_feature bg_white_f" href="#"><span style="color:white!important; font-weight: bold!important;">{$strNew}</span></p>
			</div>
			<style>
				.bg_white_f:after {
					border-right: 118px solid #0290dc;
				}				
			</style>
EOF;
		}

		if ( $badge_name <> '' )
		{
            if ( ICL_LANGUAGE_CODE == 'fr' ) {
                if ( $badge_name == 'Featured' ) {
                    $badge_name = "Mis en Avant";
                }
            }

			$corner_badge=<<<EOF
			<div class="m_features_name">
				<p class="m_right_date m_right_corner_feature bg_white_f" href="#" style=""><span style="color:{$badge_color}!important; font-weight: bold!important;">{$badge_name}</span></p>
			</div>
			<style>
			.bg_white_f:after {
				border-right: 118px solid {$badge_background};
			}				
			</style>
EOF;
		} 

        if ( ICL_LANGUAGE_CODE == 'fr' ) {
             $link_used = get_site_url() . "/annuaire-en-ligne/users-details/?uid=" . $user_id;
        } else {
		    $link_used = get_site_url() . "/en/online-directory/users-details/?uid=" . $user_id;
        }

		$final_link = $company_name_c = $company_name_wm = "";
		$company_id_c = 0;

		$result15 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='company'");	
		foreach ($result15 as $row15) {
			$company_id_c = $row15->meta_value;
		}

		$result16 = $wpdb->get_results("SELECT `post_title` FROM wp_xpksy4skky_posts WHERE ID='{$company_id_c}'");	
		foreach ($result16 as $row16) {
			$company_name_c = $row16->post_title;
		}

		$link_company_wm = get_permalink($company_id_c);

		if ($company_name_c == "Individual") {
			$company_name_wm = $company_name_c="";
		} else if (strlen($link_company_wm) > 0 && $company_id_c > 0) {
			$company_name_wm = $company_name_c;
			$final_link = "<a href='{$link_company_wm}'><p style='font-size: 14px; height: 32px; color: #0291dd;'>{$company_name_wm}</p></a>";
		}

		$user_item = <<<EOF
		<div class="item">
			<div class="set_bg_for_item">
				<img src="{$user_image}" alt="client">
			</div>
			{$corner_badge}
			<div class="m_desc_of_position">
				<div class="m_name_of_person">
					<a href="{$link_used}"><p>{$full_name}</p></a>
				</div>
				<div class="occupation_position_ahead m_b_20">
					<p style="height: 32px;">{$position}</p>
				</div>
				<div class="occupation_position_ahead m_b_20">
					{$final_link}
				</div>
			</div>
		</div>
EOF;
	
		if ( $type == "shortcode" ) {
			$user_shortcode_block .= $user_item;
		} else {
			echo $user_item;
		}
	}

	if ( $type == "shortcode" ) {
		return $user_shortcode_block;
	}
}

function user_datail_search_fs($atts)
{
	if (ICL_LANGUAGE_CODE == 'fr') {
		$strSrchText = "Rechercher un membre individuel";
	} else {
    	$strSrchText = "Search for Member";
	}

	echo '
	<div class="banner_top_on_list_categories">
		<div class="set_dimensions_to_banner_top">
			<img src="'.content_url().'/uploads/2017/01/banner_top_img.jpg" alt="">
			<div class="include_search_on_list_categories_top">
				<form action="' .get_permalink(14918).'">
					<input type="text" name="q" placeholder=" ' . $strSrchText . ' " class="style_input_search_on_page_list" value="' . $_GET['q'] . '">
					<div class="include_button_submit_on_page_list">
						<button type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>';
}

function user_detail_title_fs($atts)
{
	echo '
	<div class="title_categories">
		<div class="m_events_in_future style_events_on_page">
			<h2>Member</h2>
		</div>
	</div>';
}

function user_detail_image_fs($atts)
{
	if (isset($_GET['uid']) && !empty($_GET['uid'])) {
		$company_id = $_GET['uid'];
	} else {
		wp_redirect(home_url());
	}
	echo '
		<div class="content_on_company_on_page">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="left_details_company_on_page">
						<div class="row">
							<div class="col-md-1 col-sm-12 col-xs-12"></div>
								<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
									<div class="set_img_company">
										<a class="company_img fullwh_img" href="javascript:void(0)">';
											$image = get_field('user_picture','user_'.$company_id);
											if(strlen($image) == 0) {
												$image = content_url() . "/uploads/2017/01/new-FBG-logo_transparent.png";
											}
											if( !empty($image) ) {
												echo '<img src="'.$image.'" alt="" />';
											}
								echo '
										</a>
									</div>
								</div>';
}

function user_detail_name_fs($atts)
{
	$company_id = $_GET['uid'];
	echo '
	<div class="detail_page">
		<div class="top_company_on_page">
			<div class="style_company_and_individual_name_title txtaC">
				<h3>'. get_field('first_name','user_'.$company_id).' '.get_field('last_name','user_'.$company_id).'</h3>
			</div>';
}

function user_detail_details_fs($atts)
{
	$company_id = $_GET['uid'];
	$fax_nr=get_field('fax', 'user_'.$company_id);
	$city_nr=get_field('city', 'user_'.$company_id);
	$phone_nr=get_field('mobile', 'user_'.$company_id);
	$position=get_field('position', 'user_'.$company_id);

	$user_info = get_userdata($company_id);
	$mailadresje = $user_info->user_email;

	$company = get_field('company', 'user_'.$company_id);
	$company_name = $company->post_title;

	$user_type = "Corporate";

	if ($company->ID == "14843" || $company->ID=="") {
		$company_name = $company_img = "";
		$user_type = "Individual";
		$company->ID="14843";
	}

	if (is_user_logged_in() )
	{
		$phone_icon = '<i class="fa fa-phone" aria-hidden="true"></i>';
		$mail_icon = '<i class="fa fa-mail" aria-hidden="true"></i>';
		$phone_display = $mail_display = "";

		if(strlen($phone_nr) == 0) { $phone_nr = ""; $phone_icon = ''; $phone_display = ' style="display:none" '; }
		if(strlen($mailadresje) == 0) { $mailadresje = ""; $mail_icon = ''; $mail_display = ' style="display:none" '; }

		$company_details = <<<EOF
		<a onclick="swal1('Phone', '{$phone_nr}', 'OK');" class="button_sm" {$phone_display}>
			{$phone_icon}
		</a>
		<a onclick="swal1('Email', '{$mailadresje}', 'OK');" class="button_sm" {$mail_display}>
			{$mail_icon}
		</a>
EOF;
	} else {

		$link_register = "<a href='" . get_permalink(13739) . "'>Login</a> or <a href='" . get_permalink(13760) . "'>Become a member!</a> ";

		$company_details = <<<EOF
		<a  onclick="swal1('Phone', `{$link_register}`, 'OK');" class="button_sm">
			<i class="fa fa-phone" aria-hidden="true"></i>
		</a>
		<a  onclick="swal1('Fax', `{$link_register}`, 'OK');" class="button_sm">
			<i class="fa fa-mail" aria-hidden="true"></i>
		</a>
EOF;
	}

	echo '
	<div class="col-md-5 col-sm-12 col-xs-12 pl_0 pr_0">
		<div class="activity_sectors">
			<div class="member_period">
				<p>'.$position.'</p>
				<p>'.$company_name.'</p>
				<p>'.$user_type.'</p>
			</div>
			<div class="include_icons">
				<div class="bottom_buttons m_set_mt_20">
					'.$company_details.'
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>';

	echo '
	<script>
		function swal1(title, text, confirmButtonText){
			swal({
				title: title,
				html: text,
				confirmButtonText: confirmButtonText
			})
		};
	</script>
	';
}

function user_detail_company_fs($atts)
{
	$company_id = $_GET['uid'];

	if($company->ID == "14843" || $company->ID=="")
	{
		$company_name = $company_img = "";
		$user_type = "Individual";
		$company->ID="14843";
	}

	$company = get_field('company', 'user_'.$company_id);
	$company_name = $company->post_title;

	$image = get_field('company_picture', $company->ID);
	if(strlen($image) == 0) {
		$image = content_url() . "/uploads/2017/01/new-FBG-logo_transparent.png";
	}

	$company_img = "<a href='" . get_permalink($company->ID) . "'><img src='" . $image . "' alt='' /></a>";

	if($company->ID == "14843" || $company->ID == "") {
		$company_name = $company_img = "";
	}

	echo '
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="desc_company_individual_name mg_b_50">
						<div class="activity_sectors member_period">
							<p>'.$company_name.'</p>
							'.$company_img.'
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';
}


function companies_form_search_fs($atts)
{
	$placeholder = "Search for companies";
	if ( isset($atts['placeholder']) ) {
		$placeholder = $atts['placeholder'];
	}

	echo "
	<div class='banner_top_on_list_categories'>
		<div class='set_dimensions_to_banner_top'>
			<img src='" . content_url() . "/uploads/2017/01/banner_top_img.jpg' alt=''>
			<div class='include_search_on_list_categories_top'>
				<form action='" .  get_permalink(13750) . "'>
					<input type='text' name='q' placeholder='" . $placeholder . "' class='style_input_search_on_page_list' value='" .  $_GET['q'] . "'>
					<div class='include_button_submit_on_page_list'>
						<button type='submit'>
							<i class='fa fa-search'></i>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	";
}

function companies_page_companies_show_fs($atts)
{
	global $wpdb;
	$category_id = 0;

	if (isset($_GET['cat'])&&!empty($_GET['cat']) )
	{
		$sector_company = esc_attr( sanitize_text_field( $_GET['cat'] ) );
		$result5 = $wpdb->get_results("SELECT ID, post_title FROM wp_xpksy4skky_posts WHERE `post_name` LIKE '{$sector_company}' AND `post_type` = 'sector' ");	

		foreach ($result5 as $row5) {
			$category = $row5->post_title;
			$category_id = $row5->ID;
		}
	}

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$args = array(
		'posts_per_page'	=>	6,
		'post_type'			=>	'company',
		'orderby'			=>	'title',
		'order'				=>	'ASC',
		'paged'				=>	$paged
	);


	if (isset($_GET['start'])&&!empty($_GET['start']) ) {
		$args['sector_title'] = esc_attr( sanitize_text_field( $_GET['start'] ) );
	}

	if (isset($_GET['cat'])&&!empty($_GET['cat']) )
	{
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => 'company_sector_obj_%_sector_obj',
				'value'   => esc_attr( sanitize_text_field( $category_id ) ),
				'compare' => '='
			)
		);

		$args['sector_company'] = "sector_company";
	}

	if (isset($_GET['q']) && !empty($_GET['q']) ) {
		$args['s'] = $_GET['q'];
	}

	$the_query = new WP_Query( $args );
	$pageposts = array();

	if (isset($_GET['q'])&&!empty($_GET['q']) )
	{
		$q = esc_attr( sanitize_text_field($_GET['q'] ) );
		$query = "
		SELECT
			*
		FROM
			wp_xpksy4skky_posts p
		WHERE
			p.post_type = 'company'
			AND p.post_status = 'publish'
			AND p.id <> '14843'
			AND (
				p.id IN (
					SELECT
						meta_value um2
					FROM
						wp_usermeta um2
					WHERE
						um2.meta_key LIKE 'company'
						AND um2.user_id IN (
							SELECT
								um.user_id
							FROM
								wp_usermeta um
							WHERE
							(
								um.meta_key = 'first_name'
								OR um.meta_key = 'last_name'
							)
							AND meta_value LIKE '%{$q}%'
						)
				)
				OR p.post_title LIKE '%{$q}%'
			)";

		if (isset($_GET['start'])&&!empty($_GET['start']) ) {
			$query .= " AND p.post_title LIKE '" . esc_sql( $wpdb->esc_like( $_GET['start'] ) ) . "%'";
		}

		$pageposts = $wpdb->get_results($query, OBJECT);

	} else {
		$the_query = new WP_Query( $args );
	}

	echo '<div class="adelle_grid">';

	if (count($pageposts) > 0) {
		include 'templates/templates-companies-inc-q.php';
	} else {
		include 'templates/templates-companies-inc-obj.php';
	}

	echo '
	<div class="clearfix"></div>

	<div class="pagination_wrap">
		<ul class="pagination_list">';
			$big = 999999999;
			echo paginate_links(
				array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $the_query->max_num_pages
				)
			);
			echo '
		</ul>
	</div>
</div>';

echo '
<script>
	function swal1(title, text, confirmButtonText){
		swal({
			title: title,
			html: text,
			confirmButtonText: confirmButtonText
		})
	};
</script>
';
}

function companies_page_title_fs($atts)
{
	global $wpdb;
	$back = "";

	if (isset($_GET['start']) )
	{
		$back = '
		<a href="' . get_permalink(13750) . '">
			<i class="fa fa-arrow-left" aria-hidden="true"></i>
			<span>Back</span>
		</a>
		';
	}

	$category = "Companies";
	if ( isset($atts['title']) ) {
		$category = $atts['title'];
	}

	$users = "Individual members";
	$category_id = 0;

	if (isset($_GET['cat'])&&!empty($_GET['cat']) )
	{
		$sector_company = esc_attr( sanitize_text_field( $_GET['cat'] ) );
		$result5 = $wpdb->get_results("SELECT ID, post_title FROM wp_xpksy4skky_posts WHERE `post_name` LIKE '{$sector_company}'");	
		
		foreach ($result5 as $row5) {
			$category = $row5->post_title;
			$category_id = $row5->ID;
		}
	}

	echo '
	<div class="title_categories">
		<div class="m_events_in_future style_events_on_page">
			<h2>' . $category . '</h2>
		</div>
		<div class="back_button_right">
			' . $back . '
		</div>
	</div>
	';
}

function companies_page_letters_fs($atts)
{
    if (ICL_LANGUAGE_CODE == 'fr') {
        $siteUrl = get_permalink('13750');
    } else {
        $siteUrl = get_permalink('16127');
    }

    $arrAlpha = $alphas = range('a', 'z');
   ?>
   <div class="display_alphabetic_bar">
   <ul class="ul_alphabetic_bar">
   <?php
   foreach ($arrAlpha as $key => $value) {
       $searchUrl = $siteUrl . "?start=" . $value;
       $srChar = "";
       if (isset($_REQUEST['start']) ) {
           $srChar = $_REQUEST['start'];
       }
    ?>
    	<li>
            <a href="<?=$searchUrl?>" <?if($srChar == $value){?>class="active_link_on_alphabetic_sort"<?}?>><?=strtoupper($value)?></a>
       </li><?php       
   }
   ?>
   </ul></div><?php
}

