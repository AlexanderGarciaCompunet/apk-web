<?php

use yii\db\Migration;

/**
 * Class m211202_103225_create_reasons_status_table
 */
class m211202_103225_create_reasons_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->insert('{{%system_config}}', [
        'type'=> 'status',
        'reference'=> 'reasonsStatus2',
        'value' => json_encode([
          1 => ['id' => 1, 'label' => 'Bloqueo de documento logístico', 'color' =>'secondary'],
        ])
      ]);

      $this->insert('{{%system_config}}', [
        'type'=> 'status',
        'reference'=> 'reasonsStatus9',
        'value' => json_encode([
          1 => ['id' => 1, 'label' => 'Cantidad inferior confirmada por el proveedor', 'color' =>'secondary'],
          2 => ['id' => 2, 'label' => 'Cantidad inferior artículos defectuosos', 'color' =>'secondary'],
          3 => ['id' => 3, 'label' => 'Cantidad superior confirmada por el proveedor', 'color' =>'secondary'],
        ])
      ]);  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->delete('system_config', 'reference = "reasonsStatus2"');
      $this->delete('system_config', 'reference = "reasonsStatus9"');
    }

}
