<?php

use yii\db\Migration;

/**
 * Class m181013_072529_feedback_entry
 */
class m181013_072529_feedback_entry extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%feedback_entry}}', [
			'id'         => $this->primaryKey(10)->unsigned(),
			'is_viewed'  => $this->smallInteger(1)->unsigned()->notNull()->defaultValue(0),
			'text'       => $this->text()->notNull(),
			'created_at' => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('{{%feedback_entry}}');
	}
}
