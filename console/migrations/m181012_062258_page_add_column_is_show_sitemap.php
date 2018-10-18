<?php

use yii\db\Migration;

/**
 * Class m181012_062258_page_add_column_is_show_sitemap
 */
class m181012_062258_page_add_column_is_show_sitemap extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('{{%page}}', 'is_show_sitemap', $this->smallInteger(1)->unsigned()->notNull()->defaultValue(1)->after('is_enabled'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('{{%page}}', 'is_show_sitemap');
	}
}
