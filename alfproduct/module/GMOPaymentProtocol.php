<?php
class GMOPaymentProtocol{
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	const SITE_ID		= 'site000002060'; //サイトID
	const SITE_PW		= 'de34dhmx'; //サイトPW
	const SHOP_ID		= 'testpg0002104'; //ショップID
	const SHOP_PW		= 'yrd3kuh1'; //ショップPW

	const SHOP_NAME		= '日弁連'; //ショップ名
	const SHOP_MAIL_ADDRESS	= 'sano@vizlead.com';
	const SHOP_TEL		= '03-0000-0000';
	
	// コンビニ決済
	const CONV_CONTACT = 'お問い合わせ先'; //お問い合わせ先、Loppi･Famiポートを使用した際にバウチャー受領書に表示
	const CONV_CONTACT_TEL = '03-1234-5678'; //お問い合わせ先電話番号、Loppi･Famiポートを使用した際にバウチャー受領書に表示
	const CONV_CONTACT_TIME = '09:00-18:00'; //お問い合わせ先受付時間、Loppi･Famiポートを使用した際にバウチャー受領書に表示
	const CONV_PAYMENT_TERM_DAY = '10'; //支払期限日数、省略でショップ情報に設定された支払期限日数で処理される
	const CONV_SHOP_MAIL_ADDRESS = ''; //お客様へ結果通知メールを送信する際に同時加盟点様にも送信する場合のメールアドレスを設定 省略でショップ情報に設定されたメールアドレス宛てに送信
	const CONV_REGISTER_DISP1 = ''; //POSレジ表示欄1、ショップ名称を設定すること
	const CONV_REGISTER_DISP2 = ''; //POSレジ表示欄2、POSレジに表示される値
	const CONV_REGISTER_DISP3 = ''; //POSレジ表示欄3、POSレジに表示される値
	const CONV_REGISTER_DISP4 = ''; //POSレジ表示欄4、POSレジに表示される値
	const CONV_REGISTER_DISP5 = ''; //POSレジ表示欄5、POSレジに表示される値
	const CONV_REGISTER_DISP6 = ''; //POSレジ表示欄6、POSレジに表示される値
	const CONV_REGISTER_DISP7 = ''; //POSレジ表示欄7、POSレジに表示される値
	const CONV_REGISTER_DISP8 = ''; //POSレジ表示欄8、POSレジに表示される値
	const CONV_RECEIPTS_DISP1 = ''; //レシート表示欄1
	const CONV_RECEIPTS_DISP2 = ''; //レシート表示欄2
	const CONV_RECEIPTS_DISP3 = ''; //レシート表示欄3
	const CONV_RECEIPTS_DISP4 = ''; //レシート表示欄4
	const CONV_RECEIPTS_DISP5 = ''; //レシート表示欄5
	const CONV_RECEIPTS_DISP6 = ''; //レシート表示欄6
	const CONV_RECEIPTS_DISP7 = ''; //レシート表示欄7
	const CONV_RECEIPTS_DISP8 = ''; //レシート表示欄8
	const CONV_RECEIPTS_DISP9 = ''; //レシート表示欄9
	const CONV_RECEIPTS_DISP10 = ''; //レシート表示欄10
	
	// モバイルSuica決済
	const SUICA_ADD_INFO1	= ''; //お客様へ決済依頼メールに付加する文章を指定(モバイルSuica決済)
	const SUICA_ADD_INFO2	= ''; //お客様へ決済完了メールに付加する文章を指定(モバイルSuica決済)
	const SUICA_ADD_INFO3	= ''; //お客様へ決済内容確認画面に付加する文章を指定(モバイルSuica決済)
	const SUICA_ADD_INFO4	= ''; //お客様へ決済完了画面に付加する文章を指定(モバイルSuica決済)
	const SUICA_PAYMENT_TERMDAY	= '10'; //支払期限日数
	
	// 楽天Edy決済
	const EDY_ADD_INFO1 	= ''; //お客様へ決済依頼メールに付加する文章を指定
	const EDY_ADD_INFO2 	= ''; //お客様へ決済完了メールに付加する文章を指定
	const EDY_PAYMENT_TERM_DAY	= '10'; //支払期限日数
	const EDY_PAYMENT_TEAM_SEC	= ''; //支払期限秒
	
	// docomo決済

	const DOCOMO_RETURL	= 'https://gpay-test.kessai.ne.jp/payment/docomo/return.php'; //決済結果を受信する為の結果受信URL
	const DOCOMO_PAYMENT_TERM_SEC = '86400'; //【決済；実行】から【支払手続き開始IF】を呼び出すまでの期限
	
	// iD決済
	const ID_RETURL		= 'https://gpay-test.kessai.ne.jp/payment/id/return.php'; //決済結果を受信する為の結果受信URL
	const ID_PAYMENT_TERM_DAY	= '10'; //支払期限日数

