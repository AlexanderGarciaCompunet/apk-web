<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document_position}}`.
 */
class m210930_224440_add_real_amount_column_and_pivot_amount_column_to_document_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%document_position}}', 'real_amount', $this->integer()->defaultValue(0));
        $this->addColumn('{{%document_position}}', 'pivot_amount', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%document_position}}', 'real_amount');
        $this->dropColumn('{{%document_position}}', 'pivot_amount');
    }
}
