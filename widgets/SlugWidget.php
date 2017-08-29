<?php
namespace bttree\smywidgets\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use bttree\smywidgets\assets\SlugAsset;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class TextEditorWidget
 * @package \bttree\smywidgets\widgets
 *
 */
class SlugWidget extends Widget
{

    /**
     * @var Model the data model that this widget is associated with.
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
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var string
     */
    public $sourceFieldSelector;

    /**
     * @var string
     */
    public $targetFieldSelector;

    /**
     * @var string|array
     */
    public $url;

    /**
     * @var boolean
     */
    public $renderInput = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (is_array($this->url)) {
            $this->url = Url::to($this->url);
        }

        if (empty($this->targetFieldSelector)) {
            if (empty($this->options['id'])) {
                $this->options['id'] = Html::getInputId($this->model, $this->attribute);
            }
            $this->targetFieldSelector = "#". $this->options['id'];
        }

        $view = $this->getView();
        SlugAsset::register($view);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $js   = 'initSlugFields("' . $this->sourceFieldSelector . '", "' .
                $this->targetFieldSelector . '", "' .
                $this->url
                . '");';
        $view = $this->getView();

        /*
         * Инициализируем слаг по готовности скриптов. Если он отрабатывает раньше - к нему не цепляется csrf
         */
        $view->registerJs($js, $view::POS_READY);

        if ($this->renderInput) {
            if ($this->hasModel()) {
                return Html::activeTextInput($this->model, $this->attribute, $this->options);
            } else {
                return Html::textInput($this->name, $this->value, $this->options);
            }
        } else {
            return true;
        }
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }
}