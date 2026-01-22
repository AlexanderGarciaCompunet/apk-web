<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%SystemConfig}}`.
 */
class m200615_230413_create_SystemConfig_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%system_config}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(20),
            'reference' => $this->string(),
            'value' => $this->text(),
            'uri' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->insert('{{%system_config}}', [
            'type'=> 'status',
            'reference'=> 'Datatable',
            'value' => json_encode([
                1 => ['id' => 1, 'label' => 'Eliminado', 'color' =>'badge-danger'],
                9 => ['id' => 9, 'label' => 'Deshabilitado', 'color' =>'badge-primary'],
                10 => ['id' => 10, 'label' => 'Normal', 'color' =>'badge-warning'],
            ])
        ]);

        $this->insert('{{%system_config}}', [
            'type'=> 'status',
            'reference'=> 'documents',
            'value' => json_encode([
                1 => ['id' => 1, 'label' => 'Eliminado', 'color' =>'badge-danger'],
                9 => ['id' => 9, 'label' => 'Deshabilitado', 'color' =>'badge-primary'],
                10 => ['id' => 10, 'label' => 'Normal', 'color' =>'badge-warning'],
                13 => ['id' => 13, 'label' => 'Envío Exitoso', 'color' =>'badge-success'],
            ])
        ]);


        $this->insert('{{%system_config}}', [
            'type'=> 'profile',
            'reference'=> 'genderId',
            'value' => json_encode([
                1 => ['id' => 1, 'label' => 'Masculino'],
                2 => ['id' => 2, 'label' => 'Femenino'],
            ])
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%system_config}}');
    }
}
