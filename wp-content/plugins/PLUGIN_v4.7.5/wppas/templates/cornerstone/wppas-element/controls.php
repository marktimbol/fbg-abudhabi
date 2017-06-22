<?php
global $pro_ads_adzones;
		
// Select all available adzones
$adzones = $pro_ads_adzones->get_adzones();
$adzone_arr = array();
foreach( $adzones as $adzone )
{
	$adzone_arr[] = array('value' => $adzone->ID, 'label' => $adzone->post_title);
}


/**
 * Element Controls
 */
return array(

	/*'heading' => array(
		'type'    => 'text',
		'ui' => array(
			'title'   => __( 'Heading &amp; Content', 'my-extension' ),
			'tooltip' => __( 'Tooltip to describe your controls.', 'my-extension' ),
		),
		'context' => 'content',
    'suggest' => __( 'Heading', 'my-extension' ),
	),

	'content' => array(
		'type'    => 'textarea',
		'context' => 'content',
		'suggest' => __( 'Click to inspect, then edit as needed.', 'my-extension' ),
	),*/
	
	'adzone' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __( 'Adzone', 'wpproads' ),
			'tooltip' => __( 'Select the adzone to show.', 'wpproads' ),
		),
		//'context' => 'content',
		'options' => array(
			'choices' => $adzone_arr
			//'choices' => array(
				
				/*array( 'value' => 'none',   'label' => __( 'None', 'cornerstone' ) ),
				array( 'value' => 'solid',  'label' => __( 'Solid', 'cornerstone' ) ),
				array( 'value' => 'dotted', 'label' => __( 'Dotted', 'cornerstone' ) ),
				array( 'value' => 'dashed', 'label' => __( 'Dashed', 'cornerstone' ) ),
				array( 'value' => 'double', 'label' => __( 'Double', 'cornerstone' ) ),
				array( 'value' => 'groove', 'label' => __( 'Groove', 'cornerstone' ) ),
				array( 'value' => 'ridge',  'label' => __( 'Ridge', 'cornerstone' ) ),
				array( 'value' => 'inset',  'label' => __( 'Inset', 'cornerstone' ) ),
				array( 'value' => 'outset', 'label' => __( 'Outset', 'cornerstone' ) )*/
			//)  
		)
	),

	/*'orientation' => array(
		'type' => 'choose',
		'ui' => array(
			'title' => __( 'Orientation', 'my-extension' ),
      'tooltip' => __( 'Choose to display the heading vertically or horizonatally', 'my-extension' ),
      'divider' => true
		),
		'options' => array(
			'divider' => true,
      'columns' => '2',
      'choices' => array(
      	  array( 'value' => 'vertical',   'tooltip' => __( 'Vertical', 'my-extension' ),   'icon' => fa_entity( 'arrows-v' ) ),
      	  array( 'value' => 'horizontal', 'tooltip' => __( 'Horizontal', 'my-extension' ), 'icon' => fa_entity( 'arrows-h' ) ),
      )
    )
  ),

	'heading_color' => array(
	 	'type' => 'color',
	 	'ui' => array(
			'title'   => __( 'Heading Color', 'my-extension' )
		)
	),

	'background_color' => array(
	 	'type' => 'color',
	 	'ui' => array(
			'title'   => __( 'Background Color', 'my-extension' )
		)
	),

	'border' => array(
	 	'mixin' => 'border',
	),*/

);