	// WebMoney
	const WEBMONEY_RETURL	= 'https://gpay-test.kessai.ne.jp/payment/webmoney/return.php'; //決済結果を受信する為の結果受信URL
	const WEBMONEY_PAYMENT_TERMDAY	= '10'; //支払期限日数

	// au決済
	const AU_RETURL		= 'https://gpay-test.kessai.ne.jp/payment/au/return.php'; //決済結果を受信する為の結果受信URL
	const AU_PAYMENT_TERM_SEC = '86400'; //【決済；実行】から【支払手続き開始IF】を呼び出すまでの期限

	// ソフトバンクケータイ支払決済
	const SB_RETURL		= 'https://gpay-test.kessai.ne.jp/payment/sb/return.php'; //決済結果を受信する為の結果受信URL
	const SB_PAYMENT_TERM_SEC = '86400'; //【決済；実行】から【支払手続き開始IF】を呼び出すまでの期限

	// Pay-easy決済
	const PAYEASY_CONTACT = 'お問い合わせ先'; //お問い合わせ先
	const PAYEASY_CONTACT_TEL = '03-1234-5678'; //お問い合わせ先電話番号
	const PAYEASY_CONTACT_TIME = '09:00-18:00'; //お問い合わせ先受付時間
	const PAYEASY_PAYMENT_TERM_DAY = '10'; //支払期限日数、省略でショップ情報に設定された支払期限日数で処理される
	const PAYEASY_SHOP_MAIL_ADDRESS = ''; //お客様へ結果通知メールを送信する際に同時加盟点様にも送信する場合のメールアドレスを設定 省略でショップ情報に設定されたメールアドレス宛てに送信
	const PAYEASY_REGISTER_DISP1 = ''; //ATM表示欄1、ショップ名称を設定すること
	const PAYEASY_REGISTER_DISP2 = ''; //ATM表示欄2、ATMに表示される値
	const PAYEASY_REGISTER_DISP3 = ''; //ATM表示欄3、ATMに表示される値
	const PAYEASY_REGISTER_DISP4 = ''; //ATM表示欄4、ATMに表示される値
	const PAYEASY_REGISTER_DISP5 = ''; //ATM表示欄5、ATMに表示される値
	const PAYEASY_REGISTER_DISP6 = ''; //ATM表示欄6、ATMに表示される値
	const PAYEASY_REGISTER_DISP7 = ''; //ATM表示欄7、ATMに表示される値
	const PAYEASY_REGISTER_DISP8 = ''; //ATM表示欄8、ATMに表示される値
	const PAYEASY_RECEIPTS_DISP1 = ''; //利用明細表示欄1
	const PAYEASY_RECEIPTS_DISP2 = ''; //利用明細表示欄2
	const PAYEASY_RECEIPTS_DISP3 = ''; //利用明細表示欄3
	const PAYEASY_RECEIPTS_DISP4 = ''; //利用明細表示欄4
	const PAYEASY_RECEIPTS_DISP5 = ''; //利用明細表示欄5
	const PAYEASY_RECEIPTS_DISP6 = ''; //利用明細表示欄6
	const PAYEASY_RECEIPTS_DISP7 = ''; //利用明細表示欄7
	const PAYEASY_RECEIPTS_DISP8 = ''; //利用明細表示欄8
	const PAYEASY_RECEIPTS_DISP9 = ''; //利用明細表示欄9
	const PAYEASY_RECEIPTS_DISP10 = ''; //利用明細表示欄10
	
	// PayPal決済
	const PAYPAL_CURRENCY = 'JPY'; //通貨コード(複数通貨決済非対応)
	const PAYPAL_LOCALE = 'JP'; //PayPal決済画面の言語コード(複数言語選択非対応)
	const PAYPAL_REDIRECT_URL	= 'https://gpay-test.kessai.ne.jp/payment/paypal/return.php'; //決済結果を受信する為の結果受信URL

