<?php

use yii\db\Schema;
use yii\db\Migration;

class m180611_053158_image extends Migration {

	public function init() {
		$this->db = 'db';
		parent::init();
	}

	public function safeUp() {
		$tableOptions = 'ENGINE=InnoDB';

		$this->createTable('{{%image}}', [
			'id'          => $this->primaryKey(10)->unsigned(),
			'file'        => $this->string(1024)->notNull(),
			'title'       => $this->string(255)->null()->defaultValue(null),
			'description' => $this->text()->null()->defaultValue(null),
			'created_at'  => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
			'updated_at'  => $this->timestamp()->notNull()->defaultValue('0000-00-00 00:00:00'),
		], $tableOptions);

	}

	public function safeDown() {
		$this->dropTable('{{%image}}');
	}
}
