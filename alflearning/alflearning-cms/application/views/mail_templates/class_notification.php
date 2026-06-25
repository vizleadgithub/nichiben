このメールは、ALFラーニングから自動送信にて送られています

<?= htmlspecialchars( $student['student_name'], ENT_QUOTES, 'UTF-8') ?>さん
ALFラーニングをご利用いただきありがとうございます。

<?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?>講師による授業
「<?= htmlspecialchars( $class['class_name'], ENT_QUOTES, 'UTF-8') ?>」
<? if($nextStr == 'week'): ?>
が１週間後に控えておりますので、ご連絡させて頂きます
<? elseif($nextStr == 'day'): ?>
が明日行われますので、、ご連絡させて頂きます
<? elseif($nextStr == 'hour'): ?>
が1時間後に開催されますので、ご連絡させて頂きます
<? endif; ?>

――――――――――――――――――――――――――――――――
●授業名
<?= htmlspecialchars( $class['class_name'], ENT_QUOTES, 'UTF-8') ?>


●開催予定日時
<?= date('m月d日 H時i分', strtotime($class['class_open'])); ?>から<?= Sec2Disp((strtotime($class['class_close']) - strtotime($class['class_open'])), array('dd' => false)); ?>


●授業内容
<?= htmlspecialchars( $class['class_caption'], ENT_QUOTES, 'UTF-8') ?>

――――――――――――――――――――――――――――――――


お持ちのiPadを利用して、授業へのご参加をよろしくお願いいたします

★ご注意下さい★
※このメールは、ご指定頂いたメールアドレス宛に自動的に送信されています。
※このメールに心あたりが無い場合には、お手数ですが、下記のお問い合わせ先
　まで、ご連絡頂けますようお願い致します。

------------------------------------------------------------------
お問い合わせ先
メール: support@alfredcore.com
ウェブ: http://alfredcore.com/alflearning/
------------------------------------------------------------------
