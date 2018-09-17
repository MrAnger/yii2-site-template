<?php

use yii\db\Migration;

/**
 * Class m180911_101034_page_tree
 */
class m180911_101034_page_tree extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%page_tree}}', [
			'page_id' => $this->integer(10)->unsigned(),
			'tree'    => $this->integer(11)->notNull(),
			'left'    => $this->integer(11)->notNull(),
			'right'   => $this->integer(11)->notNull(),
			'depth'   => $this->integer(11)->notNull(),
		]);

		$this->addPrimaryKey('primary_keys', '{{%page_tree}}', ['page_id']);

		$this->addForeignKey('page_id_to_page', '{{%page_tree}}', ['page_id'], '{{%page}}', ['id'], 'CASCADE', 'CASCADE');

		$this->insert('{{%page_tree}}', [
			'page_id' => 1,
			'tree'    => 1,
			'left'    => 1,
			'right'   => 2,
			'depth'   => 0,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropForeignKey('page_id_to_page', '{{%page_tree}}');

		$this->dropTable('{{%page_tree}}');
	}
}
