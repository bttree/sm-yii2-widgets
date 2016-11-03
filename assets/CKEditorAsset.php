<?php
/**
 * @copyright Copyright (c) 2013-16 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
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
