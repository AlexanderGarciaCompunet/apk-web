<?php

use yii\db\Migration;

/**
 * Migración para crear usuario demo
 * Usuario: demo
 * Contraseña: demo123
 */
class m260121_130000_create_demo_user extends Migration
{
    public function safeUp()
    {
        $time = time();

        // Crear usuario demo
        // Contraseña: demo123
        $passwordHash = Yii::$app->security->generatePasswordHash('demo123');
        $authKey = Yii::$app->security->generateRandomString();

        // Verificar si el usuario ya existe
        $exists = (new \yii\db\Query())
            ->from('{{%user}}')
            ->where(['username' => 'demo'])
            ->exists();

        if (!$exists) {
            $this->insert('{{%user}}', [
                'username' => 'demo',
                'email' => 'demo@compunet.com',
                'password_hash' => $passwordHash,
                'auth_key' => $authKey,
                'status' => 10, // STATUS_ACTIVE
                'created_at' => $time,
                'updated_at' => $time,
            ]);

            $userId = $this->db->getLastInsertID();

            // Crear perfil del usuario
            $this->insert('{{%user_profile}}', [
                'user_id' => $userId,
                'name' => 'Usuario',
                'lastname' => 'Demo COMPUNET',
            ]);

            // Asignar rol de admin si existe
            $adminRole = (new \yii\db\Query())
                ->from('{{%auth_item}}')
                ->where(['name' => 'admin', 'type' => 1])
                ->one();

            if ($adminRole) {
                $this->insert('{{%auth_assignment}}', [
                    'item_name' => 'admin',
                    'user_id' => (string) $userId,
                    'created_at' => $time,
                ]);
            }

            echo "Usuario demo creado exitosamente.\n";
            echo "Usuario: demo\n";
            echo "Contraseña: demo123\n";
        } else {
            // Actualizar la contraseña del usuario existente
            $this->update('{{%user}}', [
                'password_hash' => $passwordHash,
                'status' => 10,
                'updated_at' => $time,
            ], ['username' => 'demo']);

            echo "Usuario demo actualizado.\n";
            echo "Usuario: demo\n";
            echo "Contraseña: demo123\n";
        }
    }

    public function safeDown()
    {
        // Obtener ID del usuario demo
        $user = (new \yii\db\Query())
            ->from('{{%user}}')
            ->where(['username' => 'demo'])
            ->one();

        if ($user) {
            // Eliminar asignaciones de rol
            $this->delete('{{%auth_assignment}}', ['user_id' => (string) $user['id']]);

            // Eliminar perfil
            $this->delete('{{%user_profile}}', ['user_id' => $user['id']]);

            // Eliminar usuario
            $this->delete('{{%user}}', ['id' => $user['id']]);
        }

        echo "Usuario demo eliminado.\n";
    }
}
