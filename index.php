<?php

error_reporting(E_ERROR | E_PARSE);
// 変数の初期化
$clean = array();
$error = array();
// サニタイズ
if( !empty($_POST) ) {
	foreach( $_POST as $key => $value ) {
		$clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
	} 
}
// 文字成型
$clean['tel'] = str_replace(array('-', 'ー', '−', '―', '‐'), '', $clean['tel']);
$clean['tel'] = str_replace(array(" ", "　"), "", $clean['tel']);
$clean['tel'] = mb_convert_kana($clean['tel'], "n");
$clean['email'] = str_replace(array(" ", "　"), "", $clean['email']);
$clean['email'] = mb_convert_kana($clean['email'], "askhc");

if( !empty($clean['btn_confirm'])) {
	$error = validation($clean);

	if( empty($error) ) {

		$page_flag = 1;
		// セッションの書き込み
		session_start();
		$_SESSION['page'] = true;		
	}

} elseif( !empty($clean['btn_submit']) ) {
	session_start();
	if( !empty($_SESSION['page']) && $_SESSION['page'] === true ) {
		// セッションの削除
		unset($_SESSION['page']);
		$page_flag = 2;
		// 変数とタイムゾーンを初期化
		$header = null;
		$body = null;
		$admin_body = null;
		$auto_reply_subject = null;
		$auto_reply_text = null;
		$admin_reply_subject = null;
		$admin_reply_text = null;
		date_default_timezone_set('Asia/Tokyo');
		
		//日本語の使用宣言
		mb_language("ja");
		mb_internal_encoding("UTF-8");
	
		$header = "MIME-Version: 1.0\n";
		$header = "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
		$header .= "From: hipetest@bpoc.co.jp\n";
		$header .= "Reply-To: hipetest@bpoc.co.jp\n";
	
		// 件名を設定
		$auto_reply_subject = 'neo-career-rpo';
	
		// 本文を設定
		$auto_reply_text =  $clean['f_name'] . " " . $clean['l_name'] . "様 \n\n";
		$auto_reply_text .= "この度は、当サイトよりお問い合わせを頂きまして、誠にありがとうございます。\n 3営業日以内に担当より折り返しご連絡をいたしますので、今しばらくおまちくださいませ。\n\n";

		$auto_reply_text .= "お問合せ内容: " . $_POST['purpose'][0] . " / " . $_POST['purpose'][1] . " / " . $_POST['purpose'][2] . " / " . $_POST['purpose'][3] . " / " . "\n";
		$auto_reply_text .= "会社名: " . $clean['company_name'] . "\n";
		$auto_reply_text .= "氏名: " . $clean['f_name'] . " " . $clean['l_name'] . "\n";
		$auto_reply_text .= "メールアドレス: " . $clean['email'] . "\n";
		$auto_reply_text .= "電話番号: " . $clean['tel1'] ."-". $clean['tel2'] ."-". $clean['tel3'] ."\n";
        $auto_reply_text .= "リモート勤務中の連絡先: " . $clean['tel4'] ."-". $clean['tel5'] ."-". $clean['tel6'] ."\n";
        $auto_reply_text .= "会社所在地: " . $clean['company_location'] . "\n";
        $auto_reply_text .= "業種: " . $clean['industry'] . "\n";
		// $auto_reply_text .= "役職: " . $clean['director'] . "\n";
		$auto_reply_text .= "お問合せ内容詳細: " . nl2br($clean['content']) . "\n\n";
		$auto_reply_text .= "---------------------------- \n";
		$auto_reply_text .= "内容を確認の上、弊社担当者より追ってご連絡させていただきます。\n\n尚、お問い合わせの内容によってはお時間をいただく場合がございますのでご了承ください。\n";
		$auto_reply_text .= "■本メールは送信専用メールです。\nご返信頂いてもお答えできませんのでご了承ください。\n";
		$auto_reply_text .= "---------------------------- \n";
		
		$auto_reply_text .= "株式会社ネオキャリア \n東京都新宿区西新宿1-22-2 新宿サンエービル\n03-6756-0433\n";

		$auto_reply_text .= "---------------------------- \n";
		
		// テキストメッセージをセット
		$body = "--__BOUNDARY__\n";
		$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
		$body .= $auto_reply_text . "\n";
		$body .= "--__BOUNDARY__\n";
	
		// 自動返信メール送信
		mb_send_mail( $clean['email'], $auto_reply_subject, $body, $header);
	

		// 運営側へ送るメールの件名
		$admin_reply_subject = "【資料請求がありました】";
	
		// 本文を設定
		$admin_reply_text = "下記のお客様より資料請求がありました。\n 担当者は対応をお願いします\n\n";
		$admin_reply_text .= "お問合せ内容: " . $clean['inquiry'] . "\n";
		$admin_reply_text .= "会社名: " . $clean['company_name'] . "\n";
		$admin_reply_text .= "氏名: " . $clean['f_name'] . " " . $clean['l_name'] . "\n";
		$admin_reply_text .= "メールアドレス: " . $clean['email'] . "\n";
		$admin_reply_text .= "電話番号: " . $clean['tel1'] ."-". $clean['tel2'] ."-". $clean['tel3'] ."\n";
		$admin_reply_text .= "役職: " . $clean['director'] . "\n";
		$admin_reply_text .= "お問合せ内容詳細: " . nl2br($clean['content']) . "\n\n";
		$admin_reply_text .= "---------------------------- \n\n";
		$admin_reply_text .= "送信された日時：" . date("Y/m/d D H:i") . "\n";
		// $admin_reply_text .= "Neo Career";
		
		// テキストメッセージをセット
		$body = "--__BOUNDARY__\n";
		$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
		$body .= $admin_reply_text . "\n";
		$body .= "--__BOUNDARY__\n";
	
		// 管理者へメール送信
		mb_send_mail('denver.gomez@bpoc.co.jp,', $admin_reply_subject, $body, $header);
		
	} else {
		$page_flag = 0;
	}	
}
function validation($data) {
	$error = array();

	// Company name validation
	if( empty($data['company_name']) ) {
		$error['company_name'] = "「会社名」は入力必須項目です。";
	} elseif( 20 < mb_strlen($data['company_name']) ) {
		$error['company_name'] = "20文字以内で入力してください。";
	}

    if( empty($_POST['purpose']) ) {
		$error['purpose'] = "「お問合せ内容」は入力必須項目です。";
	} elseif( 20 < mb_strlen($data['purpose']) ) {
		$error['purpose'] = "20文字以内で入力してください。";
	}

    if( empty($data['company_location']) ) {
		$error['company_location'] = "「会社所在地」は入力必須項目です。";
	} elseif( 20 < mb_strlen($data['company_location']) ) {
		$error['company_location'] = "20文字以内で入力してください。";
	}

    if( empty($data['industry']) ) {
		$error['industry'] = "「業種」は入力必須項目です。";
	} elseif( 20 < mb_strlen($data['industry']) ) {
		$error['industry'] = "20文字以内で入力してください。";
	}

	// 氏名のバリデーション
	if( empty($data['f_name']) || empty($data['l_name'])) {
		$error['fullname'] = "「氏名」は入力必須項目です。";
	} elseif( 20 < mb_strlen($data['f_name']) || 20 < mb_strlen($data['l_name'])) {
		$error['fullname'] = "20文字以内で入力してください。";
	}

	// メールアドレスのバリデーション//
	if( empty($data['email']) ) {
		$error['email'] = "「メールアドレス」は入力必須項目です。";
	} elseif( !preg_match( '/^[0-9a-z_.\/?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $data['email']) ) {
		$error['email'] = "正しい形式で入力してください。";
	}

	// 電話番号のバリデーション
	if( empty($data['tel1']) || empty($data['tel2']) || empty($data['tel3']) ) {
		$error['tel'] = "「電話番号」は入力必須項目です。";
	} elseif( !preg_match( '/^[0-9]+[0-9.-]+$/', $data['tel1']) || !preg_match( '/^[0-9]+[0-9.-]+$/', $data['tel2']) || !preg_match( '/^[0-9]+[0-9.-]+$/', $data['tel3'])) {
		$error['tel'] = "正しい形式で入力してください。";
	}

	// // 氏名のバリデーション
	if( empty($data['content']) ) {
		$error['content'] = "「お問合せ内容詳細」は入力必須項目です。";
	} elseif( 300 < mb_strlen($data['content']) ) {
		$error['content'] = "20文字以内で入力してください。";
	}

	// // 氏名のバリデーション
	if( empty($data['privacy']) ) {
		$error['privacy'] = "「プライバシーポリシー」は入力必須項目です。";
	} 
	return $error;
}
?>

<?php if( $page_flag === 1 ):
	// 確認画面読み込み
require_once(dirname(__FILE__)."/inc/confirm.php");
 ?>
<?php elseif( $page_flag === 2 ):
	// サンクスページへリダイレクト
// $url = "https://www.e-vision.co.jp/lp/inc/thanks.php";
// header('Location: ' . $url, true, 301);
require_once(dirname(__FILE__)."/inc/thanks.php");
exit;
 ?>
<?php else:
	// フォーム画面読み込み
require_once(dirname(__FILE__)."/inc/form.php");
 ?>
<?php endif; ?>
