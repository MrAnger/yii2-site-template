<?php

use yii\db\Migration;

/**
 * Class m180911_094054_page
 */
class m180911_094054_page extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%page}}', [
			'id'               => $this->primaryKey(10)->unsigned()->notNull(),
			'name'             => $this->string(250)->notNull(),
			'slug'             => $this->string(255)->notNull(),
			'intro'            => $this->text()->null()->defaultValue(null),
			'content'          => $this->text()->null()->defaultValue(null),
			'image_cover_id'   => $this->integer(10)->unsigned()->null()->defaultValue(null),
			'is_enabled'       => $this->smallInteger(1)->unsigned()->notNull()->defaultValue(1),
			'meta_title'       => $this->string(255)->null()->defaultValue(null),
			'meta_description' => $this->string(255)->null()->defaultValue(null),
			'meta_keywords'    => $this->string(255)->null()->defaultValue(null),
			'layout'           => $this->string(255)->null()->defaultValue(null),
			'file_template'    => $this->string(255)->null()->defaultValue(null),
			'params'           => $this->text()->null()->defaultValue(null),
			'published_at'     => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
			'created_at'       => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
			'updated_at'       => $this->timestamp()->notNull()->defaultValue('0000-00-00 00:00:00'),
		]);

		$this->addForeignKey('fk_image_id_to_image', '{{%page}}', ['image_cover_id'], '{{%image}}', ['id'], 'SET NULL', 'CASCADE');

		$this->insert('{{%page}}', [
			'name' => 'This is root page, not visible and usable, created for page tree structure.',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropForeignKey('fk_image_id_to_image', '{{%page}}');

		$this->dropTable('{{%page}}');
	}
}
