<?php

use yii\db\Migration;

/**
 * Class m180927_110941_page_image_gallery
 */
class m180927_110941_page_image_gallery extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%page_image_gallery}}', [
			'id'         => $this->primaryKey(10)->unsigned(),
			'page_id'    => $this->integer(10)->unsigned()->notNull(),
			'image_id'   => $this->integer(10)->unsigned()->notNull(),
			'sort_order' => $this->smallInteger()->notNull()->defaultValue(1),
		]);

		$this->addForeignKey('FK_page_id_to_page', '{{%page_image_gallery}}', ['page_id'], '{{%page}}', ['id'], 'CASCADE', 'CASCADE');
		$this->addForeignKey('FK_image_id_to_image_table', '{{%page_image_gallery}}', ['image_id'], '{{%image}}', ['id'], 'CASCADE', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropForeignKey('FK_page_id_to_page', '{{%page_image_gallery}}');
		$this->dropForeignKey('FK_image_id_to_image_table', '{{%page_image_gallery}}');

		$this->dropTable('{{%page_image_gallery}}');
	}
}
