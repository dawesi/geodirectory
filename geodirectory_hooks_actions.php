<?php  

function geodir_get_ajax_url(){
	return admin_url('admin-ajax.php?action=geodir_ajax_action');
}

/////////////////////
/* ON INIT ACTIONS */
/////////////////////

add_action('init', 'geodir_on_init',1);

add_action('init','geodir_add_post_filters');

//add_action('init', 'geodir_init_defaults');

add_action('init','geodir_session_start');

add_action('init','geodir_allow_post_type_frontend');

add_action( 'init', 'geodir_register_taxonomies', 1 );

add_action( 'init', 'geodir_register_post_types', 2 );

add_filter('geodir_post_type_args', 'geodir_post_type_args_modify',0, 2) ;

//add_action( 'init', 'geodir_flush_rewrite_rules', 99 );

add_action( 'init', 'geodir_custom_post_status' );

add_action('widgets_init', 'geodir_register_sidebar'); // Takes care of widgets

global $geodir_addon_list ;
apply_filters('geodir_build_addon_list' , $geodir_addon_list) ;

add_action('wp_ajax_geodir_ajax_action', "geodir_ajax_handler");

add_action( 'wp_ajax_nopriv_geodir_ajax_action', 'geodir_ajax_handler' );

/* Pluploader */
add_action('wp_ajax_plupload_action', "geodir_plupload_action");

add_action( 'wp_ajax_nopriv_plupload_action', 'geodir_plupload_action' ); // call for not logged in ajax

////////////////////////
/* Widget Initalizaion */
////////////////////////

add_action('widgets_init', 'register_geodir_widgets'); 

////////////////////////
/* REWRITE RULES */
////////////////////////

add_filter( 'rewrite_rules_array','geodir_listing_rewrite_rules' );

////////////////////////
/* QUERY VARS */
////////////////////////

add_filter('query_vars', 'add_location_var');

add_action('wp', 'add_page_id_in_query_var'); // problem fix in wordpress 3.8

add_filter('parse_query', 'geodir_modified_query');


////////////////////////
/* ON WP LOAD ACTIONS */
////////////////////////

//add_action( 'wp_loaded','geodir_flush_rewrite_rules' );


/////////////////////////////
/* ON WP HEADE ACTIONS */
/////////////////////////////

add_action( 'wp_head', 'geodir_header_scripts');

add_action( 'admin_head', 'geodir_header_scripts');


add_action( 'wp_head', 'geodir_location_header_scripts');
	
add_action( 'admin_head', 'geodir_location_header_scripts');
	

add_action('wp_head','geodir_init_map_jason'); // Related to MAP
		
add_action('wp_head','geodir_init_map_canvas_array'); // Related to MAP

add_action( 'wp_head', 'geodir_restrict_widget' ); // Related to widgets

//////////////////////////////
/* ENQUE SCRIPTS AND STYLES */
//////////////////////////////

add_action( 'wp_enqueue_scripts', 'geodir_templates_scripts');

add_action( 'wp_enqueue_scripts', 'geodir_templates_styles',100);

////////////////////////
/* ON MAIN NAVIGATION */
////////////////////////
add_filter('wp_nav_menu_items','geodir_menu_items', 100, 2);

add_filter('wp_page_menu','geodir_pagemenu_items',100,2);


/////////////////////////
/* ON TEMPLATE INCLUDE */
/////////////////////////

add_filter( 'template_include', 'geodir_template_loader' );

/////////////////////////
/* CATEGORY / TAXONOMY / CUSTOM POST ACTIONS */
/////////////////////////

add_action('edited_term','geodir_update_markers_oncatedit',10,3);

add_filter('term_link', 'geodir_get_term_link', 10, 3);

add_filter('post_type_archive_link','geodir_get_posttype_link',10,2);

add_filter('post_type_link', 'geodir_listing_permalink_structure', 10, 4);

