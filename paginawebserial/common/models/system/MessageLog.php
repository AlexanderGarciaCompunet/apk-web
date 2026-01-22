<?php

namespace common\models\system;

use common\models\Contact;
use common\models\Organization;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "message_log".
 *
 * @property int $id
 * @property int $contact_id
 * @property int $organization_id
 * @property int $user_id
 * @property int|null $country_id
 * @property int|null $type_id
 * @property string|null $origin
 * @property string|null $detail
 * @property int $carrier_id
 * @property string|null $send_id
 * @property float|null $carrier_price
 * @property float|null $client_price
 * @property int|null $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Carrier $carrier
 * @property Contact $contact
 * @property Country $country
 * @property Organization $organization
 * @property User $user
 */
class MessageLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contact_id', 'organization_id', 'user_id', 'carrier_id'], 'required'],
            [['contact_id', 'organization_id', 'user_id', 'country_id', 'type_id', 'carrier_id', 'status'], 'integer'],
            [['detail'], 'string'],
            [['carrier_price', 'client_price'], 'number'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['origin', 'send_id'], 'string', 'max' => 255],
            [['carrier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrier::className(), 'targetAttribute' => ['carrier_id' => 'id']],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
            'organization_id' => Yii::t('app', 'Organization ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'origin' => Yii::t('app', 'Origin'),
            'detail' => Yii::t('app', 'Detail'),
            'carrier_id' => Yii::t('app', 'Carrier ID'),
            'send_id' => Yii::t('app', 'Send ID'),
            'carrier_price' => Yii::t('app', 'Carrier Price'),
            'client_price' => Yii::t('app', 'Client Price'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Carrier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrier()
    {
        return $this->hasOne(Carrier::className(), ['id' => 'carrier_id']);
    }

    /**
     * Gets query for [[Contact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
