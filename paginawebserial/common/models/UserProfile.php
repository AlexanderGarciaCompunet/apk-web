<?php

namespace common\models;

use \common\models\base\UserProfile as BaseUserProfile;
use Yii;

class UserProfile extends BaseUserProfile
{
    /**
     * @var UploadedFile
     */
    public $thumbnail_file;
    /**
     * @var UploadedFile
     */
    public $signature_file;
    public $otherInfo;
    private $path;


    public function getCustomThumbnail()
    {
        if (empty($this->thumbnail)){
            $this->thumbnail = Yii::getAlias('@web') . "/img/user.png";
        }
        return $this->thumbnail;
    }


    public function upload()
    {
        $key = Yii::$app->params['cryptKey'];
        $this->path = "uploads/up/{$key}{$this->user_id}/";

        if (!file_exists(Yii::$app->basePath . '/web/' . $this->path)) {
            mkdir(Yii::$app->basePath . '/web/' . $this->path, 0775, true);
        }

        if ($this->validate()) {
            if ($this->thumbnail_file) {
                $this->saveFile('thumbnail');
            }
            if ($this->signature_file) {
                $this->saveFile('signature');
            }
            return $this->save();
        } else {
            return false;
        }
    }

    private function saveFile($type)
    {
        $base_url = Yii::getAlias('@web') . '/' .  $this->path;
        $image = $type . '_file';
        $ext = $this->$image->extension;
        $name = Yii::$app->security->generateRandomString() . ".{$ext}";
        $file = Yii::$app->basePath . '/web/' . $this->path . $name;
        //Guarda el archivo en disco y lo referencia en el modelo results
        $this->$image->saveAs($file);
        $this->$type = $base_url . $name;
    }

    public function beforeSave($insert)
    {
        //TODO implementar almacenamiento de direccion
        $other_info = json_encode(['address' => $this->address]);

        if (!$insert) {
            $oldSignatue = $this->getOldAttribute('signature');
            // Evita sobre escribir la variable si llega vacia en la actualizacion
            if (empty($this->thumbnail)) {
                $this->thumbnail = $this->getOldAttribute('thumbnail');
            }
            if (empty($this->signature)) {
                $this->signature = $this->getOldAttribute('signature');
            }
        }
        return parent::beforeSave($insert);
    }

    public function getAddress() {
        if (empty($this->otherInfo)) {
            return '';
        }
        return json_decode($this->otherInfo)->address;
    }

}
