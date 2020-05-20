<?php

namespace denis909\yii;

use yii\db\ActiveRecord;
use Yii;

class SortBehavior extends \yii\base\Behavior
{

    public $attribute = 'sort';

    public $defaultValue = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave'
        ];
    }

    public function beforeSave($event)
    {
        if (!$event->sender->isAttributeSafe($this->attribute))
        {
            return;
        }

        if ($event->sender->{$this->attribute} === '')
        {
            $event->sender->{$this->attribute} = $this->defaultValue;
        }
    }

    public function afterSave($event)
    {   
        if (!$event->sender->isAttributeSafe($this->attribute))
        {
            return;
        }

        if ($event->sender->{$this->attribute})
        {
            return;
        }
        
        $event->sender->{$this->attribute} = $event->sender->primaryKey;

        $event->sender->updateAttributes([$this->attribute]);
    }

}