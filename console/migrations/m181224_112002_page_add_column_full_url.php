<?php

use yii\db\Migration;

/**
 * Class m181224_112002_page_add_column_full_url
 */
class m181224_112002_page_add_column_full_url extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('{{%page}}', 'full_url', $this->string(5000)->null()->defaultValue(null)->after('slug'));

		$this->createIndex('full_url', '{{%page}}', 'full_url');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('{{%page}}', 'full_url');
	}
}
