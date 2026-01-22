<?php
namespace common\models;

use yii\helpers\ArrayHelper;

class SystemConfig extends system\SystemConfig
{
    /**
     * returns all configured document types
     *
     * @return array
     */
    public function documentTypes(){
        $types = json_decode(self::findOne(['type' => 'profile', 'reference' => 'documentId'])->value, true);
        return ArrayHelper::map($types, 'id', 'label');

    }
}
