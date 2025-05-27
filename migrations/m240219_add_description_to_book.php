<?php

use yii\db\Migration;

/**
 * Class m240219_add_description_to_book
 */
class m240219_add_description_to_book extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('book', 'description', $this->text()->after('title'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('book', 'description');
    }
} 