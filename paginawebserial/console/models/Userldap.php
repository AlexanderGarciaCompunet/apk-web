<?php

namespace console\models;

use common\models\User;
use Edvlerblog\Adldap2\model\UserDbLdap;
use Yii;

class Userldap extends \yii\db\ActiveRecord
{

  public function getUsersFromLdap()
  {
    \Yii::warning("-- Starting import from Active Directory --");
    $users=[];
    $results1 = \Yii::$app->ad1->getDefaultProvider()->search()->select("member")->paginate(999);
    foreach ($results1->getResults() as $ldapUser) {
      $path=$ldapUser['member'];
      foreach($path as $users){
        User::findByAttribute('distinguishedName',$users);
      }
    }
  }
}


