<?php
/*
Plugin Name:List Sub Pages modified
Plugin URI: http://www.weblineindia.com/
Description: Creates a widget and  shortcode with different functionalities for displaying the sub pages or parent pages.
Author: Weblineindia, datagutten
Version: 1.2
Author URI: http://www.weblineindia.com/
*/

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$ls_plugin_data = get_plugin_data( __FILE__ );
	
define('SUBPAGE_VERSION', $ls_plugin_data['Version']);

register_activation_hook( __FILE__, 'ls_default_option_value' );

register_uninstall_hook(__FILE__,'ls_delete_option_value');

/**
*  ls_default_option_value() will add the default values in the database.  
*
*  @return             void
*  @var                No arguments passed
*  @author             HG
*/

function ls_default_option_value()
{
	$default_values=array(
				    'version'=>SUBPAGE_VERSION,
			);
	 add_option('list_subpage_settings',$default_values);
}

/**
*  ls_delete_option_value() will add the default values in the database.
*
*  @return             void
*  @var                No arguments passed
*  @author             HG
*/

function ls_delete_option_value()
{
	 delete_option('list_subpage_settings',$default_values);
}


/**
*  myplugin_init() will load a text domain for multiple language support
*
*  @return             void             
*  @var                No arguments passed
*  @author             HG

*/

add_action('plugins_loaded',array('ls_sub_pages','ls_myplugin_init'));
add_action( 'widgets_init', 'ls_sub_pages',1 );

/**
*  ls_sub_pages() is used to register the widget.
*
*  @return             void
*  @var                No arguments passed
*  @author             HG
* 
*/

function ls_sub_pages() {
	register_widget( 'ls_sub_pages' );
}
	
/**
*  ls_sub_pages class extends the WP_Widget class functionalities and contains different functions which handles the admin and front end sides of the widget.
*
*  @author     HG
 
*/

class ls_sub_pages extends WP_Widget
{
	public function __construct()
	{
		
		$widget_ops = array('classname' => 'ls_sub_pages','description' => __('Displays the list of subpages for that particular parent page else will display the list of parent pages.','subpage')
				);
		
		parent::__construct('ls_sub_pages', __('Sub Pages Widget','subpage'), $widget_ops);
		add_shortcode('sub_page','ls_shortcode');
		
	}
	
	/**
	*  form() is used to display a form of widgets edit option page.
	*
	*  @return             form
	*  @var                $instance return type is array.
	*  @author             HG
	
	*/
	
    public function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','sort_order' => '', 'sort_by'=>'','exclude_pages'=>'','depth'=>'','sort_order_parent'=>'' ) );
		
		$exclude_pages=array();
		$title = $instance['title'];
		$sort_order=$instance['sort_order'];
		$sort_by=$instance['sort_by'];
		$exclude_pages=$instance['exclude_pages'];
		$depth=$instance['depth'];
		$sort_order_parent=$instance['sort_order_parent']; ?>
		
		<!-- Title Field -->
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'subpage'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
				
		<!-- Sort Order Field Starts-->
		
		<label for="help_text"><?php _e('Options for Displaying the Subpages','subpage')?></label> 
		<p>
  			<label for="<?php echo $this->get_field_id('sort_order'); ?>"><?php _e('Sorting Order', 'subpage'); ?></label>
 <select name="<?php echo $this->get_field_name('sort_order'); ?>" id="<?php echo $this->get_field_id('sort_order'); ?>" class="widefat">	
<?php
		
			$order_options = array(
				'ASC'=>__('Ascending','subpage'), 
				'DESC'=>__('Descending','subpage'),
			);
			foreach($order_options as $option_key=>$option_value) { ?>
				<option <?php selected( $instance['sort_order'], $option_key ); ?> value="<?php echo esc_attr($option_key); ?>"><?php echo __($option_value,'subpage'); ?></option>
			<?php } ?>      
</select>&nbsp;&nbsp;
		</p>
		
		
		<!-- Sorting Criteria Field -->
		
