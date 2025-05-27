<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "avatar".
 *
 * @property int $id
 * @property string $pathway
 *
 * @property User[] $users
 */
class Avatar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avatar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pathway'], 'required'],
            [['pathway'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pathway' => 'Pathway',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id_avatar' => 'id']);
    }
}
