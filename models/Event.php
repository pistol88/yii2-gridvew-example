<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $name
 * @property string $date
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date'], 'required'],
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date' => 'Date',
        ];
    }
    
    public function getDistances()
    {
        return $this->hasMany(Distance::className(), ['id' => 'distance_id'])
                ->viaTable('event_distances', ['event_id' => 'id']);
    }
    
    public function getUsers()
    {
        if(!$distanceIds = ArrayHelper::map($this->getDistances()->asArray()->all(), 'id', 'id')) {
            return null;
        }
        
        return User::find()
                ->leftJoin('user_events', 'user_events.user_id = user.id')
                ->where(['user_events.event_distance_id' => $distanceIds]);
    }
    
    public function getUserCount()
    {
        if($users = $this->getUsers()) {
            return $users->count();
        }
        
        return 0;
    }
}
