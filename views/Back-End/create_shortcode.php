<?php 

add_action( 'admin_menu', 'ls_admin_menu' );
add_action('admin_enqueue_scripts','ls_add_script');

/**
 *  It enqueues the js files for the plugins settings page.
 *
 *  @return    void
 *  @var	No arguments passed
 *  @author    HG
  
 */

function ls_add_script()
{   
	global 	$ls_plugin_version;

	if( !wp_script_is( 'jquery' ) )
	{
		wp_enqueue_script('jquery');
	}
	
	wp_enqueue_script('shortcode_js',ls_sub_pages::ls_get_plugin_dir_url().'/js/jquery_create_shortocode.js',array('jquery'), $ls_plugin_version);
	
}

/**
 *  It is used to add the option page for the plugin page.
 *
 *  @return     void
 *  @var	No arguments passed
 *  @author     HG
  
 */
function ls_admin_menu() {
	add_options_page( 'List Sub Pages','List Sub Pages','manage_options', 'sub-page', 'ls_options_page' );
}

/**
 *  It is used to render the form for the settings page.
 *
 *  @return     form
 *  @var	No arguments passed
 *  @author     HG

 */
function ls_options_page() {

//$result='0';
 
?>
    <div class="wrap">
        <h2><?php _e('Sub Page Plugin Options','subpage'); ?></h2>
        
        <form action="" method="POST">
            
             <h3><?php _e('Sub Page Display Options','subpage'); ?></h3>
             <h4><?php _e('Select the subpage dislaying options if subpage exists for that particular parent page.','subpage');?></h4>
          
             <table class="form-table">
             	<tbody>
             		
             		<tr valign="top">
             		<th scope="row"><?php _e('Title','subpage');?></th>
             		<!-- Title Field -->
             		<td>
             		<?php 
				echo "<input id='title' type='text' name='title' value='Pages' />";?>
             		</td>
             		</tr>
             		
             		<tr valign="top">
             		<th scope="row"><?php _e('Sort Order','subpage');?></th>
             		
             		<!-- Sort Order Field -->
             		<td>
             			
	
			<select id='child_sort_order' name="child_sort_order"  tabindex="1">
			<?php 
		
				$order_options = array(
							'ASC'=>__('Ascending','subpage'), 
							'DESC'=>__('Descending','subpage')
							);
			foreach ($order_options as $option_key=>$option_value ) { ?>
				<option
					value="<?php echo esc_attr($option_key); ?>"><?php echo __($option_value,'subpage'); ?>
				</option>
				<?php }?>
	</select>
             		</td>
             		</tr>
             	
             	</tbody>
             </table>
             
             <h3><?php _e('Parent Pages Display Options','subpage'); ?></h3>
             <h4><?php _e('Select the options to add effect in the parent pages display when subpages are not available to display.','subpage');?></h4>
             
             <table class="form-table">
             	<tbody>
             		<tr valign="top">
             		<th scope="row"> <?php _e('Sorting Criteria:','subpage');?></th>
             		
             		<!-- Sorting Criteria Field -->
             		<td>
				<select id='sorting_criteria' name="sorting_criteria[]" size="7" multiple="multiple" tabindex="1">
					<?php
						$options = array(
								'ID'=>__('Page ID','subpage'),
								'post_title'=>__('Page Title','subpage'),
								'menu_order'=>__('Menu Order','subpage'),
								'post_date'=>__('Date Created','subpage'),
								'post_modified'=>__('Date Modified','subpage'),
								'post_author'=>__('Page Author','subpage'),
								'post_name'=>__('Post Slug','subpage')
								);
							
						foreach ( $options as $key=>$value ) {?>
							<option
								value="<?php echo $key; ?>">
								<?php echo __($value,'subpage'); ?>
							</option>
							<?php } ?>
				</select>
             		</td>
             		</tr>
             		<tr valign="top">
             		<th scope="row"><?php _e('Exclude Pages','subpage');?></th>
             		
             		<!-- Exclude Pages Field -->
             		<td>
             			<select id='exclude_page' name="exclude_page[]" size="6" multiple="multiple"
						tabindex="1">
						
							<?php 
							
								$pages = get_pages();
								foreach ( $pages as $page ) { 
							?>
							<option
								value="<?php echo esc_attr($page->ID);?>">
							<?php echo __("$page->post_title"); ?>
							</option>
							<?php } ?>
					</select>
             		</td>
             		
             		<tr valign="top">
             		<th scope="row"><?php _e('Depth Level','subpage'); ?></th>
             		
             		<!-- Depth Level Field -->	
             		<td>
             			<select id='depth_level' name="depth_level"  tabindex="1">
					<?php
						$depth_options = array(
							'1'=>__('1st Level Depth','subpage'),
							'2'=>__('2nd Level Depth','subpage'),
							'3'=>__('3rd Level Depth','subpage'),
							'4'=>__('4th Level Depth','subpage'),
							'0'=>__('Unlimited Depth','subpage')
						);
						foreach($depth_options as $depth_number=>$depth_label) {   ?>
						<option
							value="<?php echo $depth_number; ?>"><?php echo __($depth_label,'subpage'); ?>
						</option>
					<?php }?>
				</select>
             		</td>
             		
             		<tr valign="top">
             		<th scope="row"><?php _e('Parent Sort Order','subpage'); ?></th>
             		<!-- Parent Sort Order Field -->
             		<td>
				<select id='parent_sort_order' name="parent_sort_order"  tabindex="1">
					<?php 
						$order_options = array(
									'ASC'=>__('Ascending','subpage'),
									'DESC'=>__('Descending','subpage')
									);
						foreach ($order_options as $option_key=>$option_value ) { ?>
						<option
							value="<?php echo esc_attr($option_key); ?>"><?php echo __($option_value,'subpage'); ?>
						</option>
					<?php }?>
				</select>
             		</td>
             		</tr>
             	</tbody>
             </table>
              <h3><?php _e('Dynamic Shortcode','subpage');?></h3>
             <h4> <?php _e('Please Copy This Shortcode and paste where you want to display subpages or parent pages.','subpage');?></h4>
             <table class="form-table">
             	<tbody>
             		<tr valign="top">
             		<th scope="row"><?php _e('Dynamic Shortcode:','subpage');?></th>
             		<td>
             			<?php echo '<div id= "shortcode"></div>';?>
             		</td>
             </tr></tbody></table>
        </form>
    </div>
    <?php
}

$dom = new DOMDocument;
$dom->formatOutput = true;

/**
 * Create a <ul> list of sub pages recursing into the given depth
 *
 * @param string $post_id Post ID
 * @param $atts
 * @param bool $current_depth
 *
 * @return bool|DOMElement ul object
 * @since 2.0.0
 *
 */
function list_children( $post_id, $atts, $current_depth = false ) {
	global $dom;
	$args = array(
		'sort_order' => $atts['sort_order'],
		'sort_column' => $atts['sort_by_values'],
		'parent' => $post_id,
		'post_status' => 'publish',
		'post_type' => 'page',
		'depth' => 1,
	);
	if ( current_user_can( 'read_private_pages' ) ) {
		$args['post_status'] = 'publish,private';
	}
	if ( false === $current_depth ) {
		$current_depth = 1;
	} else {
		$current_depth++;
	}

	$pages = get_pages( $args );
	if ( empty( $pages ) ) {
		return false;
	}

	$ul = $dom->createElement( 'ul' );

	foreach ( $pages as $current_post ) {
		$li = $dom->createElement( 'li' );
		$ul->appendChild( $li );
		$li->setAttribute( 'class','level' . $current_depth );

		$a = $dom->createElement( 'a', $current_post->post_title );
		$a->setAttribute( 'href', $current_post->guid );
		$li->appendChild( $a );

		$ul_sub = list_children( $current_post->ID,$atts,$current_depth );
		if ( false !== $ul_sub && (0 == $atts['depth'] || $current_depth < $atts['depth']) ) {
			$li->appendChild( $ul_sub );
		}
	}
	return $ul;
}

/**
 *  It is used to render the output when the shortcode is called.
 *
 * @return false|string|void
 * @param array Attributes
 * @author             HG
 */
function ls_shortcode( $atts ) {
	global $post;
	global $dom;
	$atts = shortcode_atts(array(
					'title' => 'Pages',
					'sort_order' => 'ASC',
					'sort_by_values' => 'page_title',
					'exclude_page_id' => '',
					'depth' => '1',
					'sort_order_parent' => 'ASC',
	), $atts);
	$ul = list_children( $post->ID,$atts );
	if ( false === $ul ) {
		return;
	}

	$div = $dom->createElement( 'div' );
	$div->setAttribute( 'class','ls_container' );

	$h3 = $dom->createElement( 'h3',$atts['title'] );
	$h3->setAttribute( 'class','widget_title' );
	$div->appendChild( $h3 );

	$ul->setAttribute( 'class','ls_page_list' );
	$div->appendChild( $ul );

	return $dom->saveXML( $div );
}
