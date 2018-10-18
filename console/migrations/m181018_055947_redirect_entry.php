<?php

use yii\db\Migration;

/**
 * Class m181018_055947_redirect_entry
 */
class m181018_055947_redirect_entry extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%redirect_entry}}', [
			'id'   => $this->primaryKey(10)->unsigned(),
			'from' => $this->string(1000)->notNull(),
			'to'   => $this->string(1000)->notNull(),
			'code' => $this->smallInteger(3)->unsigned()->notNull(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('{{%redirect_entry}}');
	}
}
