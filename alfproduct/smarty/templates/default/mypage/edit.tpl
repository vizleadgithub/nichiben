<!--{include file='mypage/side_menu.tpl'}-->
<div style="float:right;width:720px;">
		<div id="single_title" style="margin-bottom:20px;">
			<h3>登録情報変更</h3>
			<h6>edit</h6>
		</div>



	<form name="form1" action="edit.php" method="post">
	<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
	<input type="hidden" name="act" value="confirm" />


	<div style="border: solid 1px #47a6d4;padding: 1px;">
	<table style="width:100%;" class="member_table">
		<!--{* ====================================================================== *}-->
		<tr>
			<!--{if isset($err_msg.name1)}--><!--{assign var=name1_style value=$err_style}--><!--{/if}-->
			<!--{if isset($err_msg.name2)}--><!--{assign var=name2_style value=$err_style}--><!--{/if}-->
			<th  style="width:230px;background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">氏名<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<table><tr>
					<td>姓<input type="text" name="name1" id="name1" value="<!--{$arr_input.name1|escape}-->" <!--{$name1_style}--> /></td>
					<td>&nbsp;</td>
					<td>名<input type="text" name="name2" id="name2" value="<!--{$arr_input.name2|escape}-->" <!--{$name2_style}--> /></td>
				</tr></table>
				<!--{if isset($err_msg.name1)}-->
				<br /><span style="color:red;"><!--{$err_msg.name1|escape}--></span>
				<!--{/if}-->
				<!--{if isset($err_msg.name2)}-->
				<br /><span style="color:red;"><!--{$err_msg.name2|escape}--></span>
				<!--{/if}-->
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<tr>
			<!--{if isset($err_msg.kana1)}--><!--{assign var=kana1_style value=$err_style}--><!--{/if}-->
			<!--{if isset($err_msg.kana2)}--><!--{assign var=kana2_style value=$err_style}--><!--{/if}-->
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">フリガナ<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<table><tr>
					<td>セイ<input type="text" name="kana1" id="kana1" value="<!--{$arr_input.kana1|escape}-->" <!--{$kana1_style}--> /></td>
					<td>&nbsp;</td>
					<td>メイ<input type="text" name="kana2" id="kana2" value="<!--{$arr_input.kana2|escape}-->" <!--{$kana2_style}--> /></td>
				</tr></table>
				<!--{if isset($err_msg.kana1)}-->
				<br /><span style="color:red;"><!--{$err_msg.kana1|escape}--></span>
				<!--{/if}-->
				<!--{if isset($err_msg.kana2)}-->
				<br /><span style="color:red;"><!--{$err_msg.kana2|escape}--></span>
				<!--{/if}-->
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
<!--{*
		<tr>
			<!--{if isset($err_msg.zip1)}--><!--{assign var=zip1_style value=$err_style}--><!--{/if}-->
			<!--{if isset($err_msg.zip2)}--><!--{assign var=zip2_style value=$err_style}--><!--{/if}-->
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">郵便番号</th>
			<td style="padding:5px;">
				<table><tr>
					<td><input type="text" name="zip1" id="zip1" value="<!--{$arr_input.zip1|escape}-->" <!--{$zip1_style}--> size="5"/></td>
					<td>&nbsp;-&nbsp;</td>
					<td><input type="text" name="zip2" id="zip2" value="<!--{$arr_input.zip2|escape}-->" <!--{$zip2_style}--> size="7" /></td>
					<td><a target="_blank" href="http://search.post.japanpost.jp/zipcode/" rel="noopener noreferrer"><span>郵便番号検索</span></a></td>
				</tr></table>
				<!--{if isset($err_msg.zip)}-->
				<br /><span style="color:red;"><!--{$err_msg.zip|escape}--></span>
				<!--{/if}-->
				<a target="_blank" onclick="fnCallAddress('input_zip.php', 'zip1', 'zip2', 'pref_id', 'address1' ); return false;" href="javascript:void(0);" rel="noopener noreferrer"><img width="120" height="24" alt="住所自動入力" src="/img/btn_zip.jpg" style="margin-top: 10px;"></a>
				<span> 郵便番号を入力後、クリックしてください。</span>
			</td>
		</tr>
		<script type="text/javascript">
			//<![CDATA[
				// 親ウィンドウの存在確認.
				function fnIsopener() {
					var ua = navigator.userAgent;
					if( !!window.opener ) {
						if( ua.indexOf('MSIE 4')!=-1 && ua.indexOf('Win')!=-1 ) {
							return !window.opener.closed;
						} else {
							return typeof window.opener.document == 'object';
						}
					} else {
						return false;
					}
				}
				// 郵便番号入力呼び出し.
				function fnCallAddress(php_url, tagname1, tagname2, input1, input2) {
					zip1 = document.form1[tagname1].value;
					zip2 = document.form1[tagname2].value;
					if(zip1.length == 3 && zip2.length == 4) {
						url = php_url + "?zip1=" + zip1 + "&zip2=" + zip2 + "&input1=" + input1 + "&input2=" + input2;
						window.open(url,"nomenu","width=500,height=350,scrollbars=yes,resizable=yes,toolbar=no,location=no,directories=no,status=no");
					} else {
						alert("郵便番号を正しく入力して下さい。");
					}
				}
				// 郵便番号から検索した住所を渡す.
				function fnPutAddress(input1, input2) {
					// 親ウィンドウの存在確認。.
					if(fnIsopener()) {
						if(document.form1['state'].value != "") {
							// 項目に値を入力する.
							state_id = document.form1['state'].value;
							town = document.form1['city'].value + document.form1['town'].value;
							window.opener.document.form1[input1].selectedIndex = state_id;
							window.opener.document.form1[input2].value = town;
						}
					} else {
						window.close();
					}
				}
			//]]>
		</script>
*}-->
		<!--{* ====================================================================== *}-->
		<tr>
			<!--{if isset($err_msg.pref_id)}--><!--{assign var=pref_id_style value=$err_style}--><!--{/if}-->
			<!--{if isset($err_msg.address1)}--><!--{assign var=address1_style value=$err_style}--><!--{/if}-->
			<!--{if isset($err_msg.address2)}--><!--{assign var=address2_style value=$err_style}--><!--{/if}-->
			<!--{if isset($err_msg.address3)}--><!--{assign var=address3_style value=$err_style}--><!--{/if}-->
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">住所</th>
			<td style="padding:5px;">
				<div id="japan">
					<table><tr>
						<th>都道府県<span style="color:red;">※必須</span></th>
						<td>
							<select name="pref_id" id="pref_id" <!--{$pref_id_style}-->>
							<option value="">選択してください</option> 
							<!--{foreach name=pref_id from=$mtb_pref item=val}-->
							<option value="<!--{$val.id|escape}-->" <!--{if $smarty.foreach.pref_id.iteration == $arr_input.pref_id}-->selected<!--{/if}-->><!--{$val.name|escape}--></option>
							<!--{/foreach}-->
							</select>
							<!--{if isset($err_msg.pref_id)}-->
							<br /><span style="color:red;"><!--{$err_msg.pref_id|escape}--></span>
							<!--{/if}-->
						</td>
					</tr><tr>
						<th>市区町村<span style="color:red;"></span></th>
						<td>
							<input type="text" name="address1" id="address1" value="<!--{$arr_input.address1|escape}-->" <!--{$address1_style}--> />
							<!--{if isset($err_msg.address1)}-->
							<br /><span style="color:red;"><!--{$err_msg.address1|escape}--></span>
							<!--{/if}-->
						</td>
					</tr><tr>
						<th>番地<span style="color:red;"></span></th>
						<td>
							<input type="text" name="address2" id="address2" value="<!--{$arr_input.address2|escape}-->" <!--{$address2_style}--> />
							<!--{if isset($err_msg.address2)}-->
							<br /><span style="color:red;"><!--{$err_msg.address2|escape}--></span>
							<!--{/if}-->
						</td>
					</tr><tr>
						<th>ビル名・マンション名</th>
						<td>
							<input type="text" name="address3" id="address3" value="<!--{$arr_input.address3|escape}-->" <!--{$address3_style}--> />
							<!--{if isset($err_msg.address3)}-->
							<br /><span style="color:red;"><!--{$err_msg.address3|escape}--></span>
							<!--{/if}-->
						</td>
					</tr></table>
				</div>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.email)}--><!--{assign var=email_style value=$err_style}--><!--{/if}-->
		<!--{if isset($err_msg.email_conf)}--><!--{assign var=email_conf_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">メールアドレス</th>
			<td style="padding:5px;">
				<table><tr>
					<td>
						<input type="text" name="email" id="email" value="<!--{$arr_input.email|escape}-->" <!--{$email_style}--> /><br />
					</td>
				</tr><tr>
					<td>
						<input type="text" name="email_conf" id="email_conf" value="<!--{$arr_input.email_conf|escape}-->" <!--{$email_conf_style}--> /><br />
					</td>
				</tr><tr>
					<td>
						確認のため2度入力してください。
						<!--{if isset($err_msg.email)}-->
							<br /><span style="color:red;"><!--{$err_msg.email|escape}--></span>
						<!--{/if}-->
						<!--{if isset($err_msg.email_conf)}-->
							<br /><span style="color:red;"><!--{$err_msg.email_conf|escape}--></span>
						<!--{/if}-->
					</td>
				</tr></table>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.password)}--><!--{assign var=password_style value=$err_style}--><!--{/if}-->
		<!--{if isset($err_msg.password_conf)}--><!--{assign var=password_conf_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワード<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<table><tr>
					<td>
						<input type="password" name="password" id="password" value="<!--{$arr_input.password|escape}-->" <!--{$password_style}--> />
					</td>
				</tr><tr>
					<td>
						半角英数字4～10文字でお願いします。(記号不可)
						<!--{if isset($err_msg.password)}-->
						<br /><span style="color:red;"><!--{$err_msg.password|escape}--></span>
						<!--{/if}-->
					</td>
				</tr><tr>
					<td>
						<input type="password" name="password_conf" id="password_conf" value="<!--{$arr_input.password_conf|escape}-->" <!--{$password_conf_style}--> />
					</td>
				</tr><tr>
					<td>
						確認のため2度入力してください。
						<!--{if isset($err_msg.password_conf)}-->
						<br /><span style="color:red;"><!--{$err_msg.password_conf|escape}--></span>
						<!--{/if}-->
					</td>
				</tr></table>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.password_question)}--><!--{assign var=password_question_style value=$err_style}--><!--{/if}-->
		<!--{if isset($err_msg.password_answer)}--><!--{assign var=password_answer_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<table><tr>
					<th>質問</th>
					<td>
						<select name="password_question" id="password_question" <!--{$password_question_style}-->>
							<option value="">選択してください</option> 
						<!--{foreach name=password_question from=$mtb_password_question item=val}-->
						<option value="<!--{$val.id|escape}-->" <!--{if $smarty.foreach.password_question.iteration == $arr_input.password_question}-->selected<!--{/if}-->><!--{$val.name|escape}--></option>
						<!--{/foreach}-->
						</select>
						<!--{if isset($err_msg.password_question)}-->
						<br /><span style="color:red;"><!--{$err_msg.password_question|escape}--></span>
						<!--{/if}-->
					</td>
				</tr><tr>
					<th>答え</th>
					<td>
						<input type="text" name="password_answer" id="password_answer" value="<!--{$arr_input.password_answer|escape}-->" <!--{$password_answer_style}--> />変更する場合は入力してください。<br>
						<!--{if isset($err_msg.password_answer)}-->
						<br /><span style="color:red;"><!--{$err_msg.password_answer|escape}--></span>
						<!--{/if}-->
					</td>
				</tr></table>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">職業</th>
			<td style="padding:5px;">
				<select name="job" id="job">
				<option value="">選択してください</option> 
				<!--{foreach name=job from=$mtb_job item=val}-->
				<option value="<!--{$val.id|escape}-->" <!--{if $smarty.foreach.job.iteration == $arr_input.job}-->selected<!--{/if}-->><!--{$val.name|escape}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">業種</th>
			<td style="padding:5px;">
				<select name="job_type" id="job_type">
				<option value="">選択してください</option> 
				<!--{foreach name=job_type from=$mtb_job_type item=val}-->
				<option value="<!--{$val.id|escape}-->" <!--{if $smarty.foreach.job_type.iteration == $arr_input.job_type}-->selected<!--{/if}-->><!--{$val.name|escape}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">学校名</th>
			<td style="padding:5px;">
				<input type="text" name="school_name" id="school_name" value="<!--{$arr_input.school_name|escape}-->" />
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">学年</th>
			<td style="padding:5px;">
				<select name="school_grade" id="school_grade" <!--{$school_grade_style}-->>
				<option value="">選択してください</option> 
				<!--{foreach name=school_grade from=$mtb_school_grade item=val}-->
				<option value="<!--{$val.id|escape}-->" <!--{if $smarty.foreach.school_grade.iteration == $arr_input.school_grade}-->selected<!--{/if}-->><!--{$val.name|escape}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.age)}--><!--{assign var=age_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">年代<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<select name="age" id="age" <!--{$age_style}-->>
				<option value="">選択してください</option> 
				<!--{foreach name=age from=$mtb_age item=val}-->
				<option value="<!--{$val.id|escape}-->" <!--{if $smarty.foreach.age.iteration == $arr_input.age}-->selected<!--{/if}-->><!--{$val.name|escape}--></option>
				<!--{/foreach}-->
				</select>
				<!--{if isset($err_msg.age)}-->
				<br /><span style="color:red;"><!--{$err_msg.age|escape}--></span>
				<!--{/if}-->
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.gender)}--><!--{assign var=gender_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">性別<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<!--{foreach from=$mtb_gender item=val}-->
				<label <!--{$gender_style}-->><input type="radio" name="gender" value="<!--{$val.id|escape}-->" <!--{if $val.id == $arr_input.gender}-->checked<!--{/if}--> /><!--{$val.name|escape}--></label>
				<!--{/foreach}-->
				<!--{if isset($err_msg.gender)}-->
				<!--{assign var=gender_style value=$err_style}-->
				<br /><span style="color:red;"><!--{$err_msg.gender|escape}--></span>
				<!--{/if}-->

			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">伊藤塾塾生番号</th>
			<td style="padding:5px;">
				<input type="text" name="student_no" id="student_no" value="<!--{$arr_input.student_no|escape}-->" />
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
	</table>
	</div>

	<hr>

	<div style="border: solid 1px #47a6d4;padding: 1px;">
	<table style="width:100%;" class="member_table">
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.mail_magazine_flag)}--><!--{assign var=mail_magazine_flag_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="width:230px;background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">メールマガジンについて<span style="color:red;">※必須</span></th>
			<td style="padding:5px;">
				<label <!--{$mail_magazine_flag_style}-->><input type="radio" name="mail_magazine_flag" value="1" <!--{if $arr_input.mail_magazine_flag==1}-->checked<!--{/if}--> />受け取る</label>&nbsp;
				<label <!--{$mail_magazine_flag_style}-->><input type="radio" name="mail_magazine_flag" value="0" <!--{if $arr_input.mail_magazine_flag==0}-->checked<!--{/if}--> />受け取らない</label>
				<!--{if isset($err_msg.mail_magazine_flag)}-->
				<br /><span style="color:red;"><!--{$err_msg.mail_magazine_flag|escape}--></span>
				<!--{/if}-->
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
		<!--{if isset($err_msg.mail_magazine)}--><!--{assign var=mail_magazine_style value=$err_style}--><!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">受け取るメールマガジン</th>
			<td style="padding:5px;">
			<!--{foreach from=$mtb_mailmagazine_category item=val}-->
				<!--{assign var=checked value=''}-->
				<!--{foreach name=loop from=$arr_input.arr_mail_magazine item=val2}-->
					<!--{if $val2 == $val.id}-->
						<!--{assign var=checked value='checked'}-->
					<!--{/if}-->
				<!--{/foreach}-->
				<div style="width:30%;float:left;"><label><input type="checkbox" name="mail_magazine[]" value="<!--{$val.id|escape}-->" <!--{$checked}--> /><!--{$val.name|escape}--></label></div>
			<!--{/foreach}-->
			</td>
		</tr>
		<!--{* ====================================================================== *}-->
	</table>
	</div>

	<hr>

	<table style="width:100%;">
		<tr>
			<td style="text-align:center;">
				<input type="image" src="/img/btn/conf_btn.gif" value="確認" />
			</td>
		</tr>
	</table>
	</form>

</div>