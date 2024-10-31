<?php
/*
Plugin Name: WP nFusion Solutions Widgets
Plugin URI: https://nfusionsolutions.com/
Description: To show nFusion widgets using shortcode and widgets.
Author: nFusion Solutions
Version: 1.2.8
*/

//function to register custom post type
function custom_post_nFusion_Widgets() {
  $labels = array(
    'name'               => _x( 'nFusion Widgets', 'nFusion Widgets type general name' ),
    'singular_name'      => _x( 'nFusion-Widgets', 'nFusion Widgets type singular name' ),
    'add_new'            => _x( 'Add New', 'nFusion Widgets' ),
    'add_new_item'       => __( 'Add New nFusion Widgets' ),
    'edit_item'          => __( 'Edit nFusion Widgets' ),
    'new_item'           => __( 'New nFusion Widgets' ),
    'all_items'          => __( 'All nFusion Widgets' ),
    'view_item' 	=> __( 'View nFusion Widgets' ),
	'view_link'          => __( 'desi employee1' ),
    'search_items'       => __( 'Search nFusion Widgets' ),
    'not_found'          => __( 'No nFusion Widgets found' ),
    'not_found_in_trash' => __( 'No nFusion Widgets found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'nFusion Widgets'
  );
  
  $supports = array('title');
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our nFusion Widgets and nFusion-Widgets specific data',
    'public'        => true,
    'supports'      => $supports,
    'has_archive'   => true,
	'exclude_from_search' => false,
	'show_ui'     => true,
	'show_in_menu'  =>  true,
	'menu_icon' => 'dashicons-chart-area',
  );
  register_post_type( 'nfusion-widgets', $args ); 
}

add_action( 'init', 'custom_post_nFusion_Widgets' );

//function to fet meta value
function nFusion_get_meta($meta_name, $post){
	$meta_data = get_post_meta($post->ID, $meta_name, true);
	
	if( !empty($meta_data) )
		$save_meta = $meta_data;
	else
		$save_meta = '';
	
	return $save_meta;
}


