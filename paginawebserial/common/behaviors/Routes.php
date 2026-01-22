<?php
namespace common\behaviors;

use common\models\User;
use yii\base\Behavior;
use Yii;

class Routes extends Behavior
{

    public function getDefaultRoute(){
        if (!$this->owner->organization_id) {
            $user_id = Yii::$app->getUser()->id;
            $model = User::findOne(['id' => $user_id]);
            $this->owner->organization_id = $model->organization_id;
        }
    }

}
