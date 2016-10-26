<?php
/*
Plugin Name: CAHNRSWP Lyris Segments
Plugin URI: http://cahnrs.wsu.edu/communications/
Description: Create WordPress and Lyris connection using WordPress categories and Lyris Segments or Interests
Version: 1.6.1
Author: Don Pierce
Author URI: http://cahnrs.wsu.edu/communications/
License: GPL2
*/
/*
$new_general_setting = new new_general_setting();

class new_general_setting {
    function new_general_setting( ) {
        add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
    }
    function register_fields() {
        register_setting( 'general', 'wpl_email_list', 'esc_attr' );
        add_settings_field('wpl_email_list', '<label for="wpl_email_list">'.__('Default Lyris Email List' , 'wpl_email_list' ).'</label>' , array(&$this, 'fields_html') , 'general' );
    }
    function fields_html() {
        $value = get_option( 'wpl_email_list', '' );
        echo '<input type="text" id="wpl_email_list" name="wpl_email_list" value="' . $value . '" />';
    }
}
*/


add_action('admin_init', 'wpl_options_init' );
add_action('admin_menu', 'wpl_options_add_page');


// Init plugin options to white list our options
function wpl_options_init(){
	register_setting( 'wpl_options_options', 'wpl_list_options', 'wpl_options_validate' );
	}

// Add menu page
function wpl_options_add_page() {
	add_options_page('Lyris List Options', 'Lyris List Options', 'manage_options', 'wpl_options', 'wpl_options_do_page');
}

