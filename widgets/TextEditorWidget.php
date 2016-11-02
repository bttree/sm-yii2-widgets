<?php
namespace backend\widgets;

use Yii;
use yii\base\Widget;

/**
 * Class TextEditorWidget
 * @package \bttree\smywidgets\widgets
 *
 */
class TextEditorWidget extends Widget
{
    /**
     * @var string $class \vova07\imperavi\Widget or \dosamigos\ckeditor\CKEditor
     */
    public $widgetClass;

    /**
     * @var \yii\base\Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;

    /**
     * @var string the input value.
     */
    public $value;

    /**
     * @var string|null Selector pointing to textarea to initialize redactor for.
     * Defaults to null meaning that textarea does not exist yet and will be
     * rendered by this widget.
     */
    public $selector;

    public $settings;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    public function init()
    {
        Yii::$app->getModule('smywidgets');
    }

    public function run()
    {
        $widget = $this->widgetClass;

        return $widget::widget([
                                   'model'     => $this->model,
                                   'attribute' => $this->attribute,
                                   'name'      => $this->name,
                                   'value'     => $this->value,
                                   'selector'  => $this->selector,
                                   'settings'  => $this->settings,
                               ]);
    }
}