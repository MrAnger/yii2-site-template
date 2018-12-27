<?php

use yii\db\Migration;

/**
 * Class m181225_073105_block_content_nullable
 */
class m181225_073105_block_content_nullable extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('{{%block}}', 'content', $this->text()->null()->defaultValue(null));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('{{%block}}', 'content', $this->text()->notNull());
	}
}
