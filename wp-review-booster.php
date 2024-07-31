<?php

/*
 * Plugin Name: WP Review Booster
 * Description: This is a custom plugin which is mainly used to post review in a bulk.
 * Author: Dhvani Barot
 * Version: 1.0
 */

if (!defined("ABSPATH"))
    exit;
if (!defined("REVIEW_BOOSTER_PLUGIN_DIR_PATH"))
    define("REVIEW_BOOSTER_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
if (!defined("REVIEW_BOOSTER_PLUGIN_URL"))
    define("REVIEW_BOOSTER_PLUGIN_URL", plugin_dir_url(__FILE__));





function review_booster_table() {
    global $wpdb;
    return $wpdb->prefix . "review_booster"; 
}
function reply_booster_table() {
    global $wpdb;
    return $wpdb->prefix . "reply_booster"; 
}
function product_booster_table() {
    global $wpdb;
    return $wpdb->prefix . "product_booster"; 
}
function namelist_booster_table() {
    global $wpdb;
    return $wpdb->prefix . "namelist_booster"; 
}
function purchasecode_booster_table() {
    global $wpdb;
    return $wpdb->prefix . "purchasecode_booster"; 
}



function review_booster_generates_table_script() {

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	
	$sql[] = "CREATE TABLE `" . product_booster_table() . "` (
            `product_id` int(11) NOT NULL AUTO_INCREMENT,
			`product_name` varchar(255) NOT NULL,
            `description` text,
			`status` CHAR(10) DEFAULT NULL,
			`total_review_recycle` int(11)  DEFAULT 0,
			`base_five` int(11) DEFAULT 0,
			`base_four` int(11) DEFAULT 0,
			`base_three` int(11) DEFAULT 0,
			`base_two` int(11) DEFAULT 0,
			`base_one` int(11) DEFAULT 0,
			`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`product_id`)
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	
	$sql[] = " CREATE TABLE `" . purchasecode_booster_table() . "` (
            `purchase_code_id` int(11) NOT NULL AUTO_INCREMENT,
			`purchase_code` varchar(255) NOT NULL,
            PRIMARY KEY (`purchase_code_id`)
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";


    $sql[] = "CREATE TABLE  `" . review_booster_table() . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
			`product_id` int(11) NULL,
			`user` varchar(100) DEFAULT NULL,
			`email` varchar(50),
			`user_image` text,
            `description` text,
            `schedule_start_date` timestamp NOT NULL,
			`rating` int(11),
			`review_rating` varchar(255) DEFAULT NULL,
			`status` CHAR(10) DEFAULT NULL,
			`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
			FOREIGN KEY (`product_id`) REFERENCES `wp_product_booster`(`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	
	 $sql[] = "  CREATE TABLE `" . reply_booster_table() . "` (
            `reply_id` int(11) NOT NULL AUTO_INCREMENT,
			`id` int(11) NOT NULL,
			`user` varchar(100) DEFAULT NULL,
			`user_image` text,
            `description` text,
			`start_date` timestamp NOT NULL,
			`reply_rating` varchar(255) DEFAULT NULL,
			`rating` int(11),
			`status` CHAR(10) DEFAULT NULL,
			`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`reply_id`),
			FOREIGN KEY (`id`) REFERENCES `wp_review_booster`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	$sql[] = " CREATE TABLE `" . namelist_booster_table() . "` (
            `name_id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
            PRIMARY KEY (`name_id`)
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		   
	
    dbDelta($sql);

}

register_activation_hook(__FILE__, "review_booster_generates_table_script");




/*function drop_table_plugin_review_booster() {
	
    global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS " . reply_booster_table());
	$wpdb->query("DROP TABLE IF EXISTS " . review_booster_table());
	$wpdb->query("DROP TABLE IF EXISTS " . product_booster_table());	
	$wpdb->query("DROP TABLE IF EXISTS " . namelist_booster_table());
	$wpdb->query("DROP TABLE IF EXISTS " . purchasecode_booster_table());	
    
}

register_uninstall_hook(__FILE__, "drop_table_plugin_review_booster");*/

function insert_namelist_plugin_review_booster(){
	global $wpdb;
	$table_name = namelist_booster_table();
	$namelist = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * from " . $table_name, ""
			), ARRAY_A
	);
	if(count($namelist) == 0){
		$names_list = array("John Smith","Joe Smith","Harsh Lad","Soham Patel","Chirag Bhuva", "Viken Patel","Sneha Prasad","Kumar Gaurav","Ria Saksena","Rohan Varma");
		foreach( $names_list as $key => $value )
		{
			$insert = $wpdb->insert($table_name,
					array(
						'name' => $value,
					));
		}
	}
}

register_activation_hook( __FILE__, 'insert_namelist_plugin_review_booster');


