<?php
/**
 * Plugin Name: M-CIN Landing
 * Description: เพิ่ม Page Template "M-CIN Landing" (หน้า landing ยาสเปรย์ เอ็ม-ซิน) ให้เลือกได้ในหน้า Page
 * Version:     1.0.2
 */

defined( 'ABSPATH' ) || exit;

define( 'MCN_TEMPLATE', 'mcn-landing' );

function mcn_is_landing() {
	return is_page() && get_page_template_slug() === MCN_TEMPLATE;
}

// ให้ "M-CIN Landing" โผล่ในตัวเลือก Template ของหน้า Page
add_filter( 'theme_page_templates', function ( $templates ) {
	$templates[ MCN_TEMPLATE ] = 'M-CIN Landing';
	return $templates;
} );

// หน้าที่เลือก template นี้ ใช้ไฟล์ของปลั๊กอินแทนไฟล์ของธีม
add_filter( 'template_include', function ( $template ) {
	return mcn_is_landing() ? __DIR__ . '/page-mcn.php' : $template;
}, 99 );

// โหลดฟอนต์และ CSS ของ M-CIN เฉพาะหน้านี้
add_action( 'wp_enqueue_scripts', function () {
	if ( ! mcn_is_landing() ) {
		return;
	}
	wp_enqueue_style(
		'mcn-fira',
		'https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'mcn',
		plugins_url( 'mcn.css', __FILE__ ),
		array( 'mcn-fira' ),
		filemtime( __DIR__ . '/mcn.css' )
	);
}, 20 );

// เอา CSS ของธีมและปลั๊กอินอื่นออกจากหน้านี้ กันไปทับดีไซน์
// ทำตอนกำลังจะพิมพ์ CSS (ทั้งใน head และ footer) เพราะบางธีมเช่น Healer enqueue ช้ากว่า wp_enqueue_scripts
function mcn_dequeue_other_styles() {
	if ( ! mcn_is_landing() ) {
		return;
	}
	$keep = array( 'mcn', 'mcn-fira', 'admin-bar', 'dashicons' );
	foreach ( wp_styles()->queue as $handle ) {
		if ( ! in_array( $handle, $keep, true ) ) {
			wp_dequeue_style( $handle );
		}
	}
}
add_action( 'wp_print_styles', 'mcn_dequeue_other_styles', PHP_INT_MAX );
add_action( 'wp_footer', 'mcn_dequeue_other_styles', 1 );

// Additional CSS ของ Customizer เป็นของทั้งเว็บ ไม่ต้องใช้ในหน้านี้
add_action( 'wp', function () {
	if ( mcn_is_landing() ) {
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
	}
} );

// WP ใส่ class "page" ให้ <body> ซึ่งชนกับ .page ใน mcn.css (ทำให้ทั้งหน้ากว้าง 760px พื้นขาว)
add_filter( 'body_class', function ( $classes ) {
	if ( mcn_is_landing() ) {
		$classes   = array_diff( $classes, array( 'page' ) );
		$classes[] = 'mcn-landing';
	}
	return $classes;
} );

// หน้านี้มีปุ่ม LINE/Facebook ของตัวเองแล้ว ซ่อนปุ่มติดต่อและปุ่มเลื่อนขึ้นที่เว็บใส่ไว้ทุกหน้า
add_action( 'wp_enqueue_scripts', function () {
	if ( mcn_is_landing() ) {
		wp_add_inline_style( 'mcn', 'body.mcn-landing .contact-widget, body.mcn-landing .trx_addons_scroll_to_top { display: none !important; }' );
	}
}, 21 );

add_filter( 'wp_resource_hints', function ( $urls, $type ) {
	if ( 'preconnect' === $type && mcn_is_landing() ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );
