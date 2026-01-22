<?php


namespace backend\models;


class UserProfile extends \common\models\UserProfile
{
  public function fields()
  {
    return [
      'name',
      'lastname',
      'document',
      'thumbnail',
      'phone',
      // 'signature',
      'email',
      'info'
    ];
  }

  public function getEmail()
  {
    return $this->user->email;
  }

  public function getInfo()
  {
    return json_decode($this->otherInfo, true);
  }
}
