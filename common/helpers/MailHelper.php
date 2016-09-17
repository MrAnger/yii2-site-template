<?php

namespace common\helpers;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class MailHelper extends Model {
	/**
	 * Sends an email to the specified email address using the information collected by this model.
	 *
	 * @param string $from
	 * @param string $to
	 * @param string $subject
	 * @param string $body
	 *
	 * @return boolean whether the email was sent.
	 */
	public static function sendMailWithText($from, $to, $subject, $body) {
		$mailer = Yii::$app->mailer;

		if ($from === null) {
			$from = ArrayHelper::getValue(Yii::$app->params, 'contactEmailSource', 'no-reply@example.com');
		}

		return $mailer->compose()
			->setTo($to)
			->setFrom($from)
			->setSubject($subject)
			->setTextBody($body)
			->send();
	}

	/**
	 * @param string $from
	 * @param string $to
	 * @param string $subject
	 * @param string $view
	 * @param array $params
	 *
	 * @return bool
	 */
	public static function sendMail($from, $to, $subject, $view, $params = []) {
		$mailer = Yii::$app->mailer;

		if ($from === null) {
			$from = ArrayHelper::getValue(Yii::$app->params, 'contactEmailSource', 'no-reply@example.com');
		}

		return $mailer->compose($view, $params)
			->setTo($to)
			->setFrom($from)
			->setSubject($subject)
			->send();
	}
}