function review_booster_plugin_menus() {
	
    add_menu_page("Add Review", "Add Review", "manage_options", "product-list", "product_list", "dashicons-book-alt", 30);
	add_submenu_page("product-list", "Product List", "Product List", "manage_options", "product-list", "product_list");
	add_submenu_page("product-list", "Add Product", "Add Product", "manage_options", "product-add", "product_add");
	add_submenu_page("product-list", "Name List", "Name List", "manage_options", "name-list", "name_list");
	add_submenu_page("product-list", "Purchase Code", "Purchase Code", "manage_options", "purchase-code", "purchase_code");
	add_submenu_page("product-list", "Settings", "Settings", "manage_options", "review-settings", "review_settings");
   
    add_submenu_page("", "", "", "manage_options", "review-list", "review_booster");	
    add_submenu_page("", "", "", "manage_options", "review-add", "review_add");
	 add_submenu_page("", "", "", "manage_options", "review-verify", "review_verify");
    add_submenu_page("", "", "", "manage_options", "review-edit", "review_edit");
	
	add_submenu_page("", "", "", "manage_options", "reply-add", "reply_add");
	add_submenu_page("", "", "", "manage_options", "reply-list", "reply_list");
	add_submenu_page("", "", "", "manage_options", "reply-edit", "reply_edit");
	
	
	add_submenu_page("", "", "", "manage_options", "product-edit", "product_edit");
}
add_action("admin_menu", "review_booster_plugin_menus");



function purchase_code() {
    include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . "views/purchase-code.php";
}
function name_list() {
    include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . "views/name-list.php";
}
function review_settings() {
    include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . "views/review-settings.php";
}
function review_booster() {
    include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . "views/review-list.php";
}
function product_list() {
    include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . "views/product-list.php";
}
function review_verify() {
	
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/review-verify.php';
}
function review_add() {
	
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/review-add.php';
}
function product_add() {
	
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/product-add.php';
}

function review_edit() {
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/review-edit.php';
}
function product_edit() {
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/product-edit.php';
}
function reply_edit() {
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/reply-edit.php';
}
function reply_add() {
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/reply-add.php';
}
function reply_list() {
   include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/reply-list.php';
}


function review_booster_include_assets() {
				//styles
				wp_enqueue_style("review-bootstrap", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/bootstrap.css", '');
				wp_enqueue_style("review-datatable", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/jquery.dataTables.min.css", '');
				wp_enqueue_style("review-notifybar", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/jquery.notifyBar.css", '');
				wp_enqueue_style("review-style", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/style.css", '');
				wp_enqueue_style("review-jquery-ui", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/jquery-ui.css", '');
				
				//scripts
				//wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-ui-datepicker', array( 'jquery' ));
				wp_enqueue_script('review-bootstrap.min.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/bootstrap.min.js', '', true);
				wp_enqueue_script('review-validation.min.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/jquery.validate.min.js', '', true);
				wp_enqueue_script('review-datatable.min.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/jquery.dataTables.min.js', '', true);
				wp_enqueue_script('review-jquery.notifyBar.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/jquery.notifyBar.js', '', true);
				wp_enqueue_script('review-script.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/script.js', '', true);
				wp_localize_script("review-script.js", "reviewboosterajaxurl", admin_url("admin-ajax.php"));

}
function review_booster_include_front_assets(){
	wp_enqueue_style("review-font-awesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", '');
	wp_enqueue_style("review-bootstrap", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/bootstrap.css", '');
	wp_enqueue_style("review-custom-style", REVIEW_BOOSTER_PLUGIN_URL . "/assets/css/custom-style.css", '');
	
	//wp_enqueue_script('jquery');
	//wp_enqueue_script('review-validation.min.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/jquery.validate.min.js', '', true);
	wp_enqueue_script('review-bootstrap.min.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/bootstrap.min.js', '', true);
	wp_enqueue_script('review-swal.js', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js', '', true);
	wp_enqueue_script('review-front-script.js', REVIEW_BOOSTER_PLUGIN_URL . '/assets/js/front-script.js', '', true);
	wp_localize_script("review-front-script.js", "reviewboosterajaxurl", admin_url("admin-ajax.php"));

}
add_action("wp_enqueue_scripts", "review_booster_include_front_assets");


add_action("admin_enqueue_scripts", "review_booster_include_assets");


add_action("wp_ajax_reviewboosterlibrary", "review_booster_ajax_handler");
add_action("wp_ajax_nopriv_reviewboosterlibrary", "review_booster_ajax_handler");
function review_booster_ajax_handler() {
    global $wpdb;
    include_once REVIEW_BOOSTER_PLUGIN_DIR_PATH . '/library/review_booster_library.php';
    wp_die();
}


add_shortcode("review_booster", "review_booster_shortcode_part");

function review_booster_shortcode_part($attributes) {
	
	if(!empty($attributes))
		$product_id = $attributes['product']; 
	
	ob_start(); 						//Start Buffer
	include(REVIEW_BOOSTER_PLUGIN_DIR_PATH . 'views/review-front-list.php');
	$file_content = ob_get_contents();    // Reading content from buffer
	ob_end_clean();    					// Clean The Buffer
	return $file_content;
   
}


function randomDateInRange(DateTime $start, DateTime $end) {
    $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
    $randomDate = new DateTime();
    $randomDate->setTimestamp($randomTimestamp);
    return $randomDate;
}

?>