<?php

use yii\db\Migration;

/**
 * Class m220616_142524_create_company
 */
class m220616_142524_create_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%company}}', [
            'name'=> 'Compunet',
            'description'=> 'Compunet - Integradores de tecnología',
          ]);  
    }
}
