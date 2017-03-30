<?php
//var_dump($Comment);
//die();
$json = [
	
	'comment'=> $Comment,
	'isConnected' => $isConnected,
	'result' => $result,
	];

if ('true' === $isConnected){
	
	$json['linkUpdate'] = $linkUpdate;
	$json['linkDelete'] = $linkDelete;
}
return $json;

?>