#!/bin/bash

# 日弁連様環境（データホテル ex-cloud）向け、マウント状態チェックツール
#   マウント先の指定ファイルの存在を確認し、存在が確認できない場合はマウント系を再起動し、その旨を指定メールアドレスに送信する。

# 使用前にマウントしている「/alflearning-data/」配下に「mountcheck_confirmfile.txt」ファイルを新規作成する（root:root 0777）

# 使用方法（手動、root ユーザ）
# シェルのあるディレクトリに移動し実施。引数必須。各サーバー毎に異なる
# [web1]  sh mountcheck.sh web1
# [web22]  sh mountcheck.sh web2

# 変数設定（フルパス確認ファイル）
CHECK_FILE_PATH=/alflearning-data/mountcheck_confirmfile.txt

# 変数設定（日時、タイムスタンプ）
TODAY_TIME=$(date "+%Y-%m-%d %H:%M:%S")
TIMESTAMP=$(date "+%s")

# メール関連設定
# 送信元メールアドレス
FROM_MAIL_ADDRESS=nichiben@edutec.co.jp

# 送信先メールアドレス（半角スペース区切り）
TO_MAIL_ADDRESS="sano@vizlead.com aizawa@vizlead.com viz.sano@gmail.com"

# 引数がない場合は終了（引数の個数で判定） ---------- ---------- ---------- ---------- ----------
if [ $# -eq 0 ]; then
  # 引数（Argument）が見つかりません。
  echo -e "[$TODAY_TIME][Warning] Not Found Argument.\n"
  exit 0
fi

# ファイル存在確認 ---------- ---------- ---------- ---------- ----------
if [[ -f $CHECK_FILE_PATH ]]; then
  # 確認ファイルが見つかりました。マウントは再起動しません。
  echo -e "[$TODAY_TIME] Found Confirm File. Not Restart mount.\n"
  
else
  # 確認ファイルが見つかりません。マウントを再起動します。
  echo -e "[$TODAY_TIME] Not Found Confirm File. Restart mount.\n"
  
  # メール本文初期値
  MAIL_BODY="-----\n"
  
  # 第１引数を取得
  VAL_SERVER_NAME=$1
  
  # 引数により処理を切り換え
  if [ "$VAL_SERVER_NAME" = "web1" ] || [ "$VAL_SERVER_NAME" = "web2" ]; then
    # マウント先（web）再起動
    
    MAIL_BODY+="<$VAL_SERVER_NAME>"
    MAIL_BODY+="\n-----\n"
    
    # データホテル（ex-cloud） web（マウント先）マウント再設定
    MAIL_BODY+="[portmap restart]\n"
    MAIL_BODY+=`/etc/init.d/portmap restart`
    MAIL_BODY+="\n-----\n"
    MAIL_BODY+="[netfs restart]\n"
    MAIL_BODY+=`/etc/init.d/netfs restart`
    MAIL_BODY+="\n-----\n"
      
  elif [ "$VAL_SERVER_NAME" = "db01" ] ; then
    # マウント元（db）再起動
    
    MAIL_BODY+="<$VAL_SERVER_NAME>"
    MAIL_BODY+="\n-----\n"
    
    # データホテル（ex-cloud） db（マウント元）マウント再設定
    MAIL_BODY+="[portmap restart]\n"
    MAIL_BODY+=`/etc/rc.d/init.d/portmap restart`
    MAIL_BODY+="\n-----\n"
    MAIL_BODY+="[unfsd restart]\n"
    MAIL_BODY+=`/etc/init.d/unfsd restart`
    MAIL_BODY+="\n-----\n"
  elif [ "$VAL_SERVER_NAME" = "stg" ] ; then
    # マウント先（web）再起動
    
    MAIL_BODY+="<$VAL_SERVER_NAME>"
    MAIL_BODY+="\n-----\n"
    
    # データホテル（ex-cloud） web（マウント先）マウント再設定
    MAIL_BODY+="[portmap restart]\n"
    MAIL_BODY+=`/etc/init.d/portmap restart`
    MAIL_BODY+="\n-----\n"
    MAIL_BODY+="[netfs restart]\n"
    MAIL_BODY+=`/etc/init.d/netfs restart`
    MAIL_BODY+="\n-----\n"
  else
    # 引数（Argument）が不明な値です。確認して下さい。
    MAIL_BODY+="<$VAL_SERVER_NAME>"
    MAIL_BODY+="\n-----\n"
    MAIL_BODY+="Unknown Argument. Please check."
    MAIL_BODY+="\n-----\n"
  fi
  
  # 変数設定（メール本文＋日時）
  MAIL_BODY+="\n$TODAY_TIME"
  
  # 変数設定（メールタイトル）
  MAIL_SUBJECT="[nichiben][$VAL_SERVER_NAME] mount restart"
  
  
  
  # メール内容出力（確認用）
  #echo -e "SUBJECT : $MAIL_SUBJECT\n\n$MAIL_BODY"
  
  # メール送信（AWS）
  #echo -e "$MAIL_BODY" | mail -s "$MAIL_SUBJECT" -r $FROM_MAIL_ADDRESS $TO_MAIL_ADDRESS
  
  # メール送信（データホテル ex-cloud）
  echo -e "$MAIL_BODY" | mail -s "$MAIL_SUBJECT" $TO_MAIL_ADDRESS -- -f $FROM_MAIL_ADDRESS
  
fi

