<?php

namespace bttree\smywidgets\assets;

use yii\web\AssetBundle;


class SlugAsset extends AssetBundle
{
    public $sourcePath = '@bttree/smywidgets/assets/slug';

    public $js = [
        'js/slug-widget.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}
