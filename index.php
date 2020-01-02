<?php
// まずは HTTPステータス 200 を返す
http_response_code(200) ;
echo '200 {}';

// 送られて来たJSONデータを取得
$json_string = file_get_contents('php://input');
$json = json_decode($json_string);
// JSONデータから返信先を取得
$replyToken = $json->events[0]->replyToken;
// JSONデータから送られてきたメッセージを取得
$message = $json->events[0]->message->text;

// HTTPヘッダを設定
$channelToken = 'gGhLUGO+CHo8rfo5gc5kaVUD/kOKFS7CiRmosVspCN1nPcldOdgF9N/hVrIcCtKKvfJgr9CBU0l1/3pTwdjELkTWim56+RI1ARflgWLVff/FqNmgbHEN824aRWrDbrdVA9pmBvISWPw3FFegLNu1ggdB04t89/1O/w1cDnyilFU=';
$headers = [
	'Authorization: Bearer ' . $channelToken,
	'Content-Type: application/json; charset=utf-8',
];

/*---------------- quickReply関数 ------------------*/
function quickreply() {
	return $post;
}

/*------------ Processing of Received Message ------------*/
if (strcmp($message,'クイックリプライ') === 0)  {
	$post = [
		'replyToken' => $replyToken,
		'messages' => [
			[
				'type' => 'text',
				'text' => '選択してください',
				'quickReply' =>  [
				    'items' => [
				    	[
				    	'type' => 'action',
				    	'action' =>  [
				    		'type' => 'message',
				    		'label' => '1番',
				    		'text' => '1番'
				    		]
				   		],
				   		[
				    	'type' => 'action',
				    	'action' =>  [
				    		'type' => 'message',
				    		'label' =>'2番',
				    		'text' => '2番'
				    		]
				   		],
				   		[
				    	'type' => 'action',
				    	'action' =>  [
				    		'type' => 'message',
				    		'label' => '3番',
				    		'text' => '3番'
				    		]
				   		]
				   	]
				]
  			]
		],
	];
}
else if (strcmp($message,'1番') === 0) {
	$post = [
		'replyToken' => $replyToken,
		'messages' => [
			[
				'type' => 'text',
				'text' => '1番ですね',
			],
		],
	];
}
else {
	// POSTデータを設定してJSONにエンコード
	$post = [
		'replyToken' => $replyToken,
		'messages' => [
			[
				'type' => 'text',
				'text' => '「' . $message . '」',
			],
		],
	];
}

$post = json_encode($post);

// HTTPリクエストを設定
$ch = curl_init('https://api.line.me/v2/bot/message/reply');
$options = [
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_HTTPHEADER => $headers,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_BINARYTRANSFER => true,
	CURLOPT_HEADER => true,
	CURLOPT_POSTFIELDS => $post,
];

// 実行
curl_setopt_array($ch, $options);

// エラーチェック
$result = curl_exec($ch);
$errno = curl_errno($ch);
if ($errno) {
	return;
}

// HTTPステータスを取得
$info = curl_getinfo($ch);
$httpStatus = $info['http_code'];

$responseHeaderSize = $info['header_size'];
$body = substr($result, $responseHeaderSize);
/*------------ End Processing of Received Message ------------*/

// 200 だったら OK
echo $httpStatus . ' ' . $body;


?>