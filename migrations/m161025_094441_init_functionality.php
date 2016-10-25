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
            'id' => $this->integer(11)->notNull()->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->defaultValue(null),
            'text' => $this->text()->notNull(),
            'lang_id' => $this->integer(4)->defaultValue(null),
            'date' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'rubric_id' => $this->integer(11)->notNull(),
            'author_id' => $this->integer(11)->notNull(),
            'section_id' => $this->integer(4)->notNull(),
            'stick' => $this->integer(1)->defaultValue(null),
            'content_type' => "enum('text','html','javascript') NOT NULL"
        ], $tableOptions);


        $this->createTable('contentmanager_rubrics', [
            'id' => $this->integer(11)->notNull()->primaryKey(),
            'section_id' => $this->integer(11)->notNull(),
            'name_en' => $this->string(255),
            'name_uk' => $this->string(255),
            'name_ru' => $this->string(255),
        ], $tableOptions);

        $this->createTable('contentmanager_sections', [
            'id' => $this->integer(11)->notNull()->primaryKey(),
            'name' => $this->string(127)
        ], $tableOptions);

        $this->createTable('contentmanager_tags', [
            'id' => $this->integer(11)->notNull()->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer(4)->notNull()
        ], $tableOptions);

        $this->createTable('contentmanager_tags_link', [
            'id' => $this->integer(11)->notNull()->primaryKey(),
            'tag_id' => $this->integer(11)->notNull(),
            'link_id' => $this->integer(11)->notNull(),
            'link_type_id' => $this->integer(4)->notNull()
        ], $tableOptions);

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
