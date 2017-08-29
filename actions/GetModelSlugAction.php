<?php

namespace bttree\smywidgets\actions;

use Yii;
use yii\base\Action;
use yii\behaviors\SluggableBehavior;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'get-model-slug' => [
 *             'class' => \common\actions\GetModelSlugAction::className(),
 *             'modelName => 'Model'
 *         ],
 *     ];
 * }
 * ```
 *
 * @see    http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
 * @author SiteMaket
 */
class GetModelSlugAction extends Action
{
    /**
     * @var string $model Name of model with Sluggable Behavior
     */
    public $modelName;

    public function run()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        /**
         * @var \yii\db\ActiveRecord                  $model
         * @var null|\yii\behaviors\SluggableBehavior $sluggableBehavior
         */
        $modelName         = $this->modelName;
        $model             = new $modelName;
        $sluggableBehavior = null;

        foreach ($model->getBehaviors() as $behavior) {
            if (get_class($behavior) == SluggableBehavior::className()) {
                $sluggableBehavior = $behavior;
                break;
            }
        }

        if (!is_null($sluggableBehavior)) {
            $slugAttribute  = $sluggableBehavior->slugAttribute;
            $attribute      = $sluggableBehavior->attribute;
            $attributeValue = '';

            $baseModelName = StringHelper::basename($modelName);
            $post          = $request->post($baseModelName, []);
            if (isset($post[$attribute])) {
                $attributeValue = $post[$attribute];
            }

            $model->$attribute = $attributeValue;
            $model->validate();
            $transliteration = $model->$slugAttribute;
        } else {
            Yii::warning('Model ' . $this->modelName . ' does not have Sluggable Behavior!');
            $transliteration = '';
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'transliteration' => $transliteration
        ];
    }
}