////////////////////////
/* POST AND LOOP ACTIONS */
////////////////////////
if(!is_admin()){
add_action('pre_get_posts' ,'geodir_exclude_page',100 ); /// Will help to exclude virtural page from everywhere

add_filter('wp_list_pages_excludes', 'exclude_from_wp_list_pages',100); /** Exclude Virtual Pages From Pages List **/ 

add_action('pre_get_posts','set_listing_request',0);

add_action('pre_get_posts','geodir_listing_loop_filter' ,1 );

add_action('excerpt_more', 'geodir_excerpt_more');

add_action('excerpt_length', 'geodir_excerpt_length');

add_action('the_post','create_marker_jason_of_posts'); // Add marker in json array, Map related filter
}


add_action( 'set_object_terms', 'geodir_set_post_terms', 10, 4 );

add_action('transition_post_status', 'geodir_update_poststatus',10,3);

add_action('before_delete_post','geodir_delete_listing_info');
////////////////////////
/* WP FOOTER ACTIONS */
////////////////////////

add_action('wp_footer','geodir_footer_scripts'); /* Footer Scripts loader */

add_action('wp_footer','send_marker_jason_to_js'); // Show map for listings with markers



add_action('admin_footer','geodir_localize_all_js_msg');

add_action('wp_footer','geodir_localize_all_js_msg');

add_action('admin_head-media-upload-popup','geodir_localize_all_js_msg');
add_action('customize_controls_print_footer_scripts','geodir_localize_all_js_msg');

add_action('wp_head', 'geodir_add_meta_keywords');

/* Sharelocation scripts */
//global $geodir_addon_list;
//if(!empty($geodir_addon_list) && array_key_exists('geodir_sharelocation_manager', $geodir_addon_list) && $geodir_addon_list['geodir_sharelocation_manager'] == 'yes') { 
add_action('wp_footer','geodir_add_sharelocation_scripts'); 
//}


/// add action for theme switch to blank previous theme navigation location setting 
add_action("switch_theme", "geodir_unset_prev_theme_nav_location", 10 , 2); 
function geodir_unset_prev_theme_nav_location($newname, $newtheme) {
	update_option('geodir_theme_location_nav', '');
}


require_once ('geodirectory-functions/custom_taxonomy_hooks_actions.php');



function geodir_add_post_filters()
{	
	include_once( 'geodirectory-functions/listing_filters.php' );
}




if( !function_exists('geodir_init_defaults') ){
	function geodir_init_defaults(){
		if(function_exists('geodir_register_defaults')){
			
			geodir_register_defaults();
			
		}
			 
	}
}







/* Header Scripts loader */
//add_action( 'admin_head', 'geodir_header_scripts');





/* Content Wrappers */
//add_action( 'geodir_before_main_content', 'geodir_output_content_wrapper', 10);
//add_action( 'geodir_after_main_content', 'geodir_output_content_wrapper_end', 10);


/* Sidebar */
add_action( 'geodir_sidebar', 'geodir_get_sidebar', 10);


/* Pagination in loop-store */
add_action( 'geodir_pagination', 'geodir_pagination', 10 );









/** Add Custom Menu Items **/


/** Replaces "Post" in the update messages for custom post types on the "Edit" post screen. **/
add_filter( 'post_updated_messages', 'geodir_custom_update_messages' );
	


// CALLED ON 'sidebars_widgets' FILTER

if(!function_exists('geodir_restrict_widget')){
	function geodir_restrict_widget(){
		global $is_listing,$is_single_place;
		
		// set is listing	
		(geodir_is_page('listing')) ? $is_listing = true : $is_listing = false; 
		
		// set is single place
		(geodir_is_page('place')) ? $is_single_place = true : $is_single_place = false; 
		
		
	} 
}

add_filter( 'sidebars_widgets', 'widget_logic_filter_sidebars_widgets', 10);
if(!function_exists( 'widget_logic_filter_sidebars_widgets')){	
	function widget_logic_filter_sidebars_widgets($sidebars_widgets)
	{	
		global $is_listing,$is_single_place;		
		
		if(!empty($sidebars_widgets)):
		// loop through every widget in every sidebar (barring 'wp_inactive_widgets') checking WL for each one
			foreach($sidebars_widgets as $widget_area => $widget_list)
			{	
				if( $widget_area == 'wp_inactive_widgets' || empty($widget_list) ) continue;
				
				foreach($widget_list as $pos => $widget_id)
				{
					if(!$is_listing && strstr($widget_id,'geodir_map_listingpage'))
					{	unset($sidebars_widgets[$widget_area][$pos]);
						continue;
					}
					
					if(!$is_single_place && strstr($widget_id,'geodir_single_place_map'))
					{	unset($sidebars_widgets[$widget_area][$pos]);
						continue;
					}
				}
			}
		endif;
		return $sidebars_widgets;
	}
}

