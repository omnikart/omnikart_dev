<?php
function dd($data, $die = true) {
	echo '<pre>';
	print_r ( $data );
	echo '</pre>';
	
	if ($die) {
		die ();
	}
}
function words_limit($str, $num, $append_str = '') {
	$num = ( int ) $num;
	$str = strip_tags ( $str );
	$words = preg_split ( '/[\s]+/', $str, - 1, PREG_SPLIT_OFFSET_CAPTURE );
	// $words = preg_split('/(\p{L}+)/u', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
	if (isset ( $words [$num] [1] )) {
		$str = substr ( $str, 0, $words [$num] [1] ) . $append_str;
	}
	unset ( $words, $num );
	return trim ( $str );
}
function setting($data) {
	$setting = array ();
	foreach ( $data as $key => $value ) {
		$setting [$value ['setting_name']] = $value ['setting_value'];
	}
	return $setting;
}
function author($id, $info) {
	global $registry;
	$db = $registry->get ( 'db' );
	$user_query = $db->query ( "SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . ( int ) $id . "'" );
	if ($user_query->num_rows) {
		return ucfirst ( $user_query->row [$info] );
	} else {
		return 'Unknown Author';
	}
}
function getId() {
	global $registry;
	$load = $registry->get ( 'load' );
	$load->library ( 'user' );
	$user = new user ( $registry );
	return $user->getId ();
}
function month_name($month) {
	switch ($month) {
		case '01' :
			return 'January';
			break;
		case '02' :
			return 'February';
			break;
		case '03' :
			return 'March';
			break;
		case '04' :
			return 'April';
			break;
		case '05' :
			return 'May';
			break;
		case '06' :
			return 'June';
			break;
		case '07' :
			return 'July';
			break;
		case '08' :
			return 'August';
			break;
		case '09' :
			return 'September';
			break;
		case '10' :
			return 'October';
			break;
		case '11' :
			return 'November';
			break;
		case '12' :
			return 'December';
			break;
		
		default :
			return 'Unknown!';
			break;
	}
}
function form_error() {
	global $registry;
	$session = $registry->get ( 'session' );
	if (isset ( $session->data ['form_error'] )) :
		foreach ( $session->data ['form_error'] as $key => $value ) :
			?>
<div class="alert alert-danger"><?php echo $value; ?></div>
<?php
		
endforeach
		;
	 
	endif;
	$session->data ['form_error'] = NULL;
}
function form_msg() {
	global $registry;
	$session = $registry->get ( 'session' );
	if (isset ( $session->data ['form_msg'] )) :
		foreach ( $session->data ['form_msg'] as $key => $value ) :
			?>
<div class="alert alert-success"><?php echo $value; ?></div>
<?php
		
endforeach
		;
	 
	endif;
	$session->data ['form_msg'] = NULL;
}
