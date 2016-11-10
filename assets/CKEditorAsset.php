<?php

namespace bttree\smywidgets\assets;

use yii\web\AssetBundle;

/**
 * CKEditorAsset
 *
 */
class CKEditorAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bttree/smywidgets/assets/text-editor/ckeditor';

    public $js = [
        'ckeditor.js',
        'adapters/jquery.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}
