<?
$config['ldap_connect'] = array(
    "host" => "61.220.44.200",
    "port" => 10389,
    "domain" => "@longe.tw",
    "dc1" => "longe",
    "dc2" => "tw"
);

$config['ldap_roles'] = array(
    "客服人員" => "cs_master",
    "技術人員" => "admin",
    "營運主管" => "pm",
    "營運人員" => "op",
    "產品經理" => "pm",
    "美術人員" => "op",
    "行銷人員" => "mo",
	"總務人員" => "acct",
	"行政主管" => "acct",
	"行政人員" => "acct",
	"財務人員" => "acct"
);
?>