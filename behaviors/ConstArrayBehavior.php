<?php

namespace common\behaviors;

use yii\behaviors\AttributeBehavior;

/**
 *
 * ```php
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => ConstArrayBehavior::className(),
 *             'arrays' => [
 *                   'title' => [1 => 'Good', 2 => 'Bad']
 *             ],
 *         ],
 *     ];
 * }
 * ```
 *
 * Class ConstArrayBehavior
 * @package yii\behaviors
 */
class ConstArrayBehavior extends AttributeBehavior
{
    /**
     * @var array
     */
    public $arrays = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

    }

    /**
     * @param  string $attribute
     * @return array
     */
    public function getConstArray($attribute)
    {
        if (!empty($this->arrays[$attribute]) && is_array($this->arrays[$attribute])) {
            return $this->arrays[$attribute];
        } else {
            return [];
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function getTitleValue($attribute)
    {
        $array = $this->getConstArray($attribute);
        $value = $this->owner->$attribute;
        if (isset($array[$value])) {
            return $this->arrays[$value];
        } else {
            return '---';
        }
    }

    public function events()
    {
        return array_merge([
                            ''
                           ], parent::events());
    }
}