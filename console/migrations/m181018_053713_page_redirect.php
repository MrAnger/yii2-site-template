<?php

use yii\db\Migration;

/**
 * Class m181018_053713_page_redirect
 */
class m181018_053713_page_redirect extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('{{%page}}', 'redirect_url', $this->string(1000)->null()->defaultValue(null)->after('published_at'));
		$this->addColumn('{{%page}}', 'redirect_code', $this->smallInteger(3)->unsigned()->null()->defaultValue(null));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('{{%page}}', 'redirect_url');
		$this->dropColumn('{{%page}}', 'redirect_code');
	}
}
