<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "platform";
$route['404_override'] = '';

$route['play_game'] = 'gate/play_game';
$route['play_game/(:any)'] = 'gate/play_game/$1';

$route['register'] = 'member/register';
$route['cust/CustServiceNew.php'] = 'service';
$route['PayFun/PayMethod.php'] = 'payment';
$route['eWallet/transfer_index.php'] = 'wallet/transfer';
$route['member/login.php'] = 'member/login';
$route['member/forgot.php'] = 'member/forgot_password';
$route['member/regist.php'] = 'member/register';
$route['member/register.php'] = 'member/register';
$route['member/msettings.php'] = 'member/update_member_data';

$route['ImageInfo.php?'] = 'link/s_gif';
$route['Js/ads_banner.js'] = 'link/s';

if ( ! empty($_GET['gate'])) {
	$route['member/login_gate.php'] = 'link/pass/'.urlencode(base_url().'/gate/login_callback/'.$_GET['gate'].'?'.$_SERVER['QUERY_STRING']);
}
$route['member/yahoo/login_proc.php'] = 'link/pass/'.urlencode(base_url().'/gate/login_callback/yahoo?'.$_SERVER['QUERY_STRING']);

$route['guide/t.htm'] = 'payment';



// $route['mycard/payment_confirm.php'] = 'mycard/payment_confirm';
// $route['mycard/mycard_billing.php'] = 'mycard/mycard_inform';
// $route['mycard/err_report.php'] = 'mycard/mycard_trade_err';

//$route['mycard/result.php'] = 'mycard/ingame_callback'; //等改設定

/* End of file routes.php */
/* Location: ./application/config/routes.php */