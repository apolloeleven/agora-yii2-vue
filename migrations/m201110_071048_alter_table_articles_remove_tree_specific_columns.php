<?php

use yii\db\Migration;

/**
 * Class m201110_071048_alter_table_articles_remove_tree_specific_columns
 */
class m201110_071048_alter_table_articles_remove_tree_specific_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-article-parent_id}}', '{{%articles}}');
        $this->dropIndex('{{%idx-article-parent_id}}', '{{%articles}}');

        $this->dropColumn('{{%articles}}', 'parent_id');
        $this->dropColumn('{{%articles}}', 'is_folder');
        $this->dropColumn('{{%articles}}', 'image_path');
        $this->dropColumn('{{%articles}}', 'lft');
        $this->dropColumn('{{%articles}}', 'rgt');
        $this->dropColumn('{{%articles}}', 'depth');
        $this->dropColumn('{{%articles}}', 'tree');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%articles}}', 'parent_id', $this->integer());
        $this->addColumn('{{%articles}}', 'is_folder', $this->integer(1)->defaultValue(0));
        $this->addColumn('{{%articles}}', 'image_path', $this->string(1024));
        $this->addColumn('{{%articles}}', 'lft', $this->integer());
        $this->addColumn('{{%articles}}', 'rgt', $this->integer());
        $this->addColumn('{{%articles}}', 'depth', $this->integer());
        $this->addColumn('{{%articles}}', 'tree', $this->integer());

        $this->createIndex(
            '{{%idx-article-parent_id}}',
            '{{%articles}}',
            'parent_id'
        );

        $this->addForeignKey(
            '{{%fk-article-parent_id}}',
            '{{%articles}}',
            'parent_id',
            '{{%articles}}',
            'id',
            'CASCADE'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201110_071048_alter_table_articles_remove_tree_specific_columns cannot be reverted.\n";

        return false;
    }
    */
}
