<?php

use yii\db\Migration;

/**
 * Class m210919_003459_insert_data_config_table
 */
class m210919_003459_insert_data_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->insert('{{%system_config}}', [
        'type'=> 'status',
        'reference'=> 'orders',
        'value' => json_encode([
          9 => ['id' => 9, 'label' => 'Deshabilitado', 'color' =>'danger'],
          10 => ['id' => 10, 'label' => 'Recibido', 'color' =>'primary'],
          11 => ['id' => 11, 'label' => 'En Validación', 'color' =>'primary'],
          12 => ['id' => 12, 'label' => 'Inicial', 'color' =>'success'],
          13 => ['id' => 13, 'label' => 'Sin Posiciones', 'color' =>'warning'],
          14 => ['id' => 14, 'label' => 'En Proceso', 'color' =>'success'],
          15 => ['id' => 15, 'label' => 'Procesado', 'color' =>'warning'],
          16 => ['id' => 16, 'label' => 'Bloqueo por Configuración', 'color' =>'secondary'],
          17 => ['id' => 17, 'label' => 'Cerrado', 'color' =>'danger'],
          18 => ['id' => 18, 'label' => 'Bloqueo Manual', 'color' =>'secondary'],
          19 => ['id' => 19, 'label' => 'Bloqueo por Ajuste de Cantidades', 'color' =>'secondary'],
        ])
      ]);

      $this->insert('{{%system_config}}', [
        'type'=> 'status',
        'reference'=> 'materials',
        'value' => json_encode([
          10 => ['id' => 10, 'label' => 'Activo', 'color' =>'success'],
          11 => ['id' => 11, 'label' => 'En proceso', 'color' =>'warning'],
          12 => ['id' => 12, 'label' => 'Finalizado', 'color' =>'success'],
          14 => ['id' => 14, 'label' => 'Bloqueado', 'color' =>'secondary'],
        ])
      ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->delete('system_config', 'type = "status" and reference = "orders"');
      $this->delete('system_config', 'type = "status" and reference = "materials"');

    }

}