// Draw the menu page itself
function wpl_options_do_page() {
	?>
	<div class="wrap">
		<h2>Lyris List Integration Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('wpl_options_options'); ?>
			<?php $options = get_option('wpl_list_options'); ?>
			<table class="form-table">
            
<!--            				<tr valign="top"><th scope="row">A Checkbox</th> 
					<td><input name="wpl_list_options[option1]" type="checkbox" value="1" <?php // checked('1', $options['option1']); ?> /></td>
				</tr>
                
//-->                
				<tr valign="top"><th scope="row">List Name</th>
					<td><input type="text" name="wpl_list_options[list_name]" value="<?php echo $options['list_name']; ?>" /></td>
				</tr>

				<tr valign="top"><th scope="row">List Admin Name</th>
					<td><input type="text" name="wpl_list_options[list_admin_name]" value="<?php echo $options['list_admin_name']; ?>" /></td>
				</tr>


				<tr valign="top"><th scope="row">List Admin Email</th>
					<td><input type="text" name="wpl_list_options[list_admin_email]" value="<?php echo $options['list_admin_email']; ?>" /></td>
				</tr>

                
                <tr valign="top"><th scope="row">List Admin Password</th>
					<td><input type="text" name="wpl_list_options[list_password]" value="<?php echo $options['list_password']; ?>" /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wpl_options_validate($input) {
	// Our first value is either 0 or 1
//	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
	
	// Say our second option must be safe text with no HTML tags
	$input['list_name'] =  wp_filter_nohtml_kses($input['list_name']);
	$input['list_admin_name'] =  wp_filter_nohtml_kses($input['list_admin_name']);
	$input['list_admin_email'] =  wp_filter_nohtml_kses($input['list_admin_email']);
	$input['list_password'] =  wp_filter_nohtml_kses($input['list_password']);
	
	return $input;
}


function lyris_segments_admin_init() {
//	echo 'inside lyris_segments_admin_init';
	//wp_enqueue_script('jquery');
	//wp_enqueue_script( 'select_all', plugins_url($path = 'wp-lyris-segments/js/selectall.js'), array( 'jquery'));	
	wp_enqueue_script('jquery');
	wp_register_script( 'select_all', plugins_url('/js/selectall.js', __FILE__), array('jquery'));
    wp_enqueue_script( 'select_all' );

}

function html_form_code() {
	
//	$lyris_list_name = get_option( 'wpl_email_list', '' );
//	echo $lyris_list_name;
	
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Your Name (required) <br/>';
    echo '<input type="text" name="mcf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["mmcf-name"] ) ? esc_attr( $_POST["mcf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br/>';
    echo '<input type="email" name="mcf-email" value="' . ( isset( $_POST["mmcf-email"] ) ? esc_attr( $_POST["mcf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
	echo 'Select Interest Area <br/>';
//	wp_dropdown_categories('show_option_none=&child_of=313&orderby=name');
// drop down code	
//	echo '<select name="categories">';
  // Add custom option as default
//   echo '<option>' . __('No Category', 'text-domain') . '</option>';
  // Get categories as array
//   $categories = get_categories( 'child_of=313' );
//   foreach ( $categories as $category ) :
    // Check if current term ID is equal to term ID stored in database
//    $selected = ( $stored_category_id ==  $category->term_id  ) ? 'selected' : '';
//	echo '<option value="' . $category->name . '" ' . $selected . '>' . $category->name . '</option>';
//	endforeach;
//    echo '</select>';
// end of drop down code
$args = array( 'hide_empty' => 0, 'taxonomy'=> 'topics', 'orderby' => 'slug', 'order' => 'ASC'); // 

//$categories=get_categories('child_of=313&hide_empty=0');
$categories=get_categories($args);
	echo "<input type='checkbox' id='select_all'  />Select All";
	echo '<br />';
	foreach($categories as $category) {
		echo "<input type='checkbox' name='mychecky[]' value='$category->cat_name' />";
		echo $category->cat_name;
		echo '<br />';    }
		

//	wp_category_checklist(0, 313);
//	echo '<input type="email" name="mcf-email" value="' . ( isset( $_POST["mmcf-email"] ) ? esc_attr( $_POST["mcf-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
    echo '<p>';
	
	
 //   echo 'Subject (required) <br/>';
 //   echo '<input type="text" name="mcf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["mcf-subject"] ) ? esc_attr( $_POST["mcf-subject"] ) : '' ) . '" size="40" />';
 //   echo '</p>';
    echo '<p>';
//    echo 'Your Message (required) <br/>';
//    echo '<textarea rows="10" cols="35" name="mcf-message">' . ( isset( $_POST["mcf-message"] ) ? esc_attr( $_POST["mcf-message"] ) : '' ) . '</textarea>';
//    echo '</p>';
    echo '<p><input type="submit" name="mcf-submitted" value="Send"></p>';
   echo '</form>';
}

// descr_excert - break up string into fixed lengh fields separated by commas

function descr_excerpt($string, $width, $start)
{
$your_desired_width = $width;
$string = mb_substr($string, $start, $your_desired_width+1);


if (strlen($string) > $your_desired_width)
{
    $string = wordwrap($string, $your_desired_width);

	$ipos = strrpos($string, ",");
    if ($ipos) {
        $string = mb_substr($string, 0, $ipos);
    }
}
return array($string,$ipos);
} // end descr_excert - break up string into fixed lengh fields 
 
function deliver_mail() {
 
    // if the submit button is clicked, send the email
    if ( isset( $_POST['mcf-submitted'] ) ) {
 
        // sanitize form values
        $name    = sanitize_text_field( $_POST["mcf-name"] );
        $email   = sanitize_email( $_POST["mcf-email"] );
//		$interest = sanitize_text_field($_POST["categories"]);  
//		$interest = sanitize_text_field($_POST["mychecky"]);  
		 if(isset($_POST["mychecky"])) {
//			$interest=serialize($_POST["mychecky"]);
			$interest=$_POST["mychecky"]; 
			$sinterest=implode(",",$interest);
//			$sinteresttot=strlen(implode($interest));
//			$sinterestnums=array_map('strlen',$interest);
//			print_r($interest);
//			echo("<br />");
//			print_r($sinterest);
//			echo("<br />");
//			print_r($sinteresttot);
//			echo("<br />");
//			foreach ($sinterestnums as $inum) {
//			echo $interest[$inum];
//			echo ", ";
//			echo $sinterestnums[$inum];
//     		echo("<br />");
//			}

			
			
		}
//		$firststringpos = strpos($sinterest,',',49);
//		$firststringpos = substr($sinterest, 0, 49);
//		$firststringposn = strrpos( substr($sinterest, 0, 49), ',');
//		print_r($firststringpos);
 //   	echo("<br />");
//		print_r($firststringposn);
//		echo("<br />");
//		$countytruncated = substr($sinterest,0,strpos($sinterest,',',49));
//    	$countytruncated = substr($sinterest,0,strrpos( substr($sinterest, 0, 49), ','));	
//		print_r($countytruncated);
//		echo("<br />");
//		print_r($countytruncatedn);
//		echo("<br />");
//		$secondstrpos = strpos( substr($sinterest, 50, 95),',');
//		$secondstrpos = substr($sinterest, 50, 99);
//		$secondstrposn = strrpos( substr($sinterest, 50, 99),',');
		
//		print_r($secondstrpos);
//		echo("<br />");
//		print_r($secondstrposn);
//     	echo("<br />");
//		$intereststruncated = substr($sinterest,50,strpos($sinterest,',',95));
//		$intereststruncated = substr($sinterest,50,strrpos( substr($sinterest, 50, 99),','));
//    	print_r($intereststruncated);
//		echo("<br />");
//        $occupationtruncated = substr($sinterest,100,strpos($sinterest,',',129));
//		$thirdstrpos = substr($sinterest, 100, 129);
//		$thirdtrposn = strrpos( substr($sinterest, 100, 129),',');
		
//		print_r($thirdstrpos);
//		echo("<br />");
//		print_r($thirdtrposn);
//     	echo("<br />");

//		$occupationtruncated = substr($sinterest,100,strrpos( substr($sinterest, 100, 129),','));
//		print_r($occupationtruncated);
//		echo("<br />");

		
//		$postalcodetruncated = substr($sinterest,130,strpos($sinterest,',',159));
//        $postalcodetruncated = substr($sinterest,130,strrpos( substr($sinterest, 130, 159),','));
//		print_r($postalcodetruncated);
//		echo("<br />");
//        $stateprovincetruncated = substr($sinterest,160,strpos($sinterest,',',180));
		
//		$stateprovincetruncated = substr($sinterest,160,strrpos( substr($sinterest, 160, 179),','));
//		print_r($stateprovincetruncated);
//		echo("<br />");
		
		$sinterestdash = str_replace(" ","-",$sinterest);
		$intervals = array(50, 50, 30, 30, 20);
		$start = 0;
		$parts = array();
		$k =0;
		
		foreach ($intervals as $i)
		{
	        $parts[] = descr_excerpt($sinterestdash, $i, $start);
            $start += ($i - ($i - $parts[$k][1]))+1;
            $k += 1;
        }
		$countytruncated = $parts[0][0];
        $intereststruncated = $parts[1][0];
        $occupationtruncated = $parts[2][0];
        $postalcodetruncated = $parts[3][0];
        $stateprovincetruncated = $parts[4][0];
		
//		$lyris_list_name = get_option( 'wpl_email_list', '' );
		$lyris_list_options = get_option('wpl_list_options');
		$lyris_list_name = $lyris_list_options['list_name'];
	
		
//        $subject = sanitize_text_field( $_POST["mcf-subject"] );
		$subject = "";
//        $message = esc_textarea( $_POST["mcf-message"] );
//        $message = "login w@zzu12*" . "\r\n";
        $message = "login " . $lyris_list_options['list_password'] . "\r\n";		
		
//        $message = $message . "add testdp subform sendconfirm dwpierce@live.com Don LiveDotCom" . "\r\n\r\n";
        $message = $message . "become " . $email . "\r\n";
//		$message = $message . "become dwpierce@live.com" . "\r\n";
//		$message = $message . "set testdp" . "\r\n\r\n";
//		$message = $message . 'set testdp setfield(Interests_="whitmanwa")' . "\r\n";
//		$message = $message . 'set testdp setfield(Interests_="' . $sinterest . '")' . "\r\n";	/* last correct functioning code		

//        $message = $message . 'set testdp setfield(County="' . $countytruncated . '")' . "\r\n";	
//        $message = $message . 'set testdp setfield(Interests_="' . $intereststruncated . '")' . "\r\n";	
//        $message = $message . 'set testdp setfield(Occupation_="' . $occupationtruncated . '")' . "\r\n";	
//        $message = $message . 'set testdp setfield(Postal_Code_="' . $postalcodetruncated . '")' . "\r\n";	
//        $message = $message . 'set testdp setfield(State_Province_="' . $stateprovincetruncated . '")' . "\r\n";	

        $message = $message . 'set '. $lyris_list_options['list_name'] . ' setfield(County="' . $countytruncated . '") quiet' . "\r\n";	
        $message = $message . 'set '. $lyris_list_options['list_name'] . ' setfield(Interests_="' . $intereststruncated . '") quiet' . "\r\n";	
        $message = $message . 'set '. $lyris_list_options['list_name'] . ' setfield(Occupation_="' . $occupationtruncated . '") quiet' . "\r\n";	
        $message = $message . 'set '. $lyris_list_options['list_name'] . ' setfield(Postal_Code_="' . $postalcodetruncated . '") quiet' . "\r\n";	
        $message = $message . 'set '. $lyris_list_options['list_name'] . ' setfield(State_Province_="' . $stateprovincetruncated . '") quiet' . "\r\n";	


        $message = $message . "End" ;
 
        // get the blog administrator's email address
//        $to = get_option( 'admin_email' );
//		$to = 'dwpierce@wsu.edu';
//		$to = "listmanager@lyris.cahnrs.wsu.edu";
		$to = array('listmanager@lyris.cahnrs.wsu.edu','dwpierce@wsu.edu', 'small.grains@wsu.edu');
		
 
//        $headers = "From: $name <$email>" . "\r\n";
//		 $headers = 'From: Pierce, Donald Warren <dwpierce@wsu.edu>;' . "\r\n";
		 $headers = 'From: ' . $lyris_list_options['list_admin_name'] . '<' . $lyris_list_options['list_admin_email'] . '>;' . "\r\n";
//		echo $headers; 
 
        // If email has been process for sending, display a success message	
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thank you for subscribing to Small Grains Categories! Further action is required via email for you subscription to be complete.</p>';
//			echo '<p>If you are subscribing for the first time, you will receive a confirmation email to verify you subscription. Once confirmed the list administrator will need to approve your subscription.</p>';
//			echo '<p>Otherwise, the changes to the List Segments will automatically be updated.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
			echo '<p/>the message: ' . $message;
        }
    }
}
 
function mcf_shortcode() {
	lyris_segments_admin_init();
    ob_start();
    deliver_mail();
    html_form_code();
 
    return ob_get_clean();
}

// add_action( 'phpmailer_init', 'wpse8170_phpmailer_init' );

// function wpse8170_phpmailer_init( PHPMailer $phpmailer ) {
//    $phpmailer->Host = 'smtp.wsu.edu';
//    $phpmailer->Port = 25; // could be different
//    $phpmailer->Username = 'dwpierce'; // if required
//    $phpmailer->Password = '*******'; // if required
//    $phpmailer->SMTPAuth = true; // if required
//    $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value

//    $phpmailer->IsSMTP();
//}

add_action( 'phpmailer_init', 'configure_smtp' );

function configure_smtp( PHPMailer $phpmailer ){
    $phpmailer->isSMTP(); //switch to smtp
    $phpmailer->Host = 'smtp.wsu.edu';
//    $phpmailer->SMTPAuth = false;
    $phpmailer->Port = 25;
//    $phpmailer->Username = 'dwpierce';
//    $phpmailer->Password = '*******';
//    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->From = 'small.grains@wsu.edu';
    $phpmailer->FromName='WSU Small Grains';
}

 
add_shortcode( 'my_contact_form', 'mcf_shortcode' );

function segment_html_form_code() {
	
	
$current_user = wp_get_current_user();
//echo $current_user->user_login;
if ((user_can( $current_user, 'administrator' ) || user_can( $current_user, 'webadmin' ) || user_can( $current_user, 'editor' )) && is_user_logged_in())
 {
	
	$segment_lyris_list_options = get_option('wpl_list_options');

    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p><b>Select Segments to send email</b></p>';
    echo '<p>';
	
    $args = array( 'hide_empty' => 0, 'taxonomy'=> 'topics'); // 
 
   $segment_categories=get_categories($args);
   echo "<input type='checkbox' id='select_all'  /><b>Select All</b>";
	echo '<br />';
	foreach($segment_categories as $segment_category) {
//		$segment_category_slug = 'smallgrains.'.$segment_category->slug.'@lyris.cahnrs.wsu.edu';
    	$segment_category_slug = $segment_lyris_list_options['list_name'].'.'.$segment_category->slug.'@lyris.cahnrs.wsu.edu';
//		echo "<input type='checkbox' name='segment_mychecky[]' value='$category->slug' />";
//    	echo '<input type="checkbox" name="segment_mychecky[]" value="$segment_category_slug" />';
    	echo '<input type="checkbox" name="segment_mychecky[]" value="' . $segment_category_slug . '" />';
//		echo 'smallgrains.'.$category->slug.'@lyris.cahnrs.wsu.edu';
		echo '<b>'. $segment_category->cat_name . '</b> ('. $segment_category_slug . ')';
		echo '<br />';    }
		echo '</p>';
      echo '<p>';
	
  echo 'Your Name (required) <br />';
  echo '<input type="text" name="segment-cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["segment-cf-name"] ) ? esc_attr( $_POST["segment-cf-name"] ) : '' ) . '" size="40" />';
  
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="segment-cf-email" value="' . ( isset( $_POST["segment-cf-email"] ) ? esc_attr( $_POST["segment-cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="segment-cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["segment-cf-subject"] ) ? esc_attr( $_POST["segment-cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="20" cols="115" name="segment-cf-message">' . ( isset( $_POST["segment-cf-message"] ) ? esc_attr( $_POST["segment-cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" name="segment-cf-submitted" value="Send"/></p>';
    echo '</form>';
 } //End of if user_can
 else {
	 echo "You do not have permission to send Small Grains team listserv emails ";
 }
} // End of segment_html_form_code

 function segment_deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['segment-cf-submitted'] ) ) {
    

        // sanitize form values
        $name    = sanitize_text_field( $_POST["segment-cf-name"] );
        $email   = sanitize_email( $_POST["segment-cf-email"] );
        $subject = sanitize_text_field( $_POST["segment-cf-subject"] );
        $message = esc_textarea( $_POST["segment-cf-message"] );

        // get the blog administrator's email address
//        $to = get_option( 'admin_email' );

		 if(isset($_POST["segment_mychecky"])) {

           $interest=$_POST["segment_mychecky"]; 
//           $segment_emails_values=$_POST["segment_mychecky"]; 
           $segment_emails_values=implode(",",$interest);

//	    $segment_emails_values=$_POST['segment_mychecky[]']; 
//	    $segment_emails=implode("segment_mychecky[]"); 
//			$sinterest=implode(",",$interest);
		 }
		    
        $to = $segment_emails_values;
  //      echo '$segment_emails_values is: ' . $segment_emails_values;
		
        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message

		
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578

} // End of segment_deliver_mail


function segment_cf_shortcode() {
	lyris_segments_admin_init();
    ob_start();
    segment_deliver_mail();
    segment_html_form_code();

    return ob_get_clean();
} //End of segment_cf_shortcode

add_filter( 'phpmailer_init', 'rw_change_phpmailer_object' );

function rw_change_phpmailer_object( $phpmailer )
{
    $phpmailer->IsHTML( false );
}

add_shortcode( 'segment_contact_form', 'segment_cf_shortcode' );


 
/**
 * Add custom taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function add_custom_taxonomies() {
  // Add new "Small Grains Listserv" taxonomy to Posts
 
  register_taxonomy('topics', 'post', array(
    // Hierarchical taxonomy (like categories)
    'hierarchical' => true,
	'archive_layout' => 'full',
    // This array of options controls the labels displayed in the WordPress Admin UI
    'labels' => array(
      'name' => _x( 'Topics', 'taxonomy general name' ),
      'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Topics' ),
      'all_items' => __( 'All Topics' ),
      'parent_item' => __( 'Parent Topic' ),
      'parent_item_colon' => __( 'Parent Topic:' ),
      'edit_item' => __( 'Edit Topic' ),
      'update_item' => __( 'Update Topic' ),
      'add_new_item' => __( 'Add New Topic' ),
      'new_item_name' => __( 'New Topic Name' ),
      'menu_name' => __( 'Topics' ),
    ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug' => 'topics', // This controls the base slug that will display before each term
      'with_front' => false, // Don't display the category base before "/topics/"
      'hierarchical' => true // This will allow URL's like "/topics/topicname/subtopic/"
    ),
  ));  
 
  
}
add_action( 'init', 'add_custom_taxonomies', 0 );
 

?>