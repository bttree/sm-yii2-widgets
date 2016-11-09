<?php

namespace bttree\smywidgets\assets;

use yii\web\AssetBundle;


class CellAsset extends AssetBundle
{
    public $sourcePath = '@bttree/smywidgets/assets/cell';

    public $css = [
        'js/handsontable/dist/handsontable.full.min.css',
        'js/chosen/chosen.min.css',
        'css/cell.css',
    ];

    public $js = [
        'js/handsontable/dist/handsontable.full.min.js',
        'js/chosen/chosen.jquery.min.js',
        'js/handsontable-chosen-editor.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}