/////// GEO DIRECOTORY CUSTOM HOOKS ///

add_action('geodir_before_tab_content', 'geodir_before_tab_content');// this function is in custom_functions.php and it is used to wrap detail page tab content 
add_action('geodir_after_tab_content', 'geodir_after_tab_content');// this function is in custom_functions.php and it is used to wrap detail page tab content

// Detail page sidebar content 
add_action('geodir_detail_page_sidebar', 'geodir_detail_page_sidebar_content_sorting', 1);
function geodir_detail_page_sidebar_content_sorting()
{
	$arr_detail_page_sidebar_content =
	apply_filters('geodir_detail_page_sitebar_content' , 
					array( 	'geodir_social_sharing_buttons',
							'geodir_share_this_button',
							'geodir_detail_page_google_analytics',
							'geodir_edit_post_link',
							'geodir_detail_page_review_rating',
							'geodir_detail_page_more_info'
						) // end of array 
				); // end of apply filter
	if(!empty($arr_detail_page_sidebar_content))
	{
		foreach($arr_detail_page_sidebar_content as $content_function)
		{
			if(function_exists($content_function))
			{
				add_action('geodir_detail_page_sidebar' , $content_function);	
			}
		}
	}
}

add_action('geodir_after_edit_post_link' , 'geodir_add_to_favourite_link',1) ;
function geodir_add_to_favourite_link()
{
	global $post,$preview;
    if(!$preview)
    {
	?>
    <p class="edit_link">
    <?php	geodir_favourite_html($post->post_author,$post->ID);	?>
    </p>
    <?php 
    }
}