// function to generate random string
function nfusionsolutions_widgets_Random_generator($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// Add meta box for widgets option
add_action( 'add_meta_boxes', 'nFusion_member_widgets_option_box' );

function nFusion_member_widgets_option_box() {
    add_meta_box( 
        'nFusion_member_widgets_option_box',
        'Widget Options',
        'nFusion_member_widgets_option_box_content',
        'nfusion-widgets',
        'normal',
        'high'
    );
}

function nFusion_widgets_enqueue_admin() {
	wp_enqueue_script( 'nfusion_admin_script', plugin_dir_url( __FILE__ ).'includes/script.js', array('jquery'), filemtime(plugin_dir_path( __FILE__ ).'includes/script.js'));
}
add_action('admin_enqueue_scripts', 'nFusion_widgets_enqueue_admin');

function nFusion_widgets_enqueue_frontend() {
	wp_enqueue_script( 'nfusion_multi-currency_script', 'https://widgetcdn.nfusionsolutions.com/asset/static/2/common/1/js/currency-interop.min.js', array('jquery'));

	wp_enqueue_style( 'nfusion-widgets-css', plugin_dir_url( __FILE__ ).'includes/widgets.css', array(), filemtime(plugin_dir_path( __FILE__ ).'includes/widgets.css'));
}
add_action('wp_enqueue_scripts', 'nFusion_widgets_enqueue_frontend');

function nFusion_member_widgets_option_box_content( $post ) {
	 
	  echo '<div class="option-parameter ">';
	  echo '<label for="nfusion_chart_code">Widget Url:</label> ';
	  $nfusion_chart_code = nFusion_get_meta('nfusion_chart_code', $post);
	  echo '<input size="75" type="text" id="nfusion_chart_code" name="nfusion_chart_code" placeholder="Widget Url:" value="'.$nfusion_chart_code. '"/>';
	  echo '</div>';
	  echo '<br>';
	  echo '<div class="option-parameter">';
	  echo '<label for="nfusion_parameters">Parameters:</label> ';
	  $nfusion_parameters = nFusion_get_meta('nfusion_parameters', $post);
	  echo '<input type="text" id="nfusion_parameters" name="nfusion_parameters" placeholder="(e.g. p1=1&p2=2)" value="'.$nfusion_parameters. '"/>';
	  echo '</div>';
	  
	  $nfusion_full_fidth = nFusion_get_meta('nfusion_full_fidth', $post);
	  if ($nfusion_full_fidth == 'yes') {
					$selected1 = ' selected="selected" ';
				}
	  if ($nfusion_full_fidth == 'no') {
					$selected2 = ' selected="selected" ';
				}	

	 echo  '<div class="option-full-width" style="margin-top:12px">';
	 echo  '<label for="nfusion_full_fidth">Full Width:</label>&nbsp;';
	 echo  '<select  onchange="changeOption(this.value)" id="nfusion_full_fidth" name="nfusion_full_fidth" style="width:194px; margin-left:7px">';
	 echo  '<option value="">Select Option</option>';
	 echo '<option '.$selected1.' value="yes">Yes</option>';
	 echo '<option '.$selected2.' value="no">No</option>';
	 echo '</select> ';
	 echo '</div>';
  ?> 
  
 
  <?php
  $display= 'style="display:none;"';
  if($nfusion_full_fidth == 'no'){
    $display= 'style="display:block;"';
  }
  echo '<div id="option-fixed-width" '.$display.'>';
  echo '<br>';
  echo '<label for="nfusion_fixed_width">Fixed Width:</label>';
  $nfusion_fixed_width = nFusion_get_meta('nfusion_fixed_width', $post);
  echo '<input type="text" id="nfusion_fixed_width" name="nfusion_fixed_width" placeholder="Fixed Width" value="'.$nfusion_fixed_width. '"/>';
   echo '</div>';
}

add_action( 'save_post', 'nFusion_member_widgets_option_box_save' );

function nFusion_member_widgets_option_box_save( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;
  
  if ( 'page' == sanitize_text_field($_POST['post_type'] ) ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }
  
  
  $field0= sanitize_text_field($_POST['nfusion_chart_code']);
  update_post_meta( $post_id, 'nfusion_chart_code', $field0);
  
  $field1= sanitize_text_field($_POST['nfusion_parameters']);
  update_post_meta( $post_id, 'nfusion_parameters', $field1);
  
  $field2= sanitize_text_field($_POST['nfusion_full_fidth']);
  update_post_meta( $post_id, 'nfusion_full_fidth', $field2);
  
  $field3= sanitize_text_field($_POST['nfusion_fixed_width']); 
  update_post_meta( $post_id, 'nfusion_fixed_width', $field3); 
}

// Add meta box for shortcode of member
add_action( 'add_meta_boxes', 'nFusion_member_shortcode_box' );

function nFusion_member_shortcode_box() {
    add_meta_box( 
        'nFusion_member_shortcode_box',
        'ShortCode',
        'nFusion_member_shotrcode_box_content',
        'nfusion-widgets',
        'normal',
        'high'
    );
}

function nFusion_member_shotrcode_box_content( $post ) {
	$post_id= $post->ID;
	$nfusion_full_fidth = get_post_meta($post_id, 'nfusion_full_fidth', true);
	$nfusion_parameters = get_post_meta($post_id,'nfusion_parameters',true);
	if($nfusion_full_fidth == 'yes'){
	  $width= 'full';
	}else{
	  $width= get_post_meta($post_id, 'nfusion_fixed_width', true);
	  if($width == ''){
	    $width= 'full';
	  }
	}
	if($nfusion_full_fidth  != ''){
    echo '<label for="nFusion_member_shortcode">Shortcode:</label>';
  ?>
  <input size="60" readonly type="text" id="nFusion_member_shortcode" name="nFusion_member_shortcode" placeholder="shortcode Profile Link Here" value="[nfusion-widget id='<?php echo $post_id;?>' <?php if($width != 'full'){ ?> width='<?php echo  $width;?>' <?php }?> <?php if($nfusion_parameters!= ''){ ?>parameters='<?php echo $nfusion_parameters; ?>'<?php } ?> ]"/>
  <?php
   }else{
    echo '<div>Shortcode will be genetaed after publish this widget.</div>';
   }
}

function nfusion_cpt_columns($columns) {

	$new_columns = array(
		'charturl' => __('Widget Url', ''),
		'shortcode' => __('ShortCode', ''),
	);
    return array_merge($columns, $new_columns);
}
add_filter('manage_nfusion-widgets_posts_columns' , 'nfusion_cpt_columns');

function nfusion_columns_filter( $columns ) {
   unset($columns['date']);
   return $columns;
}

// Custom Post Type
add_filter( 'manage_edit-nfusion-widgets_columns', 'nfusion_columns_filter',10, 1 );

   // Add to admin_init function
add_action('manage_nfusion-widgets_posts_custom_column', 'nfusionsolutions_widgets_manage_gallery_columns', 10, 2);
 
function nfusionsolutions_widgets_manage_gallery_columns($column_name, $id) {
    global $wpdb;
    switch ($column_name) {
    case 'charturl':
		echo get_post_meta($id, 'nfusion_chart_code', true);
            break;
 
    case 'shortcode':
		$post_id= $id;
		$nfusion_full_fidth = get_post_meta($post_id, 'nfusion_full_fidth', true);
		if($nfusion_full_fidth == 'yes'){
		  $width= 'full';
		}else{
		  $width= get_post_meta($post_id, 'nfusion_fixed_width', true);
		  if($width == ''){
			$width= 'full';
		  }
		}
		if($width== 'full'){
		 $shortcodewidth= "";
		}else{
		   $shortcodewidth= "width='$width'";
		}
		
		$nfusion_parameters = get_post_meta($post_id,'nfusion_parameters',true);
		if($nfusion_parameters !=''){
		 $parameters= "parameters='$nfusion_parameters'";
		}else{
		  $parameters= '';
		}
		 
       echo "[nfusion-widget id='$post_id'  $shortcodewidth $parameters]";
        break;
    default:
        break;
    } // end switch
}   

function nFusion_shortcode_function($atts, $content = null){
	
	extract(shortcode_atts(array(
		'id' => '',
		), $atts));
	
		$chart_id = $atts['id'];
		$width = $atts['width'];
		$chartcode = get_post_meta( $chart_id, 'nfusion_chart_code', true );
		
		if($atts['parameters'] != ''){
		   $parameters = '?'.$atts['parameters'];
		}

		if($width== 'full'){
			$width = "100%";
		}else{
			$width = $width."px";
		}	
			
		if($atts['width'] == ''){
            $width = "100%";		
	    }
		
		$unique_id = "nfusion".date('ymdhis').nfusionsolutions_widgets_Random_generator();
		$charturl = $chartcode."/".$unique_id.$parameters;
		$finalwidget = "<div style='width: $width' id='$unique_id'></div>\r\n";
		$finalwidget .='<script>
		(function(){
		nFSCurrencyHelper.init("'.$charturl.'");
		})();
		</script>';
				
		return $finalwidget;
}

function nfusionsolutions_widgets_register_shortcodes(){
   add_shortcode('nfusion-widget', 'nFusion_shortcode_function');
}
add_action( 'init', 'nfusionsolutions_widgets_register_shortcodes');

//widgets
add_action('widgets_init', function(){ return register_widget("nFusion_Navigation"); });

class nFusion_Navigation extends WP_Widget {

	var $arr_types = array(
		"wp_list_pages" => array("selectchart" => "wp_list_pages", "name" => "Select Chart" ),
	);
	
	function __construct() {
		parent::__construct('netgo_nFusion', 'nFusion Widget', array('description' => 'nFusion Widget', 'class' => 'netgo-nfusion-class'));	
	}
	
	function nfusionsolutions_widgets_Random_generator($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
    }  

	// outputs the options form on admin
	function form($instance) {
    
		// Title
		$field_id = $this->get_field_id('title');
		$field_name = $this->get_field_name('title');
		$title_value = esc_html($instance["title"]);
		echo "<p>";
		echo "<label for='$field_id'>Title</label>";
		echo "<input class='widefat' type='text' value='$title_value' name='$field_name' id='$field_id' />";
		echo "</p>";

		//select chart
		$field_id = $this->get_field_id('selectchart');
		$field_name = $this->get_field_name('selectchart');
		echo "<p>";
		echo "<label for='$field_id'>Select Widget</label>";
		echo "<select id='$field_id' name='$field_name' class='widefat'>";
		foreach ($this->arr_types as $type) {
			$selected = "";
			if ($instance["selectchart"] == $type["selectchart"]) {
				$selected = ' selected="selected" ';
			}
			echo "<option $selected value='{$type["selectchart"]}'>{$type["name"]}</option>";
		}
		
		$args = array(
			'post_type'  => 'nfusion-widgets',
			'post_status'      => 'publish',
		);
         $charts = get_posts( $args );
		
		foreach ( $charts as $chart ){
		$selected = "";
			if ($instance["selectchart"] == $chart->ID) {
				$selected = ' selected="selected" ';
		}	
			
		echo "<option $selected value='{$chart->ID}'>{$chart->post_title}</option>";	
		}
		echo "</select>";
		echo "</p>";
		
		 // Full Width
	     $randid = nfusionsolutions_widgets_Random_generator();	
		
		$fullfield_id = $this->get_field_id('fullwidth');
		$field_name = $this->get_field_name('fullwidth');
	    $title_value = esc_html($instance["fullwidth"]);
		
		if ($title_value == 'yes') {
				$selected1 = ' selected="selected" ';
			}
			
		if ($title_value == 'no') {
				$selected2 = ' selected="selected" ';
			}	
		echo "<p>";
		echo "<label for='fullwidth-$randid'>Full Width</label>";
		echo " <select onchange='changeOptionDrop(this.value ,this.id)' name='$field_name' id='$randid' style='width:123px' >
			  <option value='yes' $selected1>Yes</option>
			  <option value='no' $selected2>No</option>
			</select> ";
		echo "</p>";
		
		  // Fixed Width
		$field_id = $this->get_field_id('fixedwidth');
		
		$field_name = $this->get_field_name('fixedwidth');
		$title_value = esc_html($instance["fixedwidth"]);
		
		  $display= 'style="display:none;"';
		  if(esc_html($instance["fullwidth"]) == 'no'){
			$display= 'style="display:block;"';
		  }
		
		echo "<div class='to-fixed-width' $display id='to-fixed-width-$randid'>";
		echo "<input  placeholder='Fixed Width (enter width in pixel)' class='widefat' type='text' value='$title_value' name='$field_name' id='fixedwidth-$randid' />";
		echo '</div>';
	}
	
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance["title"] = $new_instance["title"];
		$instance["selectchart"] = $new_instance["selectchart"];
		$instance["fixedwidth"] = $new_instance["fixedwidth"];
		$instance["fullwidth"] = $new_instance["fullwidth"];
		return $instance;
	}

	// outputs the content of the widget
	function widget($args, $instance) {
		$chart_id = $instance["selectchart"] ;
		$width = $instance["fixedwidth"] ;
		$chartcode = get_post_meta( $chart_id, 'nfusion_chart_code', true );
		
		$parameters = get_post_meta( $chart_id, 'nfusion_parameters', true );
		if($parameters!='')
	    $parameters = '?'.$parameters;

		if($instance["fullwidth"]== 'yes')
		$width = "100%";
		else
		$width = $width."px";
        $unique_id = "nfusion".date('ymdhis').nfusionsolutions_widgets_Random_generator();
		
		$charturl= $chartcode."/".$unique_id.$parameters;
		$finalwidget = "<div style='width: $width' id='$unique_id'></div>";
		$finalwidget .='<script>
				(function(){
				var t = document.getElementsByTagName("script")[0];
				var s = document.createElement("script"); s.async = true;
				s.src = "'.$charturl.'";
				t.parentNode.insertBefore(s, t);
				})();
				</script>';
				
		echo  $finalwidget;
	}
}

if(!function_exists("nfusion_footer_poweredby")) {
	add_action('wp_footer', 'nfusion_footer_poweredby'); 
	function nfusion_footer_poweredby() { 
		echo '<div class="nfusion-poweredby"><a href="https://nfusionsolutions.com/data-feeds/">Precious Metals Data, Currency Data</a><a href="https://nfusionsolutions.com/charts-widgets/">, Charts, and Widgets</a> <a href="https://nfusionsolutions.com/">Powered by nFusion Solutions</a></div>'; 
	}	
}