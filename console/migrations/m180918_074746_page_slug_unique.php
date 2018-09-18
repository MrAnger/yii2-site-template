<?php

use yii\db\Migration;

/**
 * Class m180918_074746_page_slug_unique
 */
class m180918_074746_page_slug_unique extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('slug_unique', '{{%page}}', ['slug'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('slug_unique', '{{%page}}');
	}
}