function geodir_social_sharing_buttons()
{
	global $post,$preview,$post_images; 
	ob_start() ; // Start  buffering;
	do_action('geodir_before_social_sharing_buttons') ;
	if(!$preview)
	{?>
                <div class="likethis">
                    	 <a href="http://twitter.com/share" class="twitter-share-button"><?php _e('Tweet',GEODIRECTORY_TEXTDOMAIN);?></a>
                        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> 
                    	<iframe <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){echo 'allowtransparency="true"'; }?> class="facebook" src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light" style="border:none; overflow:hidden; width:100px; height:20px"></iframe> 
                    
                        <div id="plusone-div" class="g-plusone" data-size="small"></div>
                        <script type="text/javascript">
						  (function() {
							var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							po.src = 'https://apis.google.com/js/platform.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script>                    
                 </div>
<?php
	}// end of if, if its a preview or not
	do_action('geodir_after_social_sharing_buttons') ;
	$content_html = ob_get_clean();
	if(trim($content_html) != '')
		$content_html = '<div class="geodir-company_info">' . $content_html . '</div>' ;
	echo $content_html = apply_filters('geodir_social_sharing_buttons_html' , $content_html) ;
			
		
}


function geodir_share_this_button()
{
	global $post,$preview,$post_images; 
	ob_start() ; // Start buffering;
	do_action('geodir_before_share_this_button') ;
	if(!$preview)
	{?>
                <div class="share clearfix">
                	
                    <div class="addthis_toolbox addthis_default_style">
                     <span id='st_sharethis' ></span>
                        <script type="text/javascript">var switchTo5x=false;</script>
                        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                        <script type="text/javascript">stLight.options({publisher: "f47874ba-eaf2-4978-a27d-52ad4db5140e", doNotHash: false, doNotCopy: false, hashAddressBar: false});
						stWidget.addEntry({
						"service":"sharethis",
						"element":document.getElementById('st_sharethis'),
						"url":"http://sharethis.com",
						"title":"sharethis",
						"type":"chicklet",
						"text":"<?php _e( 'Share', GEODIRECTORY_TEXTDOMAIN );?>"    
						});</script>
                       
                    </div>
                    
                </div>
    <?php
	}// end of if, if its a preview or not
	do_action('geodir_after_share_this_button') ;
	$content_html = ob_get_clean();
	if(trim($content_html) != '')
		$content_html = '<div class="geodir-company_info">' . $content_html . '</div>' ;
	echo $content_html = apply_filters('geodir_share_this_button_html' , $content_html) ;

}


function geodir_edit_post_link()
{
	global $post,$preview,$post_images; 
	ob_start() ; // Start buffering;
	do_action('geodir_before_edit_post_link') ; 
	if(!$preview)
	{
		//if(is_user_logged_in() && $post->post_author == get_current_user_id())
		
		 $is_current_user_owner = geodir_listing_belong_to_current_user();
		 if($is_current_user_owner)
		 {
	
        
            $post_id = $post->ID;
                                if(isset($_REQUEST['pid']) && $_REQUEST['pid'] != ''){

                                    $post_id = $_REQUEST['pid'];
                                }
                                        
            $postlink = get_permalink( get_option('geodir_add_listing_page') );
            $editlink = geodir_getlink($postlink,array('pid'=>$post_id),false);
            echo ' <p class="edit_link"><a href="'.$editlink.'">'.__('Edit this Post',GEODIRECTORY_TEXTDOMAIN).'</a></p>';
        } 
    }// end of if, if its a preview or not
	do_action('geodir_after_edit_post_link') ;
	$content_html = ob_get_clean();
	if(trim($content_html) != '')
		$content_html  = '<div class="geodir-company_info">' . $content_html . '</div>' ;
	echo $content_html = apply_filters('geodir_edit_post_link_html' , $content_html) ;
			
		
}


function geodir_detail_page_google_analytics()
{
	global $post,$preview,$post_images; 
	$package_info = array();
	$package_info = geodir_post_package_info( $package_info , $post);
	if(isset($package_info->google_analytics))
		$package_info->google_analytics = false;
	ob_start() ; // Start buffering;
	do_action('geodir_before_google_analytics') ;
	if(	get_option('geodir_ga_stats') && get_edit_post_link() && is_user_logged_in() && ( isset($package_info->google_analytics) && $package_info->google_analytics != '' ) )
	{ 
		$page_url = $postlink; 
	?>
				
			<script type="text/javascript">
			jQuery(document).ready(function(){
					jQuery("#ga_stats").load("<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>");
			});
			</script>
				<p id="ga_stats"><img src="<?php echo geodir_plugin_url().'/geodirectory-assets/images/ajax-loader.gif'; ?>" /></p>
			
		<?php 
	} 
    do_action('geodir_after_google_analytics') ;
	$content_html = ob_get_clean();
	if(trim($content_html) != '')
		$content_html  = '<div class="geodir-company_info">' . $content_html . '</div>' ;
	echo $content_html = apply_filters('geodir_google_analytic_html' , $content_html) ;
}

function geodir_detail_page_review_rating()
{ 
	global $post,$preview,$post_images; 
	ob_start() ; // Start  buffering;
	do_action('geodir_before_detail_page_review_rating') ;
	$comment_count = isset($post->rating_count) ? $post->rating_count : 0;
	$post_ratings = geodir_get_postoverall($post->ID);
	 
	if($post_ratings != 0 && !$preview){
		
		if($comment_count > 0)
			$post_avgratings = ($post_ratings / $comment_count);
		else
			$post_avgratings = $post_ratings;
			
			do_action('geodir_before_review_rating_stars_on_detail' , $post_avgratings , $post->ID) ;
			
			$html = '<p style=" float:left;">';
		
			$html .= geodir_get_rating_stars($post_avgratings,$post->ID);
			
			$html .= '<div class="average-review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">';
			 
		 	$post_avgratings = is_float($post_avgratings) ? number_format($post_avgratings, 1, '.', '') : $post_avgratings;
		 
		 	if($comment_count>1){
		 		$html .= '<span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"><span class="rating" itemprop="average">'.$post_avgratings.'</span> /  <span itemprop="best">5</span> '.__("based on",GEODIRECTORY_TEXTDOMAIN).' <span class="count" itemprop="count">'.$comment_count.'</span> '.__("reviews",GEODIRECTORY_TEXTDOMAIN).'</span><br />';
		 	}else{
		 		$html .= '<span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"><span class="rating" itemprop="average">'.$post_avgratings.'</span> /  <span itemprop="best">5</span> '.__("based on",GEODIRECTORY_TEXTDOMAIN).' <span class="count" itemprop="count">'.$comment_count.'</span> '.__("review",GEODIRECTORY_TEXTDOMAIN).'</span><br />';	 
		 	}
		 	$html .= '<span class="item">';
		 	$html .= '<span class="fn" itemprop="itemreviewed">'.$post->post_title.'</span>';
		 	if($post_images){foreach($post_images as $img){$post_img = $img->src;break;}}
		 	if($post_img){$html .= '<br /><img src="'.$post_img.'" class="photo hreview-img"  alt="'.$post->post_title.'" itemprop="photo" />';}
		 	$html .= '</span>';

		 	echo $html .= '</div>';
		
		do_action('geodir_after_review_rating_stars_on_detail' , $post_avgratings , $post->ID);
		
	}
	do_action('geodir_after_detail_page_review_rating') ;
	$content_html = ob_get_clean();
	if(trim($content_html) != '')
		$content_html  = '<div class="geodir-company_info">' . $content_html . '</div>' ;
	echo $content_html = apply_filters('geodir_detail_page_review_rating_html' , $content_html) ;
	
}


function geodir_detail_page_more_info()
{
	global $post,$preview,$post_images; 
	ob_start() ; // Start  buffering;
	do_action('geodir_before_detail_page_more_info') ;
	if($geodir_post_detail_fields = geodir_show_listing_info()){
		echo $geodir_post_detail_fields;
	}
	do_action('geodir_after_detail_page_more_info') ;
	
	$content_html = ob_get_clean();
	if(trim($content_html) != '')
		$content_html  = '<div class="geodir-company_info">' . $content_html . '</div>' ;
	echo $content_html = apply_filters('geodir_detail_page_more_info_html' , $content_html) ;
}

function geodir_localize_all_js_msg()
{
	$arr_alert_msg = array(
							'geodir_plugin_url' => geodir_plugin_url(),
							'geodir_admin_ajax_url' => admin_url('admin-ajax.php'),
							
							'custom_field_not_blank_var' =>__('HTML Variable Name must not be blank',GEODIRECTORY_TEXTDOMAIN),
						
							'custom_field_not_special_char' =>	__('Please do not use special character and spaces in HTML Variable Name.',GEODIRECTORY_TEXTDOMAIN),
						
							'custom_field_unique_name' =>	__('HTML Variable Name should be a unique name.',GEODIRECTORY_TEXTDOMAIN),	
						
							'custom_field_delete' =>	__('Are you wish to delete this field?',GEODIRECTORY_TEXTDOMAIN),
						
						//start not show alert msg
						
							'tax_meta_class_succ_del_msg' =>	__('File has been successfully deleted.',GEODIRECTORY_TEXTDOMAIN),	
							
							'tax_meta_class_not_permission_to_del_msg' =>	__('You do NOT have permission to delete this file.',GEODIRECTORY_TEXTDOMAIN),
							
							'tax_meta_class_order_save_msg' =>	__('Order saved!',GEODIRECTORY_TEXTDOMAIN),
						
					  		'tax_meta_class_not_permission_record_img_msg' =>	__('You do not have permission to reorder images.', GEODIRECTORY_TEXTDOMAIN),
							
							'address_not_found_on_map_msg' =>	__('Address not found for:', GEODIRECTORY_TEXTDOMAIN),
						
							// end not show alert msg
						
							'my_place_listing_del' =>	__('Are you wish to delete this listing?', GEODIRECTORY_TEXTDOMAIN),
						
							//start not show alert msg
						
							'rating_error_msg' =>	__('Error : please retry', GEODIRECTORY_TEXTDOMAIN),
						
							'listing_url_prefix_msg' =>	__('Please enter listing url prefix', GEODIRECTORY_TEXTDOMAIN),

							'invalid_listing_prefix_msg' =>	__('Invalid character in listing url prefix', GEODIRECTORY_TEXTDOMAIN),
						
							'location_url_prefix_msg' =>	__('Please enter location url prefix', GEODIRECTORY_TEXTDOMAIN),
						
							'invalid_location_prefix_msg' =>	__('Invalid character in location url prefix', GEODIRECTORY_TEXTDOMAIN),
													
							'location_and_cat_url_separator_msg' =>	__('Please enter location and category url separator', GEODIRECTORY_TEXTDOMAIN),
						
							'invalid_char_and_cat_url_separator_msg' =>	__('Invalid character in location and category url separator', GEODIRECTORY_TEXTDOMAIN),
						
							'listing_det_url_separator_msg' =>	__('Please enter listing detail url separator', GEODIRECTORY_TEXTDOMAIN),
						
							'invalid_char_listing_det_url_separator_msg' =>	__('Invalid character in listing detail url separator', GEODIRECTORY_TEXTDOMAIN),
							'loading_listing_error_favorite' =>  __('Error loading listing.',GEODIRECTORY_TEXTDOMAIN),
							'geodir_field_id_required' =>  __('This field is required.',GEODIRECTORY_TEXTDOMAIN),
							'geodir_valid_email_address_msg' =>  __('Please enter valid email address.',GEODIRECTORY_TEXTDOMAIN),
							// end not show alert msg
							
						);
	
	foreach ( $arr_alert_msg as $key => $value ) 
	{
		if ( !is_scalar($value) )
			continue;
		$arr_alert_msg[$key] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8');
	}

	$script = "var geodir_all_js_msg = " . json_encode($arr_alert_msg) . ';';
	echo '<script>';
	echo $script ;	
	echo '</script>'	;
}

add_action( 'admin_bar_menu', 'geodir_admin_bar_site_menu', 31 );
function geodir_admin_bar_site_menu($wp_admin_bar)
{
	if(get_option( "geodir_installed" ))
	{
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'geodirectory', 'title' => __('GeoDirectory', GEODIRECTORY_TEXTDOMAIN), 'href' => admin_url('?page=geodirectory') ) );
	}
}

