<?php

// �X�p�C�����̑����ʂŔ��s�����g�[�N����ݒ肵�܂��B
	$TOKEN  = "00011KG1UydKb76cb9624c78d967eab376e9259dc167a79b6407";
	$SECRET = "b5a8e45ebc738952312d6d8c1ffd49fa48635d21";

echo 'name:' . $_POST['name'] . "\n\n";
echo 'pass:' . $_POST['pass'] . "\n\n";



// -----------------------------------------------------------------------------
// ���P�[�^
// -----------------------------------------------------------------------------
// ���P�[�^��URL (�ύX�̕K�v�͂���܂���)
$locator = "http://www.pi-pe.co.jp/api/locator";

// API�p��HTTP�w�b�_
$api_headers = array(
	"X-SPIRAL-API: locator/apiserver/request",
	"Content-Type: application/json; charset=UTF-8",
	);

// ���N�G�X�g�f�[�^���쐬
$parameters = array();
$parameters["spiral_api_token"] = $TOKEN;  //�g�[�N��

// JSON�`���ɃG���R�[�h���܂��B
$json = json_encode($parameters);

// curl���C�u�������g���đ��M���܂��B
$curl = curl_init($locator);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST          , true);
curl_setopt($curl, CURLOPT_POSTFIELDS    , $json);
curl_setopt($curl, CURLOPT_HTTPHEADER    , $api_headers);
curl_exec($curl);

// �G���[������΃G���[���e��\��
if (curl_errno($curl)) {
  echo curl_error($curl);
  exit(-1);
}

$response = curl_multi_getcontent($curl);
curl_close($curl);
$response_json = json_decode($response , true);
// �T�[�r�X�p��URL
$APIURL = $response_json['location'];



// -----------------------------------------------------------------------------
// ���O�C��
// -----------------------------------------------------------------------------
// API�p��HTTP�w�b�_
$api_headers = array(
	"X-SPIRAL-API: area/login/request",
	"Content-Type: application/json; charset=UTF-8",
	);

// ���N�G�X�g�f�[�^���쐬
$parameters = array();
$parameters["spiral_api_token"] = $TOKEN;       //�g�[�N��
$parameters["passkey"]          = time();       //�G�|�b�N�b
//$parameters["my_area_title"]    = "MyArea1";
$parameters["my_area_title"]    = "MatchMakingTest";
//$parameters["my_area_title"]    = "MyArea2";
$parameters["id"]               = $_POST['name'];
//$parameters["id"]               = $_POST['email'];
$parameters["password"]         = $_POST['pass'];
//$parameters["password"]         = $_POST['password'];
$parameters["url_type"]         = "2";

// ������t���܂�
$key = $parameters["spiral_api_token"] . "&" . $parameters["passkey"];
$parameters["signature"] = hash_hmac('sha1', $key, $SECRET, false);

// JSON�`���ɃG���R�[�h���܂��B
$json = json_encode($parameters);

// curl���C�u�������g���đ��M���܂��B
$curl = curl_init($APIURL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST          , true);
curl_setopt($curl, CURLOPT_POSTFIELDS    , $json);
curl_setopt($curl, CURLOPT_HTTPHEADER    , $api_headers);
curl_exec($curl);

// �G���[������΃G���[���e��\��
if (curl_errno($curl)) {
  echo curl_error($curl);
  exit(-1);
}

$response = curl_multi_getcontent($curl);
curl_close($curl);
$response_json = json_decode($response , true);

// �Z�b�V����ID
$jsessionid = $response_json['jsessionid'];

    echo 'code:' . $response_json['code'] . "\n\n";
    echo 'jsessionid:' . $response_json['jsessionid'] . "\n\n"; 
    echo 'id:' . $parameters["id"] . "\n\n";
    echo 'passkey:' . $parameters["passkey"] . "\n\n";
        
// �G���[������΃G���[���e��\��
if ($response_json['code']!=0) {
  /*echo $response_json['jsessionid'];
  header('Location: http://www.yahoo.co.jp/'
    );*/


  exit(-1);
}


$sig = hash_hmac('sha1', $parameters["id"] . "&" . $jsessionid, $SECRET, false);
//header('Location: https://strawberry-pie58216-developer-edition.ap2.force.com/?'
header('Location: https://match-making-dev-developer-edition.ap6.force.com/?'
    . 'spuid=' . $parameters["id"]
    . '&spsid=' . $jsessionid
    . '&sig=' . $sig
    . '&alc=' . $response_json['auto_login_cookie']
    );


