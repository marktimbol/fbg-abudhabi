<?php

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