//add_action('geodir_tax_sort_options', 'geodir_display_tax_sort_options'); /*function in custom_functions.php*/
add_action('geodir_before_listing', 'geodir_display_sort_options'); /*function in custom_functions.php*/

//add_filter('geodir_sort_options', 'geodir_sort_options_update'); /*function in custom_functions.php*/

add_filter('geodir_posts_order_by_sort', 'geodir_posts_order_by_custom_sort', 0, 3);

add_filter('geodir_advance_custom_fields_heading', 'geodir_advance_customfields_heading', 0, 2);


add_action('switch_theme', 'geodir_store_sidebars');
function geodir_store_sidebars()
{
	global $geodir_sidebars;
	global $sidebars_widgets;

	if ( ! is_array( $sidebars_widgets ) )
		$sidebars_widgets = wp_get_sidebars_widgets();
	$geodir_old_sidebars=array();
	
	if(is_array($geodir_sidebars))
	{
		foreach($geodir_sidebars as $val)
		{
			if(is_array($sidebars_widgets))
			{
				if(array_key_exists($val, $sidebars_widgets))
					$geodir_old_sidebars[$val] = $sidebars_widgets[$val];
				else
					$geodir_old_sidebars[$val] = array();
			}
		} 
	}
	update_option('geodir_sidebars' ,$geodir_old_sidebars );
	
}