	const BASE_URL		= 'https://gpay-test.kessai.ne.jp'; //基本URL
	const ENTRY_TRAN	= '/payment/EntryTran.idPass'; //取引登録(カード決済)
	const EXEC_TRAN		= '/payment/ExecTran.idPass'; //決済実行(カード決済)
	const ENTRY_TRAN_CVS	= '/payment/EntryTranCvs.idPass'; //取引登録(コンビニ決済)
	const EXEC_TRAN_CVS	= '/payment/ExecTranCvs.idPass'; //決済実行(コンビニ決済)
	const ENTRY_TRAN_SUICA	= '/payment/EntryTranSuica.idPass'; //取引登録(モバイルSuica決済)
	const EXEC_TRAN_SUICA	= '/payment/ExecTranSuica.idPass'; //決済実行(モバイルSuica決済)
	const ENTRY_TRAN_EDY	= '/payment/EntryTranEdy.idPass'; //取引登録(楽天Edy決済)
	const EXEC_TRAN_EDY	= '/payment/ExecTranEdy.idPass'; //決済実行(楽天Edy決済)
	const ENTRY_TRAN_DOCOMO	= '/payment/EntryTranDocomo.idPass'; //取引登録(docomo決済)
	const EXEC_TRAN_DOCOMO	= '/payment/ExecTranDocomo.idPass'; //決済実行(docomo決済)
	const ENTRY_TRAN_ID	= '/payment/EntryTranNetid.idPass'; //取引登録(iD決済)
	const EXEC_TRAN_ID	= '/payment/ExecTranNetid.idPass'; //決済実行(iD決済)
	const ENTRY_TRAN_WEBMONEY	= '/payment/EntryTranWebmoney.idPass'; //取引登録(Webmoney決済)
	const EXEC_TRAN_WEBMONEY	= '/payment/ExecTranWebmoney.idPass'; //決済実行(Webmoney決済)
	const ENTRY_TRAN_PAYEASY	= '/payment/EntryTranPayEasy.idPass'; //取引登録(Pay-easy決済)
	const EXEC_TRAN_PAYEASY	= '/payment/ExecTranPayEasy.idPass'; //決済実行(Pay-easy決済)
	const ENTRY_TRAN_AU	= '/payment/EntryTranAu.idPass'; //取引登録(auかんたん決済)
	const EXEC_TRAN_AU	= '/payment/ExecTranAu.idPass'; //決済実行(auかんたん決済)
	const ENTRY_TRAN_PAYPAL	= '/payment/EntryTranPaypal.idPass'; //取引登録(PayPal決済)
	const EXEC_TRAN_PAYPAL	= '/payment/ExecTranPaypal.idPass'; //決済実行(PayPal決済)
	const ENTRY_TRAN_SB	= '/payment/EntryTranSb.idPass'; //取引登録(ソフトバンクケータイ支払決済)
	const EXEC_TRAN_SB	= '/payment/ExecTranSb.idPass'; //決済実行(ソフトバンクケータイ支払決済)

	const SAVE_MEMBER 	= '/payment/SaveMember.idPass'; //会員登録(カード会員情報)
	const UPDATE_MEMBER 	= '/payment/UpdateMember.idPass'; //会員更新(カード会員情報)
	const DELETE_MEMBER 	= '/payment/DeleteMember.idPass'; //会員削除(カード会員情報)
	const SEARCH_MEMBER 	= '/payment/SearchMember.idPass'; //会員参照(カード会員情報)
	
