<?php

use yii\db\Migration;


/**
 * Class m190117_123205_insert_useradmin
 */
class m190117_123205_insert_useradmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth_key = Yii::$app->security->generateRandomString();
        $password =Yii::$app->security->generatePasswordHash('password_0');
        $this->insert('user', [
            
            'username' => 'admin',
            'password_hash' => $password,
            'email' => 'jose.vargas@mingenio.com',
            'status' => 10,
            'auth_key' => $auth_key,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insert('auth_item', [
            'name' => 'sysadmin',
            'type'=> 1,
            'description' => 'SuperAdministrador del sistema',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insert('auth_item', [
            'name' => 'permission_admin',
            'type'=> 2,
            'description' => 'Permisos del administrador',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insert('auth_item', [
            'name' => '/*',
            'type'=> 2,
            'description' => 'Permisos del administrador',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->insert('auth_item_child', [
            'parent' => 'sysadmin',
            'child'=> 'permission_admin',
        ]);
        $this->insert('auth_item_child', [
            'parent' => 'permission_admin',
            'child'=> '/*',
        ]);
        $this->insert('auth_assignment', [
            'item_name' => 'sysadmin',
            'user_id'=> 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(
            'auth_assignment',['user_id' => 1]
        );
        $this->delete(
            'auth_item_child',['parent' => '/*']
        );
        $this->delete(
            'auth_item_child',['parent' => 'sysadmin']
        );
        $this->delete(
            'user',['id' => 1]
        );
        $this->delete(
            'auth_item',['name' => 'sysadmin']
        );
        $this->delete(
            'auth_item',['name' => 'permission_admin']
        );
        $this->delete(
            'auth_item',['name' => '/*']
        );
        return true;
    }

}