add_action('after_switch_theme', 'geodir_restore_sidebars');
function geodir_restore_sidebars()
{
	global $sidebars_widgets;

	if ( ! is_array( $sidebars_widgets ) )
		$sidebars_widgets = wp_get_sidebars_widgets();
	
	if(is_array($sidebars_widgets))
	{
		$geodir_old_sidebars=get_option('geodir_sidebars');
		if(is_array($geodir_old_sidebars))
		{
			foreach($geodir_old_sidebars as $key => $val)
			{
				//if(array_key_exists($key, $sidebars_widgets))
				{
					$sidebars_widgets[$key] = $geodir_old_sidebars[$key] ;
				}
				
				
			}
		}
		
		// now clear all non geodiretory sidebars 
		foreach($sidebars_widgets as $key => $val)
		{
			if(!array_key_exists($key , $geodir_old_sidebars))
			{
				$sidebars_widgets[$key] = array();
			}
		}
	}
	
	update_option('sidebars_widgets' , $sidebars_widgets) ;
	update_option('geodir_sidebars' ,'' );
}

add_action('geodir_after_listing_post_gridview', 'geodir_after_listing_post_gridview');
function geodir_after_listing_post_gridview(){
	global $gridview_columns;
	
	$gridview_columns = '';
	
}
/*
add_filter('script_loader_src' , 'geodir_script_loader_src');

function geodir_script_loader_src($url)
{
	if (strstr($url, "maps") !== false) {
       echo  $url = str_replace("&amp;", "&", $url); // or $url = $original_url
    }
	return $url ;
}*/

