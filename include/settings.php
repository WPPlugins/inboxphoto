<?php
/* Options management */


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function inbox_photo_options_page() {
	if (  get_option( 'inbox_photo_slug' ) && get_option( 'inbox_photo_api_token' ) ) {
		$jsonurl = 'https://'. get_option( 'inbox_photo_slug' ) .'.inbox.photo/api/'. get_option( 'inbox_photo_api_token' ) .'/orders/';
		$data = json_decode(file_get_contents($jsonurl));
		$count = 0;
		foreach($data as $value) {
			if($value->status == "NEW") $count++;
		}
		echo $count;
	}
	if ( $count > 0 ) {
		$menu_entry = 'inbox.photo <span class="update-plugins count-1"><span class="update-count">'.$count.'</span></span>';
	}
	else {
		$menu_entry = 'inbox.photo';
	}
  add_options_page( 'inbox.photo' , $menu_entry , 'manage_options' , 'inbox_photo' , 'inbox_photo_options_page_html' );
}

function inbox_photo_options_page_html() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'inboxphoto' ) );
	}
	echo '<style>.inbox-warning {background-color:yellow;padding:5px;text-align:center;}</style>';
	echo '<div class="wrap">';
	echo '<h1>'.__('Your inbox.photo environment','inboxphoto').'</h1>';
	$active_tab = 'general-options';	if(isset($_GET['tab'])) {		if($_GET['tab'] == 'general-options') {			$active_tab = 'general-options';		}		else {			$active_tab = 'documentation';		}	}	if($active_tab == 'general-options') {				echo '<h2 class="nav-tab-wrapper"><a href="?page=inbox_photo&tab=general-options" class="nav-tab nav-tab-active">'. __('General options', 'inboxphoto' ).'</a> <a href="?page=inbox_photo&tab=documentation" class="nav-tab nav-tab">'. __('Documentation', 'inboxphoto' ).'</a></h2>';	if (  ! get_option( 'inbox_photo_slug' ) ) {
		echo '<p class="inbox-warning"><span class="dashicons dashicons-warning"></span> <strong>'. __( 'You need to be connected to a valid inbox.photo account.', 'inboxphoto' ).'</strong></p>';
	}
	echo '<h2>'.__('Settings','inboxphoto').'</h2>';
	if ( get_option( 'inbox_photo_slug' ) ) {
		$url = 'https://'.get_option( 'inbox_photo_slug').'.inbox.photo/api/shop-info/';
		$array = json_decode(file_get_contents($url));
		if ( $array ) $currency = $array->iso_currency;
	}
	echo '<form method="post" action="options.php">';
	settings_fields( 'inbox-photo' );
	echo '<table>';
	if ( ! ( $array ) ) {
		echo '<tr><td colspan="2"><strong>'.__('Your shop slug is missing or incorrect.','inboxphoto').'</strong></td></tr>';
	}
	echo '<tr>';
	echo '<td><label for="inbox_photo_slug">'.__('Your inbox.photo slug (without .inbox.photo)','inboxphoto').'</td>';
	echo '<td><input name="inbox_photo_slug" type="text" id="inbox_photo_slug" value="'. get_option( 'inbox_photo_slug' ) .'" />.inbox.photo</label> (<span id="inboxphotourlcheck"><a href="#" onclick="CheckInboxPhotoShopURL()">'.__('check','inboxphoto').'</a></span>)</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>';
	echo '<label for="inbox_photo_api_token">'.__('Your inbox.photo API token','inboxphoto').'</td>';
	echo '<td><input name="inbox_photo_api_token" type="text" id="inbox_photo_api_token" value="'. get_option( 'inbox_photo_api_token' ) .'" /></label></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><label for="inbox_photo_button_text">'.__('Your inbox.photo button default text','inboxphoto').'</td>';
	echo '<td><input name="inbox_photo_button_text" type="text" id="inbox_photo_button_text" value="'. get_option( 'inbox_photo_button_text' ) .'" /></label></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><label for="inbox_photo_button_css">'.__('Your inbox.photo button CSS','inboxphoto').'</td>';
	echo '<td><textarea name="inbox_photo_button_css" cols="80" id="inbox_photo_button_css">'. get_option( 'inbox_photo_button_css' ) .'</textarea></label></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><label for="inbox_photo_currency">'.__('Your currency','inboxphoto').'</td>';
	echo '<td><input name="inbox_photo_currency" type="hidden" id="inbox_photo_currency" value="'. $currency .'" /><span id="inbox_photo_currency_comment">'. get_option( 'inbox_photo_currency' ) .'</span></label></td>';
	echo '</tr>';		echo '<tr><td colspan="2"><h3>'.__('Click tracking','inboxphoto').'</h3></td></tr>';	echo '<tr><td><label for="inbox_photo_link_google_analytics">'.__('Track clicks with Google Analytics','inboxphoto').'</td><td><input name="inbox_photo_link_google_analytics" type="checkbox" id="inbox_photo_link_google_analytics" value="1" ' . checked( 1, get_option( 'inbox_photo_link_google_analytics' ), false ) . '" /></label></td></tr>';	echo '<tr><td><label for="inbox_photo_link_google_tag_manager">'.__('Track clicks with Google Tag Manager','inboxphoto').'</td><td><input name="inbox_photo_link_google_tag_manager" type="checkbox" id="inbox_photo_link_google_tag_manager" value="1" ' . checked( 1, get_option( 'inbox_photo_link_google_tag_manager' ), false ) . '" /></label></td></tr>';
	echo '</table>';		submit_button();	echo '</form>';	if (  get_option( 'inbox_photo_slug' ) && get_option( 'inbox_photo_api_token' ) ) {		$jsonurl = 'https://'. get_option( 'inbox_photo_slug' ) .'.inbox.photo/api/'. get_option( 'inbox_photo_api_token' ) .'/orders/';		$data = json_decode(file_get_contents($jsonurl));		$count = 0;		$new = 0;		$opened = 0;		$downloaded = 0;		foreach($data as $value) {			$count++;			if($value->status == "NEW") $new++;			if($value->status == "OPENED") $opened++;			if($value->status == "DOWNLOADED") $downloaded++;		}		echo '<p>'.__('Currently on your shop:','inboxphoto').' '.$count.' '.__('orders:','inboxphoto').' '.$new.' '.__('new orders','inboxphoto').', '.$opened.' '.__('opened orders','inboxphoto').', '.$downloaded.' '.__('downloaded orders','inboxphoto').'.</p>';	}	if ( $array ) {		echo '<a href="https://inbox.photo/dashboard/"><button>'.__('Connect to dashboard','inboxphoto').'</button></a>';	}		}	if($active_tab == 'documentation') {		echo '<h2 class="nav-tab-wrapper"><a href="?page=inbox_photo&tab=general-options" class="nav-tab">'. __('General options', 'inboxphoto' ).'</a> <a href="?page=inbox_photo&tab=documentation" class="nav-tab nav-tab-active">'. __('Documentation', 'inboxphoto' ).'</a></h2>';		echo '<p>'.__('This plug-in is designed to help you link your Wordpress web site to your inbox.photo based web shop by providing shortcodes for your ordering pages.','inboxphoto').'</p>';		echo '<p>'.__('Examples.','inboxphoto').'</p>';		echo '<ul>';		echo '<li>'.__('Short code usage example for product category button: <code>[inboxphoto_button category="prints"]</code>','inboxphoto').'</li>';		echo '<li>'.__('Short code usage example for product category button with custom text: <code>[inboxphoto_button category="prints" text="text"]</code>','inboxphoto').'</li>';		echo '<li>'.__('Short code usage example for product: <code>[inboxphoto_button category="prints" product="1234"]</code>','inboxphoto').'</li>';		echo '<li>'.__('Short code usage example for product snippet: <code>[inboxphoto_snippet category="calendars" product="5678"]</code>','inboxphoto').'</li>';		echo '<li>'.__('CSS example: <code>.inboxphotobutton { background-color: lightgrey; border-radius: 5px; font-size: 85%; font-weight: bold; color: black; }</code>','inboxphoto').'</li>';		echo '</ul>';		echo '<p>'.__('A product sidebar widget is also available. Visit the <a href="widgets.php">widget section</a>.','inboxphoto').'</p>';			}

	echo '</div>';

	?>
	<script>
	function CheckInboxPhotoShopURL() {
		var InboxPhotoShop = document.getElementById("inbox_photo_slug");
		var InboxPhotoShopJSONURL = 'https://'+InboxPhotoShop.value+'.inbox.photo/api/shop-info/';
		console.log (InboxPhotoShopJSONURL);
		var InboxPhotoShopJSON = jQuery.getJSON(InboxPhotoShopJSONURL, function(data) {
		  document.getElementById("inboxphotourlcheck").innerHTML='<?php echo ( __('Success. Your shop name is:','inboxphoto') ) ?> '+data.name;
		  console.log(data);
		  var currency = data.iso_currency;
		  document.getElementById("inbox_photo_currency").value=data.iso_currency;
		  document.getElementById("inbox_photo_currency").innerHTML=data.iso_currency;
		  document.getElementById("inbox_photo_currency_comment").innerHTML=data.iso_currency;
		})
		.done(function() {
		})
		.fail(function() {
			alert ('<?php echo ( __('Error: the inbox.photo slug seems incorrect. Please fix it.','inboxphoto') ) ?>');
		})
		.always(function() {
		});
	}
	</script>
	<?php
}

function register_inbox_photo_settings() {
	register_setting( 'inbox-photo', 'inbox_photo_slug' );
	register_setting( 'inbox-photo', 'inbox_photo_api_token' );
	register_setting( 'inbox-photo', 'inbox_photo_button_css' );
	register_setting( 'inbox-photo', 'inbox_photo_button_text' );
	register_setting( 'inbox-photo', 'inbox_photo_currency' );	register_setting( 'inbox-photo', 'inbox_photo_link_google_analytics' );	register_setting( 'inbox-photo', 'inbox_photo_link_google_tag_manager' );
}
