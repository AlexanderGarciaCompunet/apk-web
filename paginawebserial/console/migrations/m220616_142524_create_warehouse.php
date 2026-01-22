<?php

use yii\db\Migration;

/**
 * Class m220616_142524_create_warehouse
 */
class m220616_142524_create_warehouse extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%warehouse}}', [
            'name'=> 'Principal',
            'description'=> 'Compunet',
            'company_id'=> 1,
          ]);  
    }
}