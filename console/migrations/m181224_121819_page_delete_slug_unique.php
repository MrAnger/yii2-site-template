<?php

use yii\db\Migration;

/**
 * Class m181224_121819_page_delete_slug_unique
 */
class m181224_121819_page_delete_slug_unique extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropIndex('slug_unique', '{{%page}}');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->createIndex('slug_unique', '{{%page}}', ['slug'], true);
	}
}
