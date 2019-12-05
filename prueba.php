<?
$app_id='959360794434530';
$cadena_encrip = 'https://benjachl-69.github.io/';
//$valor_url = 'http://manivelasst.com/Sistemas/Sistema_Seguros/Compartir_web/';
$url_e = urlencode($cadena_encrip);
$redirect_uri=$url_e;
$scope='user_profile,user_media';
$response_type='code';
$url = 'https://api.instagram.com/oauth/authorize?app_id='.$app_id.'&redirect_uri='.$redirect_uri.'&scope='.$scope.'&response_type='.$response_type;

$app_secret = '25cfe7ac3cb6bf895217f4079d493f50';

if(isset($_GET['code'])){
	$code = $_GET['code'];
	$uri = 'https://api.instagram.com/oauth/access_token'; 
	$data = [
		'app_id'=>$app_id,
		'app_secret'=>$app_secret,
		'code'=>$code,
		'grant_type'=>'authorization_code',
		'redirect_uri'=>$redirect_uri,
	];


	$request = api_request($uri,'POST',$data);
	$response_body = json_decode($request['response_body']);
	echo '<prev>';
	print_r($response_body->access_token);
	echo '<prev>';

	$access_token =$response_body->access_token;
	$user_id =$response_body->user_id;

	$url_ser_media = 'https://graph.instagram.com/me/media?fields=id,caption&access_token='.$access_token;

	$request_user_media = api_request($url_ser_media,'GET','');
	$response_user_media = json_decode($request_user_media['response_body']);

	echo '<prev>';
	print_r($response_user_media->data);
	echo '<prev>';
	echo '<br>';
	echo 'este es el total del data '.count($response_user_media->data);
	echo '<br>';
	$total = count($response_user_media->data);

	echo '<br>';
	for ($i=0; $i < $total; $i++) {
		# Query the Media node
		$url_media_node='';
		$url_media_node ='https://graph.instagram.com/'.$response_user_media->data[$i]->id.'?fields=id,media_type,media_url,username,timestamp&access_token='.$access_token;

		$request_media_node = api_request($url_media_node,'GET','');
		$response_media_node = json_decode($request_media_node['response_body']);

		echo '<prev>';
		print_r($response_media_node->media_url);
		echo '<prev>';
		echo '<br>';
		echo '<img src="'.$response_media_node->media_url.'">';
	}

}


/*
$request = api_request($url,'','');
$response_body = json_decode($request['response_body']);


echo '<prev>';
print_r($response_body);
echo '<prev>';


*/
function api_request($url,$method='GET',$params){
		$headers = array();
		$curl = curl_init();
		#CURLOPT_RETURNTRANSFER: Devuelve la respuesta en forma de string
		#CURLOPT_URL: La url a donde se enviara la petición
		$curl_options = array(
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_URL            => $url,
			CURLOPT_HEADER     	   => 0,
			CURLOPT_NOBODY         =>0,
			CURLOPT_SSL_VERIFYHOST =>0,
			CURLOPT_SSL_VERIFYPEER =>0,

		);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }

		#curl_setopt_array: Nos permite agregar varias configuración la petición
		curl_setopt_array($curl, $curl_options);

		#Guardamos la respuesta en una variable
		$response = curl_exec($curl);
		$http_status = curl_getinfo($curl,CURLINFO_HTTP_CODE);

		#Cerramos la petición
		curl_close($curl);
		#Retornamos la respuesta en un arreglo anidado
		return array('status_code' => $http_status, 'response_body' => $response);



	}
?>
<a href="<? echo $url;?>">Click here to Authenticate </a>
