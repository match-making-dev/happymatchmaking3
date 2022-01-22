<?php

// スパイラルの操作画面で発行したトークンを設定します。
	$TOKEN  = "00011KG1UydKb76cb9624c78d967eab376e9259dc167a79b6407";
	$SECRET = "b5a8e45ebc738952312d6d8c1ffd49fa48635d21";


echo 'name:' . $_POST['name'] . "\n\n";
echo 'pass:' . $_POST['pass'] . "\n\n";



// -----------------------------------------------------------------------------
// ロケータ
// -----------------------------------------------------------------------------
// ロケータのURL (変更の必要はありません)
$locator = "http://www.pi-pe.co.jp/api/locator";

// API用のHTTPヘッダ
$api_headers = array(
	"X-SPIRAL-API: locator/apiserver/request",
	"Content-Type: application/json; charset=UTF-8",
	);

// リクエストデータを作成
$parameters = array();
$parameters["spiral_api_token"] = $TOKEN;  //トークン

// JSON形式にエンコードします。
$json = json_encode($parameters);

// curlライブラリを使って送信します。
$curl = curl_init($locator);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST          , true);
curl_setopt($curl, CURLOPT_POSTFIELDS    , $json);
curl_setopt($curl, CURLOPT_HTTPHEADER    , $api_headers);
curl_exec($curl);

// エラーがあればエラー内容を表示
if (curl_errno($curl)) {
  echo curl_error($curl);
  exit(-1);
}

$response = curl_multi_getcontent($curl);
curl_close($curl);
$response_json = json_decode($response , true);
// サービス用のURL
$APIURL = $response_json['location'];



// -----------------------------------------------------------------------------
// ログイン
// -----------------------------------------------------------------------------
// API用のHTTPヘッダ
$api_headers = array(
	"X-SPIRAL-API: area/login/request",
	"Content-Type: application/json; charset=UTF-8",
	);

// リクエストデータを作成
$parameters = array();
$parameters["spiral_api_token"] = $TOKEN;       //トークン
$parameters["passkey"]          = time();       //エポック秒
//$parameters["my_area_title"]    = "MyArea1";
$parameters["my_area_title"]    = "MatchMakingTest";
$parameters["id"]               = $_POST['name'];
$parameters["password"]         = $_POST['pass'];
$parameters["url_type"]         = "2";

// 署名を付けます
$key = $parameters["spiral_api_token"] . "&" . $parameters["passkey"];
$parameters["signature"] = hash_hmac('sha1', $key, $SECRET, false);

// JSON形式にエンコードします。
$json = json_encode($parameters);

// curlライブラリを使って送信します。
$curl = curl_init($APIURL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST          , true);
curl_setopt($curl, CURLOPT_POSTFIELDS    , $json);
curl_setopt($curl, CURLOPT_HTTPHEADER    , $api_headers);
curl_exec($curl);

// エラーがあればエラー内容を表示
if (curl_errno($curl)) {
  echo curl_error($curl);
  exit(-1);
}

$response = curl_multi_getcontent($curl);
curl_close($curl);
$response_json = json_decode($response , true);
// セッションID
$jsessionid = $response_json['jsessionid'];

$sig = hash_hmac('sha1', $parameters["id"] . "&" . $jsessionid, $SECRET, false);
//header('Location: https://strawberry-pie58216-developer-edition.ap2.force.com/?'
header('Location: https://match-making-dev-developer-edition.ap2.force.com/?'
    . 'spuid=' . $parameters["id"]
    . '&spsid=' . $jsessionid
    . '&sig=' . $sig
    );


