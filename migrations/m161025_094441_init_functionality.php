<?php

use yii\db\Migration;

class m161025_094441_init_functionality extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('contentmanager_content', [
            'id' => $this->integer(11)->notNull(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->defaultValue(null),
            'text' => $this->text()->notNull(),
            'lang_id' => $this->integer(4)->defaultValue(null),
            'date' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'rubric_id' => $this->integer(11)->notNull(),
            'author_id' => $this->integer(11)->notNull(),
            'section_id' => $this->integer(4)->notNull(),
            'stick' => $this->integer(1)->defaultValue(null),
            'content_type' => "enum('text','html','javascript') NOT NULL",
            'display_format' => $this->string(32)->null(),
            'published' => $this->boolean()->notNull(),
            'publication_date' => 'timestamp NULL'
        ], $tableOptions);

        $this->addPrimaryKey('contentmanager_content_pk', 'contentmanager_content', 'id');
        $this->alterColumn('contentmanager_content', 'id', $this->integer(11) . ' NOT NULL AUTO_INCREMENT');


        $this->createTable('contentmanager_rubrics', [
            'id' => $this->integer(11)->notNull()->primaryKey(),
            'section_id' => $this->integer(11)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull()
        ], $tableOptions);
        $this->addPrimaryKey('contentmanager_rubrics_pk', 'contentmanager_rubrics', 'id');
        $this->alterColumn('contentmanager_rubrics', 'id', $this->integer(11) . ' NOT NULL AUTO_INCREMENT');


        $this->createTable('contentmanager_sections', [
            'id' => $this->integer(11)->notNull(),
            'name' => $this->string(127)
        ], $tableOptions);
        $this->addPrimaryKey('contentmanager_sections_pk', 'contentmanager_sections', 'id');
        $this->alterColumn('contentmanager_sections', 'id', $this->integer(11) . ' NOT NULL AUTO_INCREMENT');

        $this->createTable('contentmanager_tags', [
            'id' => $this->integer(11)->notNull(),
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer(4)->notNull()
        ], $tableOptions);
        $this->addPrimaryKey('contentmanager_tags_pk', 'contentmanager_tags', 'id');
        $this->alterColumn('contentmanager_tags', 'id', $this->integer(11) . ' NOT NULL AUTO_INCREMENT');

        $this->createTable('contentmanager_tags_link', [
            'id' => $this->integer(11)->notNull(),
            'tag_id' => $this->integer(11)->notNull(),
            'link_id' => $this->integer(11)->notNull(),
            'link_type_id' => $this->integer(4)->notNull()
        ], $tableOptions);
        $this->addPrimaryKey('contentmanager_tags_link_pk', 'contentmanager_tags_link', 'id');
        $this->alterColumn('contentmanager_tags_link', 'id', $this->integer(11) . ' NOT NULL AUTO_INCREMENT');

    }

    public function down()
    {
        $this->dropTable('contentmanager_content');
        $this->dropTable('contentmanager_rubrics');
        $this->dropTable('contentmanager_sections');
        $this->dropTable('contentmanager_tags');
        $this->dropTable('contentmanager_tags_link');
    }
}