	const SAVE_CARD 	= '/payment/SaveCard.idPass'; //カード登録・更新(カード情報)
	const DELETE_CARD 	= '/payment/DeleteCard.idPass'; //カード削除(カード情報)
	const SEARCH_CARD 	= '/payment/SearchCard.idPass'; //カード参照(カード情報)
	const CARD_SEQ_MODE 	= '0'; // カード登録連番モード 0:論理モード、1:物理モード
	
	
	
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(カード決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $type : 処理タイプ（1：有効性チェック　2:即時売上　3:仮売上　4:簡易オーソリ　）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran( $order_id=0, $type=0, $price=0, $tax=0 ){
		$job_type = array();
		$job_type[0] = 'CHECK';   //有効性チェック
		$job_type[1] = 'CHECK';   //有効性チェック
		$job_type[2] = 'CAPTURE'; //即時売上
		$job_type[3] = 'AUTH';    //仮売上
		$job_type[4] = 'SAUTH';   //簡易オーソリ

		$data = array();
		//$data['VerSion'] 	= '';
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		if( is_numeric($type) ){
			$data['JobCd'] 		= $job_type[$type];
		} else {
			$data['JobCd'] 		= $job_type[0];
		}
		//$data['ItemCode'] 	= "0000990";
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$data['TdFlag'] 	= '0';
		$data['TdTenantName'] 	= base64_encode(mb_convert_encoding(self::SHOP_NAME, "EUC-JP", "auto"));
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN, $data );

		$this->tran_return_write_log('entry_tran', $data, $return);

		parse_str($return,$arr);

		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(カード決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $card_no : クレジットカードの番号
	//  $expire : クレジットカードの有効期限（YYMM形式）
	//  $security_code : カードの裏面(あるいは表面)に記載されている3桁もしくは4桁の数字
	//  $pin : 決済に使用するクレジットカードの暗証番号
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran( $access_id='', $access_pass='', $order_id='', $card_no='', $expire='', $security_code='', $pin='', $etc1='', $etc2='', $etc3='' ){
		$method_type = array();
		$method_type[0] = '1';   //1：一括
		$method_type[1] = '1';   //1：一括
		$method_type[2] = '2';   //2：分割
		$method_type[3] = '3';   //3：ボーナス一括
		$method_type[4] = '4';   //4：ボーナス分割
		$method_type[5] = '5';   //5：リボ


		$data = array();
		//$data['VerSion'] 	= '';
		$data['AccessID'] 	= trim($access_id);
		$data['AccessPass'] 	= trim($access_pass);
		$data['OrderID'] 	= trim($order_id);
		$data['Method'] 	= $method_type[0];
		//$data['PayTimes'] 	= 1;
		$data['CardNo'] 	= trim($card_no);
		$data['Expire'] 	= trim($expire);
		if( $security_code !="" ){
			$data['SecurityCode'] = $security_code;
		}
		if( $pin !="" ){
			$data['PIN'] = $pin;
		}
		$data['ClientField1'] 	= trim($etc1);
		$data['ClientField2'] 	= trim($etc2);
		$data['ClientField3'] 	= trim($etc3);
		$data['ClientFieldFlag'] = '1';
		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN, $data );

		$this->tran_return_write_log('exec_tran', $data, $return);

		parse_str($return,$arr);

		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(コンビニ決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_cvs( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_CVS, $data );
		
		$this->tran_return_write_log('entry_tran_cvs', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(コンビニ決済)
	//  $access_id : 取引登録（function : entry_tran_cvs）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran_cvs）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $convenience : 支払コンビニコード
	//  $customer_name : 氏名(セブンイレブンは半角記号不可 code:00007)
	//  $customer_kana : フリガナ
	//  $tel_no : 電話番号
	//  $mail_address : メールアドレス
	//  $reserve_no : 予約番号
	//  $member_no : 会員番号
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_cvs( $access_id='', $access_pass='', $order_id='', $convenience='', $customer_name='', $customer_kana='', $tel_no='', $mail_address='', $reserve_no='', $member_no='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		// 必須パラメータ
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['Convenience'] 	= $convenience;
		$data['CustomerName'] 	= mb_convert_encoding($customer_name, "SJIS", "auto");
		$data['CustomerKana'] 	= mb_convert_encoding($customer_kana, "SJIS", "auto");
		$data['TelNo'] = $tel_no;
		$data['ReceiptsDisp11'] 	= mb_convert_encoding(self::CONV_CONTACT, "SJIS", "auto");
		$data['ReceiptsDisp12'] 	= self::CONV_CONTACT_TEL;
		$data['ReceiptsDisp13'] 	= self::CONV_CONTACT_TIME;
		// 省略可パラメータ
		$data['MailAddress'] 	= $mail_address;
		$data['ReserveNo'] 	= $reserve_no;
		$data['MemberNo'] 	= $member_no;
		$data['PaymentTermDay'] 	= self::CONV_PAYMENT_TERM_DAY;
		$data['ShopMailAddress'] 	= self::CONV_SHOP_MAIL_ADDRESS;
		$data['RegisterDisp1'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP1, "SJIS", "auto");
		$data['RegisterDisp2'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP2, "SJIS", "auto");
		$data['RegisterDisp3'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP3, "SJIS", "auto");
		$data['RegisterDisp4'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP4, "SJIS", "auto");
		$data['RegisterDisp5'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP5, "SJIS", "auto");
		$data['RegisterDisp6'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP6, "SJIS", "auto");
		$data['RegisterDisp7'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP7, "SJIS", "auto");
		$data['RegisterDisp8'] 	= mb_convert_encoding(self::CONV_REGISTER_DISP8, "SJIS", "auto");
		$data['ReceiptsDisp1'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP1, "SJIS", "auto");
		$data['ReceiptsDisp2'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP2, "SJIS", "auto");
		$data['ReceiptsDisp3'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP3, "SJIS", "auto");
		$data['ReceiptsDisp4'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP4, "SJIS", "auto");
		$data['ReceiptsDisp5'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP5, "SJIS", "auto");
		$data['ReceiptsDisp6'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP6, "SJIS", "auto");
		$data['ReceiptsDisp7'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP7, "SJIS", "auto");
		$data['ReceiptsDisp8'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP8, "SJIS", "auto");
		$data['ReceiptsDisp9'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP9, "SJIS", "auto");
		$data['ReceiptsDisp10'] 	= mb_convert_encoding(self::CONV_RECEIPTS_DISP10, "SJIS", "auto");
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';
		
		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_CVS, $data );
		
		$this->tran_return_write_log('exec_tran_cvs', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(モバイルSuica決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $type : 処理タイプ（1：有効性チェック　2:即時売上　3:仮売上　4:簡易オーソリ　）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_suica( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_SUICA, $data );
		$this->tran_return_write_log('entry_tran_suica', $data, $return);
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(モバイルSuica決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $item_name : モバイルSuicaアプリに表示
	//  $mail_address : お客様の携帯メールアドレス
	//  ClientField1
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_suica( $access_id='', $access_pass='', $order_id='', $item_name='', $mail_address='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		//$data['VerSion'] 	= '';
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['ItemName'] 	= mb_convert_encoding($item_name, "SJIS", "auto");
		$data['MailAddress'] 	= $mail_address;
		$data['ShopMailAddress']= self::SHOP_MAIL_ADDRESS;
		$data['SuicaAddInfo1'] 	= self::SUICA_ADD_INFO1;
		$data['SuicaAddInfo2'] 	= self::SUICA_ADD_INFO2;
		$data['SuicaAddInfo3'] 	= self::SUICA_ADD_INFO3;
		$data['SuicaAddInfo4'] 	= self::SUICA_ADD_INFO4;
		$data['PaymentTermDay'] = self::SUICA_PAYMENT_TERMDAY;
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';

		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_SUICA, $data );
		$this->tran_return_write_log('exec_tran_suica', $data, $return);

		parse_str($return,$arr);
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(楽天Edy決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_edy( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_EDY, $data );
		
		$this->tran_return_write_log('entry_tran_edy', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(楽天Edy決済)
	//  $access_id : 取引登録（function : entry_tran_edy）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran_edy）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $mail_address : メールアドレス
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_edy( $access_id='', $access_pass='', $order_id='', $mail_address='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		// 必須パラメータ
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['MailAddress'] 	= $mail_address;
		// 省略可パラメータ
		$data['ShopMailAddress'] 	= self::CONV_SHOP_MAIL_ADDRESS;
		$data['EdyAddInfo1'] 	= mb_convert_encoding(self::EDY_ADD_INFO1, "SJIS", "auto");
		$data['EdyAddInfo2'] 	= mb_convert_encoding(self::EDY_ADD_INFO2, "SJIS", "auto");
		$data['PaymentTermDay'] 	= self::EDY_PAYMENT_TERM_DAY;
		$data['PaymentTermSec'] 	= self::EDY_PAYMENT_TEAM_SEC;
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';
		
		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_EDY, $data );
		
		$this->tran_return_write_log('exec_tran_edy', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(docomo決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $type : 処理タイプ（1：有効性チェック　2:即時売上　3:仮売上　4:簡易オーソリ　）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_docomo( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		//$data['JobCd'] 	= "AUTH";//仮売上
		$data['JobCd'] 		= "CAPTURE";//即時売上
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_DOCOMO, $data );
		$this->tran_return_write_log('entry_tran_docomo', $data, $return);
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(docomo決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $docomo1 : spモードの場合のみ、以下のドコモケータイ払い画面に表示
	//  $docomo2 : spモードの場合のみ、以下のドコモケータイ払い画面に表示
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_docomo( $access_id='', $access_pass='', $order_id='', $docomo1='', $docomo2='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		//$data['VerSion'] 	= '';
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;

		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['DocomoDisp1'] 	= mb_convert_encoding($docomo1, "SJIS", "auto");
		$data['DocomoDisp2'] 	= mb_convert_encoding($docomo2, "SJIS", "auto");
		$data['RetURL'] 	= self::DOCOMO_RETURL;
		$data['PaymentTermSec'] = self::DOCOMO_PAYMENT_TERM_SEC;

		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_DOCOMO, $data );
		$this->tran_return_write_log('exec_tran_docomo', $data, $return);

		parse_str($return,$arr);
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(iD決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $type : 処理タイプ（1：有効性チェック　2:即時売上　3:仮売上　4:簡易オーソリ　）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_id( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		//$data['JobCd'] 	= "AUTH";//仮売上
		$data['JobCd'] 		= "CAPTURE";//即時売上
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$data['RetURL'] 	= self::ID_RETURL;

		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_ID, $data );
		$this->tran_return_write_log('entry_tran_id', $data, $return);
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
			return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(iD決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $name : お客様の氏名（決済開始メールに表示）
	//  $mail : 決済開始メール送付先のお客様の携帯メールアドレス
	//  $item : 商品・サービス名
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_id( $access_id='', $access_pass='', $order_id='', $name='', $mail='', $item='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		//$data['VerSion'] 	= '';
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['CustomerName'] 	= mb_convert_encoding($name, "SJIS", "auto");
		$data['PaymentTermDay'] = self::ID_PAYMENT_TERM_DAY;
		$data['MailAddress'] 	= $mail;
		$data['ShopMailAddress']= self::SHOP_MAIL_ADDRESS;
		$data['ItemName'] 	= mb_convert_encoding($item, "SJIS", "auto");
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';

		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_ID, $data );
		$this->tran_return_write_log('exec_tran_id', $data, $return);

		parse_str($return,$arr);
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(Webmoney決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_webmoney( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_WEBMONEY, $data );
		$this->tran_return_write_log('entry_tran_webmoney', $data, $return);
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(Webmoney決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $item_name : WebMoneyの決済画面に表示する商品名を設定
	//  $user_name : お客様の氏名（決済開始メールに表示）
	//  $mail_address : 決済開始メール送付先
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_webmoney( $access_id='', $access_pass='', $order_id='', $item_name='', $user_name='', $mail_address='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		//$data['VerSion'] 	= '';
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['ItemName'] 	= mb_convert_encoding($item_name, "SJIS", "auto");
		$data['CustomerName'] 	= mb_convert_encoding($user_name, "SJIS", "auto");
		$data['MailAddress'] 	= $mail_address;
		$data['ShopMailAddress']= self::SHOP_MAIL_ADDRESS;
		$data['PaymentTermDay'] = self::WEBMONEY_PAYMENT_TERMDAY;
		$data['RedirectURL'] 	= self::WEBMONEY_RETURL;
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';

		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_WEBMONEY, $data );
		$this->tran_return_write_log('exec_tran_webmoney', $data, $return);

		parse_str($return,$arr);
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(Pay-easy決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_payeasy( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_PAYEASY, $data );
		
		$this->tran_return_write_log('entry_tran_payeasy', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(Pay-easy決済)
	//  $access_id : 取引登録（function : entry_tran_payeasy）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran_payeasy）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $customer_name : 氏名
	//  $customer_kana : フリガナ
	//  $tel_no : 電話番号
	//  $mail_address : メールアドレス
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_payeasy( $access_id='', $access_pass='', $order_id='', $customer_name='', $customer_kana='', $tel_no='', $mail_address='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		// 必須パラメータ
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['CustomerName'] 	= mb_convert_encoding($customer_name, "SJIS", "auto");
		$data['CustomerKana'] 	= mb_convert_encoding($customer_kana, "SJIS", "auto");
		$data['TelNo'] = $tel_no;
		$data['ReceiptsDisp11'] 	= mb_convert_encoding(self::PAYEASY_CONTACT, "SJIS", "auto");
		$data['ReceiptsDisp12'] 	= self::PAYEASY_CONTACT_TEL;
		$data['ReceiptsDisp13'] 	= self::PAYEASY_CONTACT_TIME;
		// 省略可パラメータ
		$data['MailAddress'] 	= $mail_address;
		$data['ReserveNo'] 	= $reserve_no;
		$data['MemberNo'] 	= $member_no;
		$data['PaymentTermDay'] 	= self::PAYEASY_PAYMENT_TERM_DAY;
		$data['ShopMailAddress'] 	= self::PAYEASY_SHOP_MAIL_ADDRESS;
		$data['RegisterDisp1'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP1, "SJIS", "auto");
		$data['RegisterDisp2'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP2, "SJIS", "auto");
		$data['RegisterDisp3'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP3, "SJIS", "auto");
		$data['RegisterDisp4'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP4, "SJIS", "auto");
		$data['RegisterDisp5'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP5, "SJIS", "auto");
		$data['RegisterDisp6'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP6, "SJIS", "auto");
		$data['RegisterDisp7'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP7, "SJIS", "auto");
		$data['RegisterDisp8'] 	= mb_convert_encoding(self::PAYEASY_REGISTER_DISP8, "SJIS", "auto");
		$data['ReceiptsDisp1'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP1, "SJIS", "auto");
		$data['ReceiptsDisp2'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP2, "SJIS", "auto");
		$data['ReceiptsDisp3'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP3, "SJIS", "auto");
		$data['ReceiptsDisp4'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP4, "SJIS", "auto");
		$data['ReceiptsDisp5'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP5, "SJIS", "auto");
		$data['ReceiptsDisp6'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP6, "SJIS", "auto");
		$data['ReceiptsDisp7'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP7, "SJIS", "auto");
		$data['ReceiptsDisp8'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP8, "SJIS", "auto");
		$data['ReceiptsDisp9'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP9, "SJIS", "auto");
		$data['ReceiptsDisp10'] 	= mb_convert_encoding(self::PAYEASY_RECEIPTS_DISP10, "SJIS", "auto");
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';
		
		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_PAYEASY, $data );
		
		$this->tran_return_write_log('exec_tran_payeasy', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(auかんたん決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $type : 処理タイプ（1：有効性チェック　2:即時売上　3:仮売上　4:簡易オーソリ　）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_au( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		//$data['JobCd'] 	= "AUTH";//仮売上
		$data['JobCd'] 		= "CAPTURE";//即時売上
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_AU, $data );
		$this->tran_return_write_log('entry_tran_au', $data, $return);
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(auかんたん決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_au( $access_id='', $access_pass='', $order_id='', $item_name='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		//$data['VerSion'] 	= '';
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		//$data['SiteID'] 	= self::SITE_ID;
		//$data['SitePass'] 	= self::SITE_PW;
		$data['Commodity'] 	= mb_convert_encoding($item_name, "SJIS", "auto");
		$data['RetURL'] 	= self::AU_RETURL;
		$data['PaymentTermSec'] = self::AU_PAYMENT_TERM_SEC;
		$data['ServiceName'] 	= mb_convert_encoding(self::SHOP_NAME, "SJIS", "auto");
		$data['ServiceTel'] 	= mb_convert_encoding(self::SHOP_TEL, "SJIS", "auto");
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] = '1';

		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_AU, $data );
		$this->tran_return_write_log('exec_tran_au', $data, $return);

		parse_str($return,$arr);
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(PayPal決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_paypal( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		$data['JobCd'] 		= "CAPTURE";
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$data['Currency'] 	= self::PAYPAL_CURRENCY;
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_PAYPAL, $data );
		
		$this->tran_return_write_log('entry_tran_paypal', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(PayPal決済)
	//  $access_id : 取引登録（function : entry_tran_paypal）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran_paypal）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $item_name : 商品・サービス名（CHAR 64）
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_paypal( $access_id='', $access_pass='', $order_id='', $item_name='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		// 必須パラメータ
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['ItemName'] 	= mb_convert_encoding($item_name, "SJIS", "auto");
		$data['RedirectURL'] 	= self::PAYPAL_REDIRECT_URL;
		// 省略可パラメータ
		$data['Locale'] 	= self::PAYPAL_LOCALE;
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['ClientFieldFlag'] 	= '1';
		
		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_PAYPAL, $data );
		
		$this->tran_return_write_log('exec_tran_paypal', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 取引登録を行う(ソフトバンクケータイ支払決済)
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $type : 処理タイプ（1：有効性チェック　2:即時売上　3:仮売上　4:簡易オーソリ　）
	//  $price : 合計金額
	//  $tax : 税送料
	//  return : 
	*/
	function entry_tran_sb( $order_id=0, $price=0, $tax=0 ){
		$data = array();
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['OrderID'] 	= $order_id;
		//$data['JobCd'] 	= "AUTH";//仮売上
		$data['JobCd'] 		= "CAPTURE";//即時売上
		$data['Amount'] 	= $price;
		if(trim($tax)!="" && is_numeric($tax) && $tax>0 ){
			$data['Tax'] 		= $tax;
		}
		$return = $this->http_post( self::BASE_URL.self::ENTRY_TRAN_SB, $data );
		$this->tran_return_write_log('entry_tran_sb', $data, $return);
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 決済実行を行う(ソフトバンクケータイ支払決済)
	//  $access_id : 取引登録（function : entry_tran）で発行された値を指定
	//  $access_pass : 取引登録（function : entry_tran）で発行された値を指定
	//  $order_id : 取引を識別するための値を設定する値（CHAR 27）
	//  $etc1 : 自由項目
	//  $etc2 : 自由項目
	//  $etc3 : 自由項目
	//  return : 
	*/
	function exec_tran_sb( $access_id='', $access_pass='', $order_id='', $etc1='', $etc2='', $etc3='' ){
		$data = array();
		//$data['VerSion'] 	= '';
		$data['ShopID'] 	= self::SHOP_ID;
		$data['ShopPass'] 	= self::SHOP_PW;
		$data['AccessID'] 	= $access_id;
		$data['AccessPass'] 	= $access_pass;
		$data['OrderID'] 	= $order_id;
		$data['ClientField1'] 	= $etc1;
		$data['ClientField2'] 	= $etc2;
		$data['ClientField3'] 	= $etc3;
		$data['RetURL'] 	= self::SB_RETURL;
		$data['PaymentTermSec'] = self::SB_PAYMENT_TERM_SEC;

		$return = $this->http_post( self::BASE_URL.self::EXEC_TRAN_SB, $data );
		$this->tran_return_write_log('exec_tran_sb', $data, $return);

		parse_str($return,$arr);
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */



	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 会員情報を登録する
	//  $member_id 会員ID
	//  $member_name 会員氏名
	//  return : 
	*/
	function save_member( $member_id, $member_name ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		$data['MemberName'] = mb_convert_encoding($member_name, "SJIS", "auto");
		
		$return = $this->http_post( self::BASE_URL.self::SAVE_MEMBER, $data );
		
		$this->tran_return_write_log('save_member', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 会員情報を更新する
	//  $member_id 会員ID
	//  $member_name 会員氏名
	//  return : 
	*/
	function update_member( $member_id, $member_name ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		$data['MemberName'] = mb_convert_encoding($member_name, "SJIS", "auto");
		
		$return = $this->http_post( self::BASE_URL.self::UPDATE_MEMBER, $data );
		
		$this->tran_return_write_log('update_member', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 会員情報を削除する
	//  $member_id 会員ID
	//  return : 
	*/
	function delete_member( $member_id ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		
		$return = $this->http_post( self::BASE_URL.self::DELETE_MEMBER, $data );
		
		$this->tran_return_write_log('delete_member', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 会員情報を参照する
	//  $member_id 会員ID
	//  return : 
	*/
	function search_member( $member_id ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		
		$return = $this->http_post( self::BASE_URL.self::SEARCH_MEMBER, $data );
		
		$this->tran_return_write_log('search_member', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// カード情報を登録・更新する
	//  $member_id 会員ID
	//  $card_no カード番号(CHAR 16)
	//  $expire 有効期限(CHAR4 YYMM形式)
	//  $default_flag 洗替・継続課金フラグ(0：継続課金の対象としない、1:継続課金の対象とする)
	//  $card_seq カード登録連番(更新時は対象の値を指定すること)
	//  $holder_name 名義人
	//  $card_name カード会社略称
	//  $card_pass カードパスワード(決済時に必要としたい場合に設定する)
	//  return : 
	*/
	function save_card( $member_id, $card_no, $expire, $default_flag='0', $card_seq='', $holder_name='', $card_name='', $card_pass='' ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		$data['SeqMode'] 	= self::CARD_SEQ_MODE;
		$data['CardSeq'] 	= $card_seq;
		$data['DefaultFlag'] 	= $default_flag;
		$data['CardName'] 	= $card_name;
		$data['CardNo'] 	= $card_no;
		$data['CardPass'] 	= $card_pass;
		$data['Expire'] 	= $expire;
		$data['HolderName'] 	= mb_convert_encoding($holder_name, "SJIS", "auto");
		
		$return = $this->http_post( self::BASE_URL.self::SAVE_CARD, $data );
		
		$this->tran_return_write_log('save_card', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// カード情報を削除する
	//  $member_id 会員ID
	//  $card_seq カード登録連番
	//  return : 
	*/
	function delete_card( $member_id, $card_seq ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		$data['SeqMode'] 	= self::CARD_SEQ_MODE;
		$data['CardSeq'] 	= $card_seq;
		
		$return = $this->http_post( self::BASE_URL.self::DELETE_CARD, $data );
		
		$this->tran_return_write_log('delete_card', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// カード情報を参照する
	//  $member_id 会員ID
	//  $card_seq カード登録連番
	//  return : 
	*/
	function search_card( $member_id, $card_seq ){
		$data = array();
		$data['SiteID'] 	= self::SITE_ID;
		$data['SitePass'] 	= self::SITE_PW;
		$data['MemberID'] 	= $member_id;
		$data['SeqMode'] 	= self::CARD_SEQ_MODE;
		$data['CardSeq'] 	= $card_seq;
		
		$return = $this->http_post( self::BASE_URL.self::SEARCH_CARD, $data );
		
		$this->tran_return_write_log('search_card', $data, $return);
		
		$arr = $this->error_check($return);
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	
	
	
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// POST送信を行う
	//  $url : 送信先URL
	//  $data : 配列指定した送信パラメータ
	//  return : HTTPリクエストに対するリターン
	*/
	function http_post( $url='', $data=array() ){
		$options = array('http' => array(
			'method' => 'POST',
			'content' => http_build_query($data),
		));
		$contents = file_get_contents($url, false, stream_context_create($options));
		return $contents;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	function write_log($str){
		$datetime = date( "Y/m/d (D) H:i:s", time() );//日時
		$client_ip = $_SERVER["REMOTE_ADDR"];//クライアントのIP
		$request_url = $_SERVER["REQUEST_URI"];//アクセスしたURL
		$msg = "[url {$request_url}] {$str}";
		error_log($msg."", 3,"/alflearning-data/alfproduct/logs/gmo.log");
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 戻り値がエラーかチェックする
	// $return : 取引登録・決済実行後の戻り値
	// return : エラー情報配列 or 取得値そのまま
	*/
	function error_check($return){
		parse_str($return,$arr);
		
		if(array_key_exists("ErrCode",$arr)){
			//if(strpos($arr["ErrCode"], '|')){
				//$arr["ErrCode"] = explode('|',$arr["ErrCode"]);
				$temp = explode('|',$arr["ErrCode"]);
				$arr["ErrCode"] = $temp[0];
			//}
		}
		if(array_key_exists("ErrInfo",$arr)){
			//if(strpos($arr["ErrInfo"], '|')){
				//$arr["ErrInfo"] = explode('|',$arr["ErrInfo"]);
				$temp = explode('|',$arr["ErrInfo"]);
				$arr["ErrInfo"] = $temp[0];
			//}
		}
		
		return $arr;
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	/*
	// 戻り値がエラーかチェックする
	// $return : 取引登録・決済実行後の戻り値
	// return : エラー情報配列 or 取得値そのまま
	*/
	function tran_return_write_log($function_name, $data, $return){
		$this->write_log('********************************');
		$this->write_log('--------------------------------');
		$this->write_log("class GMOPaymentProtocol->function $function_name");
		$this->write_log('--------------------------------');
		if ( array_key_exists("CardNo",$data) ) {
			$temp = $data;
			$temp["CardNo"] = "************".mb_substr($temp["CardNo"],-4);
			$this->write_log(serialize($temp));
		} else {
			$this->write_log(serialize($data));
		}
		$this->write_log('--------------------------------');
		$this->write_log(serialize($return));
		$this->write_log('--------------------------------');
		$this->write_log('********************************');
	}
	/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
}
?>
