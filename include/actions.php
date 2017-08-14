<?php
/* Resulting actions */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function hook_inbox_photo_css() {
	$css = get_option( 'inbox_photo_button_css' );
	$output="<style>$css</style>";
	echo $output;
}

function shortcode_inbox_photo_button_func( $atts ) {
	$slug = get_option( 'inbox_photo_slug' );
	if ( get_option( 'inbox_photo_button_text' ) )
		$text = get_option( 'inbox_photo_button_text' );
  else
		$text = __('Order my product','inboxphoto');
	$a = shortcode_atts( array(
    'category' => '',
    'product' => '',
    'text' => ''
    ), $atts );
	if ( $a['text'] ) $text = $a['text'];
	$button = '<a href="https://inbox.photo/shop/'.$slug.'/app/'.$a['category'].'/';
	if ( $a['product'] ) $button .=  $a['product'].'/';	$button .= '"';	if ( get_option( 'inbox_photo_link_google_analytics' ) ) $button .= " onclick=\"ga('send', 'event', 'inbox.photo', '".$a['category']."', '".$a['product']."', 'Button');\"";	if ( get_option( 'inbox_photo_link_google_tag_manager' ) ) $button .= " onclick=\"dataLayer.push({'event':'inbox.photo','category':'".$a['category']."','element':'".$a['product']."'});\"";
	$button .= ' rel="nofollow" target="_blank"><button class="inboxphotobutton">'.$text.'</button></a>';
	return $button;
}

function shortcode_inbox_photo_snippet_func( $atts ) {
	$slug = get_option( 'inbox_photo_slug' );
	$currency = get_option( 'inbox_photo_currency' );
	if ( get_option( 'inbox_photo_button_text' ) )
		$text = get_option( 'inbox_photo_button_text' );
  else
		$text = __('Order my product','inboxphoto');
  $a = shortcode_atts( array(
    'category' => '',
    'product' => '',
    'text' => ''
  ), $atts );
	if ( $a['text'] ) $text = $a['text'];
	$order_url = 'https://inbox.photo/shop/'.$slug.'/app/'.$a['category'].'/';
	if ( $a['product'] ) {
		$order_url .=  $a['product'].'/';
		$url = 'https://'.$slug.'.inbox.photo/api/'.$a['category'].'/'.$a['product'].'/';
		$array = json_decode(file_get_contents($url));
		$type = $array->type;
		switch ($type) {
			case 'prints':
			case 'large_prints':
				$snippet = '<p>'. __('Product not supported.','inboxphoto') .'</p>';
				break;
			case 'collages':
			case 'canvas_prints':
			case 'photo_gifts':
			case 'photo_books':
			case 'cards':
			case 'calendars':
				$name = $array->product->name;
				$price = $array->product->unit_price;
				$snippet = '<div itemscope itemtype="http://schema.org/Product">';
				if ( ! empty ( $array->product->custom_image ) ) $snippet .= '<a href="'. $order_url .'"><img itemprop="image" src="'. $array->product->custom_image .'" alt="'. $name .'" /></a>';
				$snippet .= '<p itemprop="name">'.$name.'</p>';
				if ( $type = 'calendars' ) $snippet .= '<meta itemprop="category" content="calendar" />';
				$snippet .= '<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">'. __('Price:','inboxphoto') .' <span itemprop="price" content="'. $price .'">'. $price .'</span> <span itemprop="priceCurrency" content="'. $currency .'">'. $currency .'</span><meta itemprop="availability" href="http://schema.org/InStock" content="In stock" /></div>';
				$snippet .= '<a href="'. $order_url .'"><button class="inboxphotobutton">'.$text.'</button></a>';
				break;
		}
	}
	else {
		$snippet = '<p>'. __('Category snippet not supported.','inboxphoto') .'</p>';
	}
	return $snippet;
}