		<label for="help_text1"><?php _e('Options for Displaying the Parent Pages when their is no subpages to be displayed','subpage');?></label>
<p>
<label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e('Sorting Criteria', 'subpage'); ?></label>
<select multiple="multiple" name="<?php echo $this->get_field_name('sort_by'); ?>[]" id="<?php echo $this->get_field_id('sort_by'); ?>" class="widefat">

<?php 
	$options = array(		 'ID'=>__('Page ID','subpage'),
					 'post_title'=>__('Page Title','subpage'),
					 'menu_order'=>__('Menu Order','subpage'),
					 'post_date'=>__('Date Created','subpage'),
					 'post_modified'=>__('Date Modified','subpage'),
					 'post_author'=>__('Page Author','subpage'),
					 'post_name'=>__('Post Slug','subpage')
				);
	foreach($options as $key=>$value) { 

  		if (is_array($instance['sort_by'])) {
			if(in_array($key,$instance['sort_by'])) {
				$selected = "selected=selected";
			}
			else {
				$selected = "";
			}
		}
		else if($key == "ID"){
			$selected = "selected=selected";
		}
		else{
			$selected = "";			
		}?>
  <option <?php echo $selected?> value="<?php echo esc_attr($key); ?>"><?php echo __($value,'subpage'); ?></option>
   <?php } ?>      
</select>
</p>
		
<!-- Exclude Pages Field -->
<p>
 <label for="<?php echo $this->get_field_id('exclude_pages'); ?>"><?php _e('Exclude Pages', 'subpage'); ?></label>

<select multiple="multiple" name="<?php echo $this->get_field_name('exclude_pages'); ?>[]" id="<?php 	echo $this->get_field_id('exclude_pages'); ?>" class="widefat">

<?php
	$pages=get_pages();
	foreach($pages as $page) { 
		if(is_array($exclude_pages) && in_array($page->ID,$exclude_pages)) {
				$selected = "selected='selected'";
		}
		else{
			$selected = "";
		}
		?>
		<option <?php echo $selected;?> value="<?php echo esc_attr($page->ID); ?>"><?php echo $page->post_title; ?></option>
<?php }
?> 
</select>
</p>

<!-- Depth Level Field -->		
<p>
  <label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e('Depth Level', 'subpage'); ?></label>
 <select name="<?php echo $this->get_field_name('depth'); ?>" id="<?php echo $this->get_field_id('depth'); ?>" class="widefat">	
<?php
	$depth_options = array('1'=>__('1st Level Depth','subpage'),
						   '2'=>__('2nd Level Depth','subpage'),
						   '3'=>__('3rd Level Depth','subpage'),
						   '4'=>__('4th Level Depth','subpage'),
						   '0'=>__('Unlimited Depth','subpage')
			);
		foreach($depth_options as $depth_number=>$depth_label) { ?>
                <option <?php selected( $instance['depth'], $depth_number ); ?> value="<?php echo esc_attr($depth_number); ?>"><?php echo __($depth_label,'subpage'); ?></option>
                <?php } ?>      
</select>&nbsp;&nbsp;
</p>

		
<!-- Parent Sort Order Field -->
<p>
  <label for="<?php echo $this->get_field_id('sort_order'); ?>"><?php _e('Sorting Order For Parent 	Pages', 'subpage'); ?></label>
 <select name="<?php echo $this->get_field_name('sort_order_parent'); ?>" id="<?php echo $this->get_field_id('sort_order_parent'); ?>" class="widefat">	
<?php
	$order_options = array('ASC'=>__('Ascending','subpage'),
			       'DESC'=>__('Descending','subpage')
			   );
		foreach($order_options as $option_key=>$option_value) { ?>
                <option <?php selected( $instance['sort_order_parent'], $option_key ); ?> value="<?php echo esc_attr($option_key); ?>"><?php echo __($option_value,'subpage'); ?></option>
                <?php } ?>      
</select>&nbsp;&nbsp;
</p>

<?php }
 
