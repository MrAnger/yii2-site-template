<?php

use yii\db\Schema;
use yii\db\Migration;

class m180611_053044_block extends Migration {

	public function init() {
		$this->db = 'db';
		parent::init();
	}

	public function safeUp() {
		$tableOptions = 'ENGINE=InnoDB';

		$this->createTable('{{%block}}', [
			'id'         => $this->primaryKey(10)->unsigned(),
			'name'       => $this->string(255)->notNull(),
			'code'       => $this->string(255)->notNull(),
			'content'    => $this->text()->notNull(),
			'params'     => $this->text()->null()->defaultValue(null),
			'created_at' => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
			'updated_at' => $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
		], $tableOptions);

		$this->createIndex('code', '{{%block}}', ['code'], true);
	}

	public function safeDown() {
		$this->dropTable('{{%block}}');
	}
}
