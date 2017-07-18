<?
include_once('../../../wp-config.php');


if($_POST['godaddy']==1)
update_option('godaddy_host', true);
else
update_option('godaddy_host', false);

$results = curl_data($_POST, "http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction?");

$tags = unserialize($results);


if(sizeof($tags[ResultSet][Result])>0)
{
	foreach ($tags[ResultSet][Result] as $tag)
	{
		//$out .= "<a href='javascript:;' onClick='add_tag(\"$tag\")'>$tag</a> ";
		$out .= "$tag, ";
	}
	$out = rtrim($out, ", ");
}
else
$out = "no tags found";

echo $out;

function curl_data($post_data, $url)
{
$appid = str_replace(".", "_", $_SERVER['HTTP_HOST'])."_autotagger";
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $url );
	curl_setopt($curl,CURLOPT_VERBOSE,1);
	if(get_option('godaddy_host'))
	{
	curl_setopt ($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	//"http://64.202.165.130:3128"
	curl_setopt ($curl, CURLOPT_PROXY,"http://proxy.shr.secureserver.net:3128");
	}
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false); 
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 30 );
	curl_setopt( $curl, CURLOPT_TIMEOUT,500 );
	curl_setopt ($curl, CURLOPT_POSTFIELDS, "appid=".$appid."&output=php&context=".strip_tags($_POST['context']));
	curl_setopt( $curl, CURLOPT_POST, 1 );
	$res = curl_exec( $curl );
	curl_close( $curl );
	
	return $res;
}
?>