/**
 *  Updates the Value of the instance in the Database.
 *
 *  @return             ARRAY
 *  @var               	$new_instance,$old_instance
 *  @author             HG

 */
	
 function update($new_instance, $old_instance)
  {	
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['sort_order'] = $new_instance['sort_order'];
    $instance['sort_by'] = $new_instance['sort_by'];
    $instance['exclude_pages']= $new_instance['exclude_pages'];
    $instance['depth']= $new_instance['depth'];
    $instance['sort_order_parent']= $new_instance['sort_order_parent'];
    return $instance;
  }
  
  
/**
 *  Shows the functionality on the UI side where the widget is placed.
 *
 *  @return             ARRAY
 *  @var                $args,$instance
 *  @author             HG

 */
  
function widget($args, $instance)
 {
 	global $post;
  	extract($args,EXTR_SKIP);
	
   echo $before_widget;
    $title = empty($instance['title']) ? 'Pages' : apply_filters('widget_title', $instance['title']);
   
 	$sort_order=empty($instance['sort_order']) ? 'ASC' : apply_filters('widget_sort', $instance['sort_order']);
 	
 	if(count($instance['sort_by']) > 0)
 	{
 		$sort_by_values=implode(',',$instance['sort_by']);
 	}
 	else
 	{
 		$sort_by_values='';
 	}
 	
 	if(count($instance['exclude_pages']) > 0){
		$exclude_page_id=implode(',',$instance['exclude_pages']);
	}
	else
	{
		$exclude_page_id='';
	}
	

	$depth=empty($instance['depth']) ? '1' : apply_filters('widget_depth', $instance['depth']);
	
 	$sort_order_parent=empty($instance['sort_order_parent']) ? 'ASC' : apply_filters('widget_depth', $instance['sort_order_parent']);
 	
 	
    if (!empty($title))
      echo $before_title . $title . $after_title;
 	
    // WIDGET CODE GOES HERE
    	
    	
    $page_id= $post->ID;
    	$args = array(
    			'order' => $sort_order,
    			'post_parent' => $page_id,
    			'post_status' => 'publish',
    			'post_type' => 'page',
    	);
    	
    	$attachments = get_children( $args ); ?>
 <ul class="ls_page_list">
 <?php 
    	if($attachments)
    	{
    		foreach($attachments as $attachment)
    	    {?>
    	    			<li><a href="<?php echo $attachment->guid;?>"><?php echo $attachment->post_title;?></a></li>	
    	    		<?php 	
    	    }
    	 }
    	 else 
    	 {	
    	   $args = array(
    			   'depth'=> $depth,
			   'title_li' => '',
    			   'echo' => 1,
    			   'sort_order'=>$sort_order_parent,
			   'sort_column' => $sort_by_values,
    			   'post_type'    => 'page',
    			   'post_status'  => 'publish',
    	 	           'exclude'=>$exclude_page_id,
    					);
    			   $pages=wp_list_pages($args);
    				
    			   print_r($pages);		
    	 }?>
   </ul>
	
 <?php
	 echo $after_widget; 
   }
   
/**
 *  Returns the path of the plugin directory.
 *
 *  @return             STRING
 *  @var                No arguments passed
 *  @author             HG
*/
public function ls_get_plugin_url()
{	
   	return plugin_dir_path(__FILE__);
}


/**
    *  Returns plugin folders path. 
    *
    *  @return             STRING
    *  @var    		   No arguments passed         
    *  @author             HG
   
*/   	
 public static function ls_get_plugin_dir_url()
 {
  		return plugins_url('', __FILE__);
 }
 
 public static function ls_myplugin_init() {
	
	load_plugin_textdomain( 'subpage', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
 }
}
$sub_page_object=new ls_sub_pages();

include_once($sub_page_object->ls_get_plugin_url().'/views/Back-End/create_shortcode.php');
?>