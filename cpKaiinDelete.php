<?php
/**
 * [ 概要 ]
 * スパイラルのAPIを使ったサンプルプログラムです。
 *
 * [ サンプルで行う事 ]
 * - データベースへ１件登録する
 *
 * UTF8で保存してください。
 */
/**
 * [サンプルを動かすための準備]
 *
 * 1. PHPにcurlライブラリが組み込まれている必要があります。
 *    参考：http://www.php.net/manual/ja/intro.curl.php
 *
 * 2. スパイラルの操作画面の「ＤＢグループ」「通常ＤＢ管理」で、以下のような構成でＤＢを発行します。
 * 
 *    DBタイトル：
 *             member
 *
 *    フィールド：
 *             No1 フィールド名　　：登録日時
 *                 フィールドタイプ：登録日時
 *                 差替えキーワード：registDate
 *
 *             No2 フィールド名　　：メールアドレス
 *                 フィールドタイプ：メールアドレス（大・小文字を無視）
 *                 差替えキーワード：email
 *
 *             No3 フィールド名　　：パスワード
 *                 フィールドタイプ：メッセージダイジェスト（SHA256）
 *                 差替えキーワード：pass
 *
 *             No4 フィールド名　　：氏名
 *                 フィールドタイプ：テキストフィールド(64 bytes)
 *                 差替えキーワード：name
 *
 */
 
 
	//POSTパラメータを取り出す
	$json_string = file_get_contents('php://input'); 
	$obj = json_decode($json_string,TRUE);
  
	//var_dump($obj);
	//echo('fffffff1');
  
	if(isset($obj['KaiinAccountId'])) {
		$KaiinAccountId = $obj['KaiinAccountId'];
	}
  
	if(isset($obj['EmailAddress'])) {
		$EmailAddress = $obj['EmailAddress'];
	} 
	
	if(isset($obj['SfdcSid'])) {
		$SfdcSid = $obj['SfdcSid'];
	}
	 
	// ロケータのURL (変更の必要はありません)
	$locator = "http://www.pi-pe.co.jp/api/locator";
	
	// スパイラルの操作画面で発行したトークンを設定します。
	$TOKEN  = "00011KG1UydKb76cb9624c78d967eab376e9259dc167a79b6407";
	$SECRET = "b5a8e45ebc738952312d6d8c1ffd49fa48635d21";
	
	// API用のHTTPヘッダ
	$api_headers = array(
		"X-SPIRAL-API: locator/apiserver/request",
		"Content-Type: application/json; charset=UTF-8",
		);
		
	// 送信するJSONデータを作成
	$parameters = array();
	$parameters["spiral_api_token"] = $TOKEN;  //トークン
	
	// 送信用のJSONデータを作成します。
	$json = json_encode($parameters);
	
	// curlライブラリを使って送信します。
	$curl = curl_init($locator);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST          , true);
	curl_setopt($curl, CURLOPT_POSTFIELDS    , $json);
	curl_setopt($curl, CURLOPT_HTTPHEADER    , $api_headers);
	curl_exec($curl);
	
	// エラーがあればエラー内容を表示
	if (curl_errno($curl)) echo curl_error($curl);
	$response = curl_multi_getcontent($curl);
	curl_close($curl);
	$response_json = json_decode($response , true);
	
	// サービス用のURL
	$APIURL = $response_json['location'];
	
	// -----------------------------------------------------------------------------
	// 削除
	// -----------------------------------------------------------------------------
	// API用のHTTPヘッダ
	$api_headers = array(
		"X-SPIRAL-API: database/delete/request",
		"Content-Type: application/json; charset=UTF-8",
	);
	// リクエストデータを作成
	$parameters = array();
	$parameters["spiral_api_token"] = $TOKEN;       //トークン
	$parameters["db_title"]         = "KaiinTest";      //DBのタイトル
	$parameters["passkey"]          = time();       //エポック秒

	// 検索条件
	$parameters["search_condition"] = array(
		array("name" => "SfdcSid", "value" => $SfdcSid, "operator" => "LIKE"),
	);
	
	// 署名を付けます
	$key = $parameters["spiral_api_token"] . "&" . $parameters["passkey"];
	$parameters["signature"] = hash_hmac('sha1', $key, $SECRET, false);
	
	// JSON形式にエンコードします
	$json = json_encode($parameters);
	echo "===> database/delete\n";
	
	// curlライブラリを使って送信します。
	$curl = curl_init($APIURL);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST          , true);
	curl_setopt($curl, CURLOPT_POSTFIELDS    , $json);
	curl_setopt($curl, CURLOPT_HTTPHEADER    , $api_headers);
	curl_exec($curl);
	
	// エラーがあればエラー内容を表示
	if (curl_errno($curl)) echo curl_error($curl);
	
	$response = curl_multi_getcontent($curl);
	curl_close($curl);
	
	// 画面に表示
	// 配列にしたい時は json_decode($response, true); とします。
	//echo "$response\n\n";
	//print_r(json_decode($response, true));
?>