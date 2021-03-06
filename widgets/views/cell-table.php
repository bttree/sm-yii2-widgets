<?php

use bttree\smywidgets\assets\CellAsset;
use bttree\smywidgets\assets\QueryObjectAsset;
use yii\helpers\Json;

/* @var array $options */
/* @var string $table_id */
/* @var string $table_pagination_id */
/* @var string $ajax_url */
/* @var integer $page_size */

QueryObjectAsset::register($this);
CellAsset::register($this);
?>
<div id="<?= $table_id ?>" style="max-width: 100%"></div>
<div id="<?= $table_pagination_id ?>"></div>
<?php
$group = "";
$summ = false;
foreach($options['columns'] as $col){
    if(isset($col['group'])){
        $group = $col['data'];
    }
    if(isset($col['rendererNative'])){
        if($col['rendererNative'] == 'TotalNameRender'){
            $summ = true;
        }
    }
}

$this->registerJs(
/** @lang javascript */
    '
     $( document ).ready(function() {
        var rowRenderer   = new RowRenderer();
        var summ = {};
        var opt = {
        summ: "'.$summ.'",
        groupCol: "'.$group.'",
        page_size: "'.$page_size.'",
        ajax_url: "'.$ajax_url.'",
        table_pagination_id: "'.$table_pagination_id.'",       
        };
        var tableContainer = document.getElementById("' . $table_id . '");
        var table = new Handsontable(tableContainer, ' . Json::encode($options) . ');
        
        table.setRowError = function(rowId) {
            rowRenderer.setHighlightedRow(rowId);
            table.render();
        };
        table.unsetRowError = function(rowId) {
            rowRenderer.unsetHighlightedRow(rowId);
            table.render();
        };
        table.setGroupRow = function(rowId) {
            rowRenderer.setGroupRow(table,rowId);
            table.render();
        };
        
        getDataNative(table,opt);
        Handsontable.Dom.addEvent(window, \'hashchange\', function (event) {
            getDataNative(table,opt) 
        });
        var btnaddrow = document.getElementById("btn-add-row");
        if(btnaddrow !== null){
        Handsontable.Dom.addEvent(btnaddrow, \'click\', function (event) {
                i = table.countSourceRows() +1;
                table.alter("insert_row", i);
        });
        }
        var btnremoverow = document.getElementById("btn-remove-row");
        if(btnremoverow !== null){
        Handsontable.Dom.addEvent(btnremoverow, \'click\', function (event) {
                var select = table.getSelected();
                table.alter(\'remove_row\', select[0], select[2]);                
        });
        }        
        $(document).on("click", ".cell-pagination_item", function (event) {
            event.preventDefault();
            var pageNum = $(this).data("page");
            var newUrl  = $.query.SET("page", pageNum);
            
            window.history.pushState(null, null, newUrl);
            getDataNative(table,opt);
        });
        $("#' . $table_id . '").bind("refreshTable", function(){ 
            getDataNative(table,opt);
        });
    });
    
    function RowRenderer() {
        var highlightedRows   = [];
        var highlightedColor = "#FF7C7C";
        
        return {
            getRenderFunction: function(){
                return function(instance, td, row, col, prop, value, cellProperties) {
                    if(cellProperties.group > 0){
                    if (value != instance.getDataAtCell(row - 1, col)){
                          td.setAttribute("style", "vertical-align: middle");
                          td.setAttribute("align", "center");
                          td.setAttribute("colSpan", cellProperties.group);
                          } else {
                            td.removeAttribute("colSpan");
                           value = ""; 
                           
                          }
                          if(row == instance.countRows() - 1){
                            td.removeAttribute("colSpan");
                          }
                    }                   
                    if($.inArray( row, highlightedRows ) !== -1){
                        td.style.backgroundColor = highlightedColor;
                    }
                    if(cellProperties.rendererNative){
                        var rendeferer = cellProperties.rendererNative;
                    }else{
                        var rendeferer = Handsontable.renderers.getRenderer(cellProperties.type);
                    }
                    
                    return rendeferer(instance, td, row, col, prop, value, cellProperties);
                }
            },
            setHighlightedRow: function(row){
                highlightedRows.push(row);
            },
            unsetHighlightedRow: function(row){
                highlightedRows = jQuery.grep(highlightedRows, function(value) {
                  return value != row;
                });
            },
            resetHighlightedRow: function(){
                highlightedRows = [];
            }
        }
    }
    
    function renderPagination(paginationBlock, totalCount) {
        var pagingControls = "";
        var numPages       = Math.ceil(totalCount / ' . $page_size . ');
        var currentPage    = parseInt(getUrlParamByName("page"));
        
        if(!$.isNumeric(currentPage)) {
            currentPage = 1;
        }
        
        if(numPages > 1) {
            pagingControls += \'<ul class="pagination">\'
            if(currentPage != 1) {
                var prevPage = currentPage - 1;
                pagingControls += \'<li class="prev cell-pagination_item" data-page="\'+ prevPage +\'"><a href="/prices?page=\'+ prevPage +\'" >«</a></li>\';
            } else {
                pagingControls += \'<li class="prev disabled"><span>«</span></li>\';
            }
        
        for (var i = 1; i <= numPages; i++) {
            if (i != currentPage) {
                pagingControls += "<li class=\'cell-pagination_item\' data-page=\'"+ i +"\'><a href=\'?page="+ i +"\' >"+ i +"</a></li>";
            } else {
                pagingControls += "<li class=\'cell-pagination_item active\' data-page=\'"+ i +"\'><a href=\'?page="+ i +"\' >"+ i +"</a></li>";
            }
        }
        
            if(currentPage != numPages) {
                var nextPage = currentPage + 1;
                pagingControls += \'<li class="next cell-pagination_item" data-page="\'+ nextPage +\'"><a href="/prices?page=\'+ nextPage +\'">»</a></li>\';
            } else {
                pagingControls += \'<li class="next disabled"><span>»</span></li>\';
            }
            pagingControls += \'</ul>\'
        }
        
        paginationBlock.html(pagingControls);
    }
    
    function getUrlParamByName(name) {
        var results = new RegExp(\'[\?&]\' + name + \'=([^&#]*)\').exec(window.location.href);
        if (results == null){
           return null;
        } else {
           return results[1] || 0;
        }
    }
    
    function getUrlParams()
    {
        var params = {};
        var hash;
        var hashes = window.location.href.slice(window.location.href.indexOf(\'?\') + 1).split(\'&\');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split(\'=\');
            params[hash[0]] = hash[1];
        }
        return params;
    }
    function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
        var selectedId;
        var optionsList = cellProperties.chosenOptions.data;
    
        var values = (value + "").split(",");
        var value = [];
        for (var index = 0; index < optionsList.length; index++) {
            if (values.indexOf(optionsList[index].id + "") > -1) {
                selectedId = optionsList[index].id;
                value.push(optionsList[index].label);
            }
        }
        value = value.join(", ");
    
        Handsontable.TextCell.renderer.apply(this, arguments);
    }
   function TotalNameRender(instance, td, row, col, prop, value){
        if(row == instance.countRows() - 1){
            td.style.fontWeight = \'bold\';
            td.style.textAlign = \'right\';
            td.innerText = \'Итого: \';
            return;
        } else {
             Handsontable.TextCell.renderer.apply(this, arguments);
        }   
    }
    function TotalRender(instance, td, row, col, prop, value){
        if(row == instance.countRows() - 1){
            td.innerText = instance.getDataAtCol(col).reduce(function(sum, row){
                        return sum + row; 
                }, 0);
        } else {
           Handsontable.TextCell.renderer.apply(this, arguments);
        }   
    }
    
    function getDataNative(table,opt) {
        var params = getUrlParams();
        params["pageSize"] = opt.page_size;
        
        var tableContainer = $(table.container).parent();
        tableContainer.addClass("loading-table-data");
        $.ajax({
            url: opt.ajax_url,
            type: "GET",
            asynch: false,
            data: params,
            success: function(result) {
                var data = new Array();
                $.each(result.data, function( index, value ) {
                if(opt.groupCol.length > 0){
                    var arr = new Array();
                    arr[opt.groupCol] = value[opt.groupCol];
                    if(index == 0){
                    data.push(arr);
                    }else{
                    if(result.data[index-1][opt.groupCol] !== value[opt.groupCol]){
                    data.push(arr);
                    }               
                    }
                 }   
                data.push(value);
                 });  
                
                if(opt.summ){
                    data.push({});
                }
                table.loadData(data);
                
                var paginationBlock = $("#"+opt.table_pagination_id);
                renderPagination(paginationBlock, result.totalCount);
            }
        }).always(function() {
                tableContainer.removeClass("loading-table-data");
            }
        );
        
    }
    '
);
?>