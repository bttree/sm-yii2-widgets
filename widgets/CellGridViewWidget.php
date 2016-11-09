<?php
namespace bttree\smywidgets\widgets;

use yii\base\Widget;
use yii\web\JsExpression;

/**
 * Class CellGridViewWidget
 */
class CellGridViewWidget extends Widget
{
    public $view                = 'cell-table';
    public $options             = [];
    public $events              = [];
    public $table_id            = 'cell-table';
    public $table_pagination_id = 'cell-table_pagination';
    public $ajax_url            = '';
    public $page_size           = 20;

    public function run()
    {
        $options = $this->options;
        foreach ($this->events as $event => $handler) {
            if (!($handler instanceof JsExpression)) {
                $handler = new JsExpression($handler);
            }
            $options[$event] = $handler;
        }

        return $this->render($this->view,
                             [
                                 'options'             => $options,
                                 'ajax_url'            => $this->ajax_url,
                                 'table_id'            => $this->table_id,
                                 'table_pagination_id' => $this->table_pagination_id,
                                 'page_size'           => $this->page_size,
                             ]);
    }
}