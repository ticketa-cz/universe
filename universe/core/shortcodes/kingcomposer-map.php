<?php

/*
*	Register extend component for Visual Composer
*	king-theme.com
*/

if(!function_exists('is_plugin_active')){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


if ( is_plugin_active( 'kingcomposer/kingcomposer.php' )  && class_exists('KingComposer')) {

	add_action( 'init', 'universe_extend_kingcomposer' );
	function universe_extend_kingcomposer() {

		$kc = KingComposer::globe();
		$live_tmpl = KC_PATH.KDS.'shortcodes'.KDS.'live_editor'.KDS;

		$kc_elements = array(
			'mega_menu' => array(
				'name' => esc_html__( 'Custom Menu', 'universe' ),
				'title' => 'Custom Menu Settings',
				'icon' => 'sl sl-menu',
				'category' => 'Universe Theme',
				'wrapper_class' => 'clearfix',
				'description' => esc_html__( 'Display menu Wordpress in your content.', 'universe' ),
				'tab_icons' => array(
					'general' => 'et-tools',
				),
				'params' => array(
					array(
						'type'  => 'text',
						'label' => esc_html__( 'Menu Title', 'universe' ),
						'name'  => 'title',
						'value' => '',
						'description' => esc_html__( 'Enter title heading of the menu', 'universe' )
					),
					array(
						'type'  => 'text',
						'label' => esc_html__( 'Menu Slug', 'universe' ),
						'name'  => 'menu',
						'value' => '',
						'description' => esc_html__( 'Enter slug of the menu', 'universe' )
					),
					array(
						'type'  => 'text',
						'label' => esc_html__( 'Class Css', 'universe' ),
						'name'  => 'custom_class',
						'value' => ''
					)
				),
			),
			'kc_works' => array(
				'name' => esc_html__( 'Our Works', 'universe' ),
				'title' => 'Our Works Settings',
				'icon' => 'fa fa-send-o',
				'category' => 'Universe Theme',
				'wrapper_class' => 'clearfix',
				'description' => esc_html__( 'Our work for portfolio template.', 'universe' ),
				'tab_icons' => array(
					'general' => 'et-tools',
					'styling' => 'et-adjustments'
				),
				'params' => array(
					'general' => array(
						array(
							'type'        => 'multiple',
							'label'       => esc_html__( 'Select Categories ( hold ctrl or shift to select multiple )', 'universe' ),
							'name'        => 'tax_term',
							'options'     => kc_tools::get_terms( 'kc-works-category', 'slug' ),
							'height'      => '120px',
							'description' => esc_html__( 'Select category which you chosen for Works items', 'universe' )
						),
						array(
							'type'        => 'radio_image',
							'label'       => esc_html__( 'Select Template', 'universe' ),
							'name'        => 'layout',
							'admin_label' => true,
							'options'     => array(
								'1'	=>	UNIVERSE_THEME_URI . '/assets/images/works/1.png',
								'2'	=>	UNIVERSE_THEME_URI . '/assets/images/works/2.png',
								'3'	=>	UNIVERSE_THEME_URI . '/assets/images/works/3.png',
								'4'	=>	UNIVERSE_THEME_URI . '/assets/images/works/4.png'
							),
							'description' => esc_html__( 'This preview for 4 cols if you select other col, it may be dificult.', 'universe' )
						),
						array(
							'type'        => 'toggle',
							'label'       => esc_html__( 'Show Filters', 'universe' ),
							'name'        => 'filter',
							'description' => esc_html__( 'Does not apply to layout 3.', 'universe' ),
							'value'       => 'yes'
						),
						array(
							'type'        => 'select',
							'label'       => esc_html__( 'Filters Display Align', 'universe' ),
							'name'        => 'filter_align',
							'description' => esc_html__( 'Display button filters list align (Center|Left|Right).', 'universe' ),
							'value'       => 'Center',
							'options'     => array(
								'Center' => 'Align Center',
								'Left'   => 'Align Left',
								'Right'  => 'Align Right'
							),
							'relation'    => array(
								'parent' => 'filter',
								'show_when' => 'yes'
							)
						),
						array(
							'type'        => 'select',
							'label'       => esc_html__( 'Filters Category Display', 'universe' ),
							'name'        => 'filter_text',
							'description' => esc_html__( 'Display button|text filters list category.', 'universe' ),
							'options'     => array(
								'button' => 'Display Button',
								''       => 'Display Text',
							),
							'value' => 'button',
							'relation'    => array(
								'parent'	=> 'filter',
								'show_when'	=> 'yes'
							)
						),
						array(
							'type'        => 'toggle',
							'label'       => esc_html__( 'Filters Count Items', 'universe' ),
							'name'        => 'filter_count',
							'description' => esc_html__( 'Display number items filters list category.', 'universe' ),
							'relation'    => array(
								'parent'	=> 'filter',
								'show_when'	=> 'yes'
							)
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Filters Splitter', 'universe' ),
							'name'        => 'filter_splitter',
							'description' => esc_html__( 'Text display middle filters list category.', 'universe' ),
							'value'       => '',
							'relation'    => array(
								'parent'	=> 'filter',
								'show_when'	=> 'yes'
							)
						),
						array(
							'type'        => 'select',
							'label'       => esc_html__( 'Filters Animation', 'universe' ),
							'name'        => 'animation',
							'description' => esc_html__( 'Defines what animation to use for grid items that will be shown or hidden after a filter is activated. Click on filter items after each change..', 'universe' ),
							'value'       => 'fadeOutTop',
							'options'     => array(
								"3dflip"       => esc_html__( '3d Flip', 'universe' ),
								"bounceBottom" => esc_html__( 'Bounce Bottom', 'universe' ),
								"bounceLeft"   => esc_html__( 'Bounce Left', 'universe' ),
								"bounceTop"    => esc_html__( 'Bounce Top', 'universe' ),
								"fadeOut"      => esc_html__( 'Fade Out', 'universe' ),
								"fadeOutTop"   => esc_html__( 'Fade Out Top', 'universe' ),
								"flipBottom"   => esc_html__( 'Flip Bottom', 'universe' ),
								"flipOut"      => esc_html__( 'Flip Out', 'universe' ),
								"flipOutDelay" => esc_html__( 'Flip Out Delay', 'universe' ),
								"foldLeft"     => esc_html__( 'Fold Left', 'universe' ),
								"frontRow"     => esc_html__( 'Front Row', 'universe' ),
								"moveLeft"     => esc_html__( 'Move Left', 'universe' ),
								"quicksand"    => esc_html__( 'Quicksand', 'universe' ),
								"rotateSides"  => esc_html__( 'Rotate Sides', 'universe' ),
								"rotateRoom"   => esc_html__( 'Rotate Room', 'universe' ),
								"scaleDown"    => esc_html__( 'Scale Down', 'universe' ),
								"scaleSides"   => esc_html__( 'Scale Sides', 'universe' ),
								"slideLeft"    => esc_html__( 'Slide Left', 'universe' ),
								"sequentially" => esc_html__( 'Sequentially', 'universe' ),
								"slideDelay"   => esc_html__( 'Slide Delay', 'universe' ),
								"skew"         => esc_html__( 'Skew', 'universe' ),
								"unfold"       => esc_html__( 'Unfold', 'universe' )
							),
							'relation'    => array(
								'parent' => 'filter',
								'show_when' => 'yes'
							)
						),
						array(
							'type'        => 'select',
							'label'       => esc_html__( 'Items on Row', 'universe' ),
							'name'        => 'column',
							'description' => esc_html__( 'Choose number of items display on a row', 'universe' ),
							'value'       => '4',
							'options'     => array(
								'2' => '2 Items',
								'3' => '3 Items',
								'4' => '4 Items'
							)
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Items Limit', 'universe' ),
							'name'        => 'items',
							'value'       => 8,
							'description' => esc_html__( 'Specify number of works that you want to show. Enter -1 to get all works', 'universe' )
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Gap', 'universe' ),
							'name'        => 'gap',
							'value'       => '0',
							'description' => esc_html__( 'Gap space Horizontal and Vertical, For example: 10 or 10|20', 'universe' )
						),
						array(
							'type'        => 'select',
							'label'       => esc_html__( 'Layout Hover', 'universe' ),
							'name'        => 'hover_style',
							'description' => esc_html__( 'Display title and author OR display button more info and view larger', 'universe' ),
							'options' => array(
								'1' => esc_html__( 'Title and author', 'universe' ),
								'2' => esc_html__( 'Display button', 'universe' )
							)
						),
						array(
							'type'        => 'select',
							'label'       => esc_html__( 'Caption Style', 'universe' ),
							'name'        => 'caption_style',
							'description' => esc_html__( 'Change the overlay that shows when you mouse over a grid item.', 'universe' ),
							'value'       => 'fadeIn',
							'options' => array(
								""                    => esc_html__( 'Default Style', 'universe' ),
								"pushTop"             => esc_html__( 'Push Top', 'universe' ),
								"pushDown"            => esc_html__( 'Push Down', 'universe' ),
								"revealBottom"        => esc_html__( 'Reveal Bottom', 'universe' ),
								"revealTop"           => esc_html__( 'Reveal Top', 'universe' ),
								"revealLeft"          => esc_html__( 'Reveal Left', 'universe' ),
								"moveRight"           => esc_html__( 'Move Right', 'universe' ),
								"overlayBottom"       => esc_html__( 'Overlay Bottom', 'universe' ),
								"overlayBottomPush"   => esc_html__( 'Overlay Push', 'universe' ),
								"overlayBottomReveal" => esc_html__( 'Overlay Reveal', 'universe' ),
								"overlayBottomAlong"  => esc_html__( 'Overlay Along', 'universe' ),
								"overlayRightAlong"   => esc_html__( 'Overlay Right', 'universe' ),
								"minimal"             => esc_html__( 'Minimal', 'universe' ),
								"fadeIn"              => esc_html__( 'Fade In', 'universe' ),
								"zoom"                => esc_html__( 'Zoom', 'universe' ),
								"opacity"             => esc_html__( 'Opacity', 'universe' )
							)
						),
						array(
							'type'    => 'select',
							'label'   => esc_html__( 'Order By', 'universe' ),
							'name'    => 'order',
							'options' => array(
								'DESC' => esc_html__( 'Descending', 'universe' ),
								'ASC' => esc_html__( 'Ascending', 'universe' )
							)
						),
						array(
							'type'        => 'toggle',
							'label'       => esc_html__( 'Show link', 'universe' ),
							'name'        => 'show_link',
							'description' => esc_html__( 'Show or hide our works link', 'universe' ),
							'value'       => 'yes'
						),
						array(
							'type'  => 'text',
							'label' => esc_html__( 'Class Css', 'universe' ),
							'name'  => 'custom_class',
							'value' => ''
						)
					),
					'styling' => array(
						array(
							'name'    => 'css_custom',
							'type'    => 'css',
							'options' => array(
								array(
									'Caption' => array(
										array('property' => 'color', 'label' => 'Text Color', 'selector' => '.cbp-l-caption-title'),
										array('property' => 'background-color', 'label' => 'Background Caption Wrap', 'selector' => '.cbp-caption-active .cbp-caption-activeWrap'),
										array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.cbp-l-caption-title'),
										array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.cbp-l-caption-title'),
										array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.cbp-l-caption-title'),
										array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.cbp-l-caption-title'),
										array('property' => 'margin', 'label' => 'Margin', 'selector' => '.cbp-l-caption-title'),
										array('property' => 'padding', 'label' => 'Padding', 'selector' => '.cbp-l-caption-title'),
									),
									'Description' => array(
										array('property' => 'color', 'label' => 'Text Color', 'selector' => '.cbp-l-caption-desc'),
										array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.cbp-l-caption-desc'),
										array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.cbp-l-caption-desc'),
										array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.cbp-l-caption-desc'),
										array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.cbp-l-caption-desc'),
										array('property' => 'margin', 'label' => 'Margin', 'selector' => '.cbp-l-caption-desc'),
										array('property' => 'padding', 'label' => 'Padding', 'selector' => '.cbp-l-caption-desc'),
									),
									'Button' => array(
										array('property' => 'color', 'label' => 'Text Color', 'selector' => '.cbp-l-caption-body a'),
										array('property' => 'color', 'label' => 'Text Color Hover', 'selector' => '.cbp-l-caption-body a:hover'),
										array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.cbp-l-caption-body a'),
										array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.cbp-l-caption-body a:hover'),
										array('property' => 'border', 'label' => 'Border', 'selector' => '.cbp-l-caption-body a'),
										array('property' => 'border-color', 'label' => 'Border Color Hover', 'selector' => '.cbp-l-caption-body a:hover'),
										array('property' => 'padding', 'label' => 'Padding', 'selector' => '.cbp-l-caption-body a'),
										array('property' => 'margin', 'label' => 'Margin', 'selector' => '.cbp-l-caption-body a'),
									),
									'Filter' => array(
										array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.cbp-filter-item'),
										array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.cbp-filter-item'),
										array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.cbp-filter-item'),
										array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.cbp-filter-item'),
										array('property' => 'color', 'label' => 'Color', 'selector' => '.cbp-filter-item'),
										array('property' => 'color', 'label' => 'Color Hover', 'selector' => '.cbp-filter-item:hover'),
										array('property' => 'color', 'label' => 'Color Active', 'selector' => '.cbp-filter-item.cbp-filter-item-active'),
										array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.cbp-filter-item'),
										array('property' => 'background-color', 'label' => 'Background Color Hover', 'selector' => '.cbp-filter-item:hover'),
										array('property' => 'background-color', 'label' => 'Background Color Active', 'selector' => '.cbp-filter-item.cbp-filter-item-active'),
										array('property' => 'border', 'label' => 'Border', 'selector' => '.cbp-filter-item'),
										array('property' => 'border-color', 'label' => 'Border Color Hover', 'selector' => '.cbp-filter-item:hover'),
										array('property' => 'padding', 'label' => 'Padding', 'selector' => '.cbp-filter-item'),
										array('property' => 'margin', 'label' => 'Margin', 'selector' => '.cbp-filter-item'),
									),

								)
							)
						)
					),
				)
			),

			'kc_subscribe' => array(
				'name'          => esc_html__( 'Subscribe form', 'universe' ),
				'title'         => 'Newsletter Subscribe form',
				'icon'          => 'fa fa-envelope-o',
				'category'      => 'Universe Theme',
				'wrapper_class' => 'clearfix',
				'description'   => esc_html__( 'Display Subscribe form', 'universe' ),
				'tab_icons' => array(
					'general' => 'et-tools',
					'styling' => 'et-adjustments'
				),
				'params'        => array(
					'general' => array(
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Input text', 'universe' ),
							'name'        => 'input_text',
							'description' => esc_html__( 'Input text, text placeholder', 'universe'),
							'value' => esc_html__( 'Your email address', 'universe' )
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Text Button Sumit', 'universe' ),
							'name'        => 'input_submit',
							'description' => esc_html__( 'Submit text. It blank, we will use Subscribe', 'universe'),
							'value' => esc_html__( 'Signup', 'universe' )
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Custom Button class', 'universe' ),
							'name'        => 'input_submit_class',
							'description' => esc_html__( 'Add custom class for submit button', 'universe')
						),
						array(
							'type'    => 'select',
							'label'   => esc_html__( 'Method', 'universe' ),
							'name'    => 'method',
							'value'   => 'mc',
							'options' => array(
								'mc'   => esc_html__( 'Mailchimp', 'universe' ),
								'self' => esc_html__( 'Theme Functions', 'universe' )
							)
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Mailchimp API Key', 'universe' ),
							'name'        => 'mc_api',
							'description' => esc_html__( 'Your API key which you can grab from http://admin.mailchimp.com/account/api/', 'universe')
						),
						array(
							'type'        => 'text',
							'label'       => esc_html__( 'Mailchimp List ID', 'universe' ),
							'name'        => 'mc_list_id',
							'description' => esc_html__( 'The ID of list which you want to customers signup. You can grab your List Id by going to http://admin.mailchimp.com/lists/ click the "settings" link for the list - the Unique Id is at the bottom of that page.', 'universe')
						),
						array(
							'type'  => 'text',
							'label' => esc_html__( 'Extra Class', 'universe' ),
							'name'  => 'class',
							'value' => '',
						)
					),
					'styling' => array(
						array(
							'name'    => 'css_custom',
							'type'    => 'css',
							'options' => array(
								array(
									'Input' => array(

										array('property' => 'color', 'label' => 'Text Color', 'selector' => '.enter_email_input'),
										array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.enter_email_input'),
										array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.enter_email_input'),
										array('property' => 'height', 'label' => 'Height', 'selector' => '.enter_email_input', 'value' => '45px'),
										array('property' => 'border', 'label' => 'Border', 'selector' => '.enter_email_input', 'value' => '1px solid #ccc'),
										array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.enter_email_input'),
									),

									'Button' => array(
										array('property' => 'color', 'label' => 'Text Color', 'selector' => '.input_submit', 'color' => '#FFFFFF'),
										array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.input_submit', 'value' => '#000000'),
										array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.input_submit'),
										array('property' => 'height', 'label' => 'Height', 'selector' => '.input_submit', 'value' => '45px'),
										array('property' => 'border', 'label' => 'Border', 'selector' => '.input_submit'),
										array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.input_submit'),
										array('property' => 'padding', 'label' => 'Spacing', 'selector' => '.input_submit'),
									),
									'Button Hover' => array(
										array('property' => 'color', 'label' => 'Text Color', 'selector' => '.input_submit:hover'),
										array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.input_submit:hover'),
									),
								)
							)
						)
					),
				)
			)

		);


		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {

			$contact_forms = kc_tools::get_cf7_names();

			$kc_elements['kc_cf7'] = array(
				'name' => esc_html__( 'Contact Form 7', 'universe' ),
				'title' => 'Contact Form 7',
				'icon' => 'fa fa-arrows-h',
				'category' => 'Universe Theme',
				'wrapper_class' => 'clearfix',
				'description' => esc_html__( 'Display contact form 7', 'universe' ),
				'tab_icons' => array(
					'general' => 'et-tools',
					'styling' => 'et-adjustments'
				),
				'params' => array(
					'general' => array(
						array(
							'type'  => 'text',
							'label' => esc_html__( 'Title', 'universe' ),
							'name'  => 'title',
						),
						array(
							'name'        => 'slug',
							'type'        => 'select',
							'label'       => esc_html__( 'Select Contact Form', 'universe' ),
							'admin_label' => true,
							'options'     => $contact_forms,
							'description' => esc_html__( 'Choose previously created contact form from the drop down list.', 'universe' )
						),
						array(
							'type'  => 'text',
							'label' => esc_html__( 'Extra Class', 'universe' ),
							'name'  => 'class',
							'value' => '',
						)
					),
					'styling' => array(
						array(
							'name'    => 'css_custom',
							'type'    => 'css',
						)
					),
				)
			);

		}


		if ( is_plugin_active( 'masterslider/masterslider.php' ) ) {

			$masterslider_list = get_masterslider_names('alias-title');

			$kc_elements['kc_masterslider'] = array(
				'name'			=> esc_html__( 'Master Slider', 'universe' ),
				'title'			=> 'Master Slider',
				'icon'			=> 'fa fa-sliders',
				'category'		=> 'Universe Theme',
				'wrapper_class'	=> 'clearfix',
				'description'	=> esc_html__( 'Display master slider', 'universe' ),
				'tab_icons'		=> array(
					'general' => 'et-tools',
					'styling' => 'et-adjustments'
				),
				'params'		=> array(
					'general' => array(
						array(
							'name'        => 'slug',
							'type'        => 'select',
							'label'       => esc_html__( 'Select Master Slider', 'universe' ),
							'admin_label' => true,
							'options'     => $masterslider_list,
							'description' => esc_html__( 'Choose previously created master slider from the drop down list.', 'universe' )
						),
						array(
							'type'  => 'text',
							'label' => esc_html__( 'Extra Class', 'universe' ),
							'name'  => 'class',
							'value' => '',
						)
					),
					'styling' => array(
						array(
							'name'    => 'css_custom',
							'type'    => 'css',
						)
					),
				)
			);

		}


		$kc->add_map( $kc_elements, 'core' );


	}

}