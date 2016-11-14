<?php

namespace bttree\smywidgets\assets;

use yii\web\AssetBundle;


class QueryObjectAsset extends AssetBundle
{
    public $sourcePath = '@bttree/smywidgets/assets/jquery-plugin-query-object';

    public $css = [
    ];

    public $js = [
        'js/jquery.query-object.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}
