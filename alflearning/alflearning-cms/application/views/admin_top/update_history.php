<?php
	$this->lang->load('common');
	$this->lang->load('msg');

	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);
?>
<style type="text/css">
#wrapper #main #contents_main  LI{
	height			: 20px;
	overflow		: hidden;
	padding-bottom	: 10px;
}
#wrapper #main #contents_main  LI.show{
	height		: auto;
}
#wrapper #main #contents_main LI H2{
	margin		: 0px 0px 5px 0px;
}
#wrapper #main #contents_main LI H3{
	background	: url("/static/image/ic_arrow.gif") no-repeat 0px center;
	padding-left:10px;
	text-align	: left;
	margin		: 10px 0px 0px 10px;
	line-height	: 1.3em;
}
#wrapper #main #contents_main  LI .history_detail{
	line-height		: 1.4em;
	margin-left		: 15px;
	background		: #F7F7F0;
	padding			: 5px 5px 5px 10px;

	border-radius			: 5px 5px 5px 5px;
	-webkit-border-radius	: 5px 5px 5px 5px;
	-moz-border-radius		: 5px 5px 5px 5px;
}
#wrapper #main #contents_main LI H2:hover{
	cursor		: pointer;
	color		: #5593ED;
}
</style>
<script type="text/javascript">
$(function(){
	$('#contents_main LI H2').bind('click', function(){
		var parent = $(this).parent();
		var nextHeight = $(parent)[0].scrollHeight;
		if(parent.height() > 20){
			nextHeight = 20;
		}
		parent.animate({
			'height'	: nextHeight
		});
	});
});
</script>
</head>
<body>
	<?php
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_update_history','更新履歴') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_update_history_comment','システム更新履歴') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'	=> 'update_history',
			));?>

			<ul id="contents_main">
				<li class="show">
					<h2>Ve1.5.0 -> Ver1.6.0&nbsp;&nbsp;&nbsp;&nbsp;(2013/03/25)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						通知機能を追加しました（通知が表示される条件は下記となります）<br />
							・受講者による課題の提出時<br />
							・資料登録の完了時<br />
							・授業資料の登録完了時<br />
							・授業資料の既存資料登録完了時<br />
							・図書室の新規登録完了時<br />
							・ビデオの新規登録完了時<br />
						課題機能追加<br />
						メイン講師とサブ講師の機能追加<br />
						受講者にグループの機能追加<br />
					</div>

					<h3>講師授業</h3>
					<div class="history_detail">
						メイン講師とサブ講師の機能追加<br />
					</div>

					<h3>受講者画面</h3>
					<div class="history_detail">
						課題機能追加<br />
					</div>

					<h3>不具合修正</h3>
					<div class="history_detail">
						管理画面で動画登録時、タグが登録できない条件があった不具合の修正
					</div>
				</li>

					<li class="show">
					<h2>Ve1.2.8 -> Ver1.5.0&nbsp;&nbsp;&nbsp;&nbsp;(2013/02/12)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						[super userのみ]全学校へのお知らせを投稿出来る機能を追加しました<br />
					</div>

					<h3>講師授業</h3>
					<div class="history_detail">
						ペイントツールで消しゴム選択後に大きさが変えられるパターンがある不具合の修正<br />
						カメラの取得サイズで、16:9が取得出来るようになりました<br />
					</div>

					<h3>受講者画面</h3>
					<div class="history_detail">
						受講者のPC画面で映像を拡大（最大化）することが可能になりました<br />
						IE8,9での表示を最適化しました（推奨端末には含まれてはいませんのでご注意ください）<br />
						PC用マイページをfollower.alflearning.com/mypage/に変更しました<br />
						図書室資料からのメモ書きが出来るようになりました<br />
						図書室資料から動画のタグ検索ができるようになりました<br />
						講座、タグについての複数選択での検索が出来るようになりました<br />
					</div>

					<h3>全体</h3>
					<div class="history_detail">
						画面ページタイトルの統一を行いました<br />
						/loginを?backurl=対応させました（直接リンクした際にログイン後指定したURLにリダイレクトします）<br />
					</div>
				</li>

				<li class="show">
					<h2>Ve1.2.7 -> Ver1.2.8&nbsp;&nbsp;&nbsp;&nbsp;(2012/01/10)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						講座から一覧選択して登録、削除が出来るようにしました<br />
						講師、受講生を削除した後、もう一度同一のメールアドレスを登録することができない問題を修正しました<br />
					</div>

					<h3>講師授業</h3>
					<div class="history_detail">
						聴講モード（canvaｓのフルスクリーン表示）時にツールバーを出すようにしました<br />
					</div>

					<h3>受講者授業</h3>
					<div class="history_detail">
						PC版の図書室資料の表示方法を変更しました<br />
					</div>
				</li>
				<li class="show">
					<h2>Ve1.2.6 -> Ver1.2.7&nbsp;&nbsp;&nbsp;&nbsp;(2012/11/26)</h2>

					<h3>UI</h3>
					<div class="history_detail">
						ページフッターの表示を統一しました
					</div>
					<h3>管理画面</h3>
					<div class="history_detail">
						ビデオファイルの未アップロード状態の場合に登録の削除を行えるようにしました
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						音声チャットの内容はデフォルトで他生徒へ送らないように変更いたしました<br />
						　音声チャットを受講生に送る場合は「共有」ボタンを押下することにより共有出来るようになります
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						左メニュー幅が狭いため、文字数の多いタグや講座があった場合に文字サイズを少し小さくして表示するようにしました
					</div>
				</li>
				<li class="show">
					<h2>Ve1.2.5 -> Ver1.2.6&nbsp;&nbsp;&nbsp;&nbsp;(2012/11/19)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						ビデオ管理の動作仕様変更<br />
						　１．状態「アップロード待ち」以外にも、alfstream側でのエラー発生時にビデオ再アップロード可能とするように変更<br />
						　２．ビデオ再アップロード可能の場合、一覧の文字色を変更。<br />
						授業に付与する資料について、登録者が別で講座が同じ資料が表示できない条件がある不具合の修正<br />
						eLearningManagerのURLを設定する際、既に他の学校で登録してあるURLが指定された場合はエラーにて弾くように変更<br />
						ユーザ情報連携時、内部的に性と名を分けて登録するように対応<br />
						受講者画面、図書室・ビデオ授業の講座が重複して表示される不具合の改修<br />
						その他軽微な不具合修正<br />
					</div>
					<h3>受講者授業<br /></h3>
					<div class="history_detail">
						iOS標準の惰性スクロールを可能にするように修正<br />
						タグ一覧を表示している時の矢印ボタンを下向きになるように変更<br />
						生徒がノートを提出->先生が何もせずに返却->生徒が何もせずに提出で画像データが無くなることがある不具合の修正<br />
					</div>
				</li>
				<li class="show">
					<h2>Ve1.2.1 -> Ver1.2.5&nbsp;&nbsp;&nbsp;&nbsp;(2012/11/12)</h2>

					<h3>サービス拡張</h3>
					<div class="history_detail">
						<a href="http://elearningmanager.jp/" target="_blank" rel="noopener noreferrer">eLearning Manager</a>との連携に対応しました（別途ご契約が必要となります）<br />
					</div>
				</li>
				<li>
					<h2>Ve1.2.0 -> Ver1.2.1&nbsp;&nbsp;&nbsp;&nbsp;(2012/11/05)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						講座から検索、タグから検索が出来るようになりました<br />
						インフォメーションにタグを追加しました<br />
						タグを選択形式で登録できるようにした<br />
						チェックボックスへの「select all」となっているリンクをボタンに変更しました<br />
						名称登録時に性、名のチェックを行うようにしました<br />
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						聴講モード時の講師から見たユーザ一覧表示のデザインを変更しました<br />
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						【PC対応】音声入力が出来るようになりました<br />
						講師ボタン押したらポップアップで講師の情報を表示出来るようにしました<br />
						図書室の棚で使用している「図書室」ボタンをなくしました<br />
						インフォメーションにタグでの選択を出来るようにしました<br />
						図書室資料について、書籍の内容がわかるようにしました<br />
					</div>
					<h3>その他</h3>
					<div class="history_detail">
						ログイン成功時のIDを保持するようにしました<br />
					</div>
					<h3>不具合修正<br /></h3>
					<div class="history_detail">
						管理画面で講師情報変更後、自分の情報が更新されていない不具合の修正<br />
						【管理画面】講座管理・授業管理の検索時、公開期間が意図しない結果となる不具合の修正<br />
						登録確認画面で、デザインが崩れることがあったのを修正<br />
						授業中に低速モードと高速モードを切り替えた後、リロードすると画面遷移時のモードに戻ってしまう不具合の修正<br />
					</div>
				</li>
				<li>
					<h2>Ve1.1.7 -> Ver1.2.0&nbsp;&nbsp;&nbsp;&nbsp;(2012/10/08)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						資料／ビデオ／図書コンテンツ権限管理を講座に紐付いて変更、表示するように変更<br/>
						授業新規登録・変更画面の講師コンボボックスを新規登録時、ログイン中講師名が初期選択表示されるように修正<br/>
						管理画面での受講者の受講講座を必須にするように修正<br/>
						管理画面での講師・受講者変更のパスワードを必須項目にしないように変更<br/>
						管理画面での受講者の受講講座必須扱いにするように変更<br/>
						講座管理と授業管理のタブを分割<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						ブラウザのバージョンチェック機能を実装<br/>
						聴講モードの表示方法変更<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						資料／ビデオ／図書コンテンツを講座単位で表示するように変更<br/>
						PC対応<br/>
						ただし、現状では下記制限があります<br/>
						　・授業中の音声チャットは不可<br/>
						　・図書室資料は画像表示となるため、画像データでのダウンロードが可能となってしまいます<br/>
					</div>
					<h3>その他</h3>
					<div class="history_detail">
						アルフラーニング公式ページの作成<br/>
						<a href="http://alflearning.com/">http://alflearning.com/</a><br/>
					</div>
					<h3>不具合修正</h3>
					<div class="history_detail">
						IE9において、授業遷移した際にブラウザキャッシュが効く事があり、授業変更が正常に行えないことがある問題の修正<br/>
						授業ページへ入室した際、講師側は初回のログインセッションチェックされていなかった問題の修正<br/>
						授業（一般授業）に参加できる受講者数制限に不備があったのを修正<br/>
						授業中のネットワーク状況が問題がある場合の接続性の向上<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.6 -> Ver1.1.7&nbsp;&nbsp;&nbsp;&nbsp;(2012/09/03)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						資料管理の変更・削除可能条件を「資料管理の権限のある講師」に変更<br/>
						受講者保存資料を閲覧可能にした<br/>
						【管理画面】ビデオのアップロード方法変更を変更<br/>
						　これにより、アップロード最大サイズが3GByteまでとなりました<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						講師からのチャットの返信が出来る機能を追加<br/>
						授業登録時の資料をアップロード出来るファイルの最大サイズを100Mbyteまでに増加<br/>
						講師用授業一覧画面から管理画面へのリンクを追加<br/>
						カメラとマイクのデバイス切り替え機能追加<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						図書室のタグ対応<br/>
						授業中の接続の安定性向上<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.5 -> Ver1.1.6&nbsp;&nbsp;&nbsp;&nbsp;(2012/08/10)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						ビデオ授業の閲覧履歴を表示できるように追加<br/>
						管理画面の表示・非表示部分をインタラクティブに動作するように変更<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						講義時間を講義中に延長すると、初期講義終了時間になると講義が終了してしまうのを回避するように変更<br/>
						Webカメラのエラーハンドリングを行うように追加<br/>
						受講者メニュー 一覧画面の個別カスタマイズ機能追加<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						トップページ一覧画面の個別カスタマイズ機能<br/>
						マイページの受講者保存資料について、表示方法変更<br/>
					</div>
					<h3>その他</h3>
					<h3>不具合修正</h3>
					<div class="history_detail">
						自画像映像取得前にノートを拡大した場合に映像が被る不具合の修正<br/>
						生徒授業のホワイトボード画像サイズが1024×748になっていたのを、1024×768にするように修正<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.4 -> Ver1.1.5&nbsp;&nbsp;&nbsp;&nbsp;(2012/07/02)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						トップページのお知らせ一覧について、講師と受講生での表示内容を変えられるように変更<br/>
						授業が開始されているものについて、授業時間を短くする、受講生を減らす、以外は変更を可能とするように仕様変更<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						iPad用マイページ作成<br/>
						授業中の各種イベントを通知するnotification機能の追加<br/>
					</div>
					<h3>不具合修正</h3>
					<div class="history_detail">
						マーカーの図形選択がペン機能のものになっている不具合を修正<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.3 -> Ver1.1.4&nbsp;&nbsp;&nbsp;&nbsp;(2012/05/31)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						ローカルビューワ対応（ダウンロード許可フラグの設置）<br/>
						アップロード可能ドキュメントの対応表を表示するように追加<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						ホワイトボードのテキスト入力を講師授業にも設置<br/>
						ログイン後のエントランス画面のデザイン変更<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						「退出」ボタンを一般メニュー機能に変更（ローカルビューワへの遷移追加）<br/>
						図書室資料、ビデオ授業のiPadへの保存に対応<br/>
						高速モードと低速モードでストリームデータの内容を変える内容をFIX<br/>
						　【高速モード】<br/>
						　・ビットレート：128kbps<br/>
						　・フレームレート：5<br/>
						　【低速モード】<br/>
						　・ビットレート：16kbps<br/>
						　・フレームレート：1<br/>
					</div>
					<h3>不具合修正</h3>
					<div class="history_detail">
						音声録音ボタンをすぐ離す or エラーが返ってきたとき、画面が更新しなくなる不具合の修正<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.2 -> Ver1.1.3&nbsp;&nbsp;&nbsp;&nbsp;(2012/05/11)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						図書室の資料にタグを登録出来るように追加<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						音声の受信が可能になるよう対応<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						音声の送信が可能になるよう対応<br/>
						アプリケーションのバージョンチェックを行うように対応<br/>
						（バージョンが古い場合、バージョンアップ通知画面が表示され、バージョンアップが完了するまでは以降の操作が<br/>
						　行えなくなります）<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.1 -> Ver1.1.2&nbsp;&nbsp;&nbsp;&nbsp;(2012/04/06)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						図書室の資料にタグを登録出来るように追加<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						ホワイトボードに設定した資料が見た目でわかるように対応<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						講師のホワイトボードの拡大を行い続けると、オーバーフローしてしまう不具合の修正<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.1.0 -> Ver1.1.1&nbsp;&nbsp;&nbsp;&nbsp;(2012/03/28)</h2>

					<h3>受講者授業</h3>
					<div class="history_detail">
						授業中に「提出」と「保存」の両方が可能になるように対応<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.0.5 -> Ver1.1.0&nbsp;&nbsp;&nbsp;&nbsp;(2012/02/27)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						授業開始日時を過ぎたら、変更は不可にするように変更<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						授業終了後にダイアログで授業終了の通知を表示するように変更<br/>
						授業に入るボタンを削除<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						ホワイトボード（ノート）にキーボード入力出来るように対応<br/>
						授業終了後にダイアログで授業終了の通知をだすように変更<br/>
						レーザーポインタの動作調整<br/>
					</div>
					<h3>マイページ</h3>
					<div class="history_detail">
						正式対応<br/>
						複数学校所属の受講者ログインの対応<br/>
						授業一覧の項目を「日付・時間・授業・講師・状況・操作」に変更。<br/>
						資料一覧の項目を「資料名・更新日・資料種類 ・操作」に変更。<br/>
						FAQを推奨環境だけにする<br/>
					</div>
					<h3>機能</h3>
					<div class="history_detail">
						同一ユーザの同時ログイン不可対応<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.0.4 -> Ver1.0.5&nbsp;&nbsp;&nbsp;&nbsp;(2012/02/09)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						レポート画面>>生授業において文言変更（【クラスＩＤ】→【授業ID】、【クラス】→【授業】、<br/>
						　【総授業数の単位、クラス】→【授業】）<br/>
						資料画面>>詳細画面・編集画面内の文言変更（【資料管理資料情報の詳細】→【資料情報の詳細】、<br/>
						　【資料管理資料情報の確認】→【資料情報の確認】）<br/>
						各一覧画面において、「詳細」「変更」リンクを削除し「ID」にリンクを付加するように変更 →対象：講座授業<br/>
						　（直近の授業一覧、講座一覧、授業一覧）、受講者一覧、講師一覧、お知らせ一覧、権限一覧<br/>
						レポート画面において文言変更（画面左側、【生授業】→【授業】、「動画】→【ビデオ】）<br/>
						授業資料画面において文言変更（一覧画面と確認画面）（【資料ID】→【ID】）<br/>
						レポート画面の授業情報において文言変更（【授業ID】→【ID】）<br/>
						授業詳細画面において、画面下にあった「教室に入る」リンクを削除<br/>
						学校管理の新規追加。SuperUserのみ使用可能、学校の新規登録＋学校管理者の登録・学校の修正が可能。<br/>
						授業詳細画面より、授業に使用する資料の削除・授業に追加する資料の選択を行う画面を追加。<br/>
						授業詳細画面より、資料を新規登録する画面を追加。（授業新規登録・更新登録後に、遷移するリンクを追加）<br/>
						講師編集画面において、実行権限が「変更できません」の場合に、現在の実行権限名を表示するように変更。<br/>
						授業詳細画面において、修正・削除処理前にログインユーザーの権限確認を追加。<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.0.3 -> Ver1.0.4&nbsp;&nbsp;&nbsp;&nbsp;(2012/01/11)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						トップ画面に、更新履歴の追加<br/>
						学校をまたいだ複数アカウントのログイン対応<br/>
					</div>
					<h3>講師授業</h3>
					<div class="history_detail">
						学校をまたいだ複数アカウントのログイン対応<br/>
					</div>
					<h3>受講者授業</h3>
					<div class="history_detail">
						学校をまたいだ複数アカウントのログイン対応<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.0.2 -> Ver1.0.3&nbsp;&nbsp;&nbsp;&nbsp;(2012/01/06)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						・生徒ログインページのバックグラウンド変更追加<br/>
						・ビデオ授業の登録時、タグを登録出来るように追加<br/>
						・文言変更<br/>
						　　先生->講師<br/>
						　　生徒->受講者<br/>
						　　その他細かい文言修正<br/>
					</div>
					<h3>生徒授業画面</h3>
					<div class="history_detail">
						・ツールバーにレーザーポインタ追加<br/>
						・ビデオ授業のデザイン大幅変更<br/>
						・先生用のホワイトボードをピンチしてホワイトボードを拡大縮小出来るように追加<br/>
						・学校タイプによるログイン画面の変更（管理画面の設定に依存）<br/>
						・文言変更<br/>
						　先生->講師<br/>
						　生徒->受講者<br/>
						　その他細かい文言修正<br/>
					</div>
				</li>
				<li>
					<h2>Ve1.0.1 -> Ver1.0.2&nbsp;&nbsp;&nbsp;&nbsp;(2011/12/16)</h2>

					<h3>管理画面</h3>
					<div class="history_detail">
						・上部のヘッダにログインした先生の権限により表示内容を変更<br/>
						　　admin@test.com -> Super User<br/>
						　　学校管理者 -> ○○○○ 管理者／[学校名] 管理ページ<br/>
						・先生 -> ○○○○ 先生／[学校名] 管理ページ<br/>
						・図書室管理において、登録済み図書の修正機能を追加（ファイル名・説明のみ、図書の修正は不可）<br/>
						・ビデオ管理において、登録済みビデオの修正機能を追加（ファイル名・説明のみ、ビデオの修正は不可）<br/>
						・図書室管理において、詳細画面・修正画面に図書のサムネイル画像を表示するように変更<br/>
					</div>
					<h3>生徒授業画面</h3>
					<div class="history_detail">
						・自分画像のデフォルトサムネイルを設定<br/>
						・自分のノートを送信した後の完了画面を表示する<br/>
						・授業中の先生動画が再生出来無い場合、ブラウザの更新をしないと２度と再生出来無い問題の改修<br/>
						・その他、細かい動作の最適化<br/>
					</div>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