add_filter('clean_url', 'so_handle_038', 99, 3);
function so_handle_038($url, $original_url, $_context) {
    if (strstr($url, "maps.google.com/maps/api/js") !== false) {
      	$url = str_replace("&#038;", "&amp;", $url); // or $url = $original_url
    }

    return $url;
}


add_action('geodir_after_main_form_fields', 'geodir_after_main_form_fields', 1);
function geodir_after_main_form_fields(){
	
	if(get_option('geodir_accept_term_condition')){
		global $post;
		$term_condition = '';
		if(isset($_REQUEST['backandedit'])){
			$post = (object)unserialize($_SESSION['listing']);
			$term_condition = isset($post->geodir_accept_term_condition) ? $post->geodir_accept_term_condition : '';	
		}
		
	?>
	<div class="required_field geodir_form_row clearfix">
				<label>&nbsp;</label>
				<div class="geodir_taxonomy_field" style="float:left; width:70%;">
				<span style="display:block"> 
				<input class="main_list_selecter" type="checkbox" <?php if($term_condition == '1'){echo 'checked="checked"';} ?> field_type="checkbox" name="geodir_accept_term_condition" id="geodir_accept_term_condition" class="geodir_textfield" value="1" style="display:inline-block"/><?php echo stripslashes(get_option('geodir_term_condition_content')); ?> 
				</span>
			</div>
			 <span class="geodir_message_error"><?php if(isset($required_msg)){ echo $required_msg;}?></span>
		</div>
	<?php
	
	}
}


/* ------------------------------START CODE FOR HIDE/DISPLAY TABS */

add_filter('geodir_detail_page_tab_is_display', 'geodir_detail_page_tab_is_display', 0, 2);

function geodir_detail_page_tab_is_display($is_display, $tab){

	global $post,$post_images,$video,$special_offers, $related_listing,$geodir_post_detail_fields;
	
	if($tab == 'post_info')
		$is_display = (!empty($geodir_post_detail_fields)) ? true : false;
	
	if($tab == 'post_images')
		$is_display = (!empty($post_images)) ? true : false;
	
	if($tab == 'post_video')
		$is_display = (!empty($video)) ? true : false;
	
	if($tab == 'special_offers')
		$is_display = (!empty($special_offers)) ? true : false;
	
	if($tab == 'reviews')
		$is_display = (geodir_is_page('detail')) ? true : false;
	
	if($tab == 'related_listing')
		$is_display = ((strpos($related_listing,__('No listings found which match your selection.',GEODIRECTORY_TEXTDOMAIN)) !== false || $related_listing == '' || !geodir_is_page('detail')) ) ? false : true;	
	
	
	return $is_display;
}


/*======================================*/
/*	Add an action to show a message on core plugin row for deactivation. */
/*=======================================*/
global $plugin_file_name;
add_action( 'after_plugin_row_' . $plugin_file_name, 'geodir_after_core_plugin_row' , 3, 3);


function geodir_after_core_plugin_row($plugin_file, $plugin_data, $status)
{
	//echo $plugin_file . " " .  $plugin_data . " " . $status ;
	if(is_plugin_active($plugin_file))
	{
		$wp_list_table = _get_list_table('WP_Plugins_List_Table');
	
		echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="geodir-plugin-row-warning">';
		_e('Deactivate all GeoDirectory depended adons first before deactivating GeoDirectory.',GEODIRECTORY_TEXTDOMAIN)  ;
		echo '</div></td></tr>';	
	}
}




