<?php
/* ----------------------------------------------------------------------
 * app/widgets/count/views/main_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2010 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
    $po_request            = $this->getVar('request');    
    $vs_widget_id            = $this->getVar('widget_id');
    $field                          = $this->getVar('field');
        
    $administrator = $po_request->user->canDoAction("is_administrator");
    
?>
<link type="text/css" rel="stylesheet" href="<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/resources/jquery.contextMenu.css">
<script src="<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/resources/jquery.contextMenu.js" type="text/javascript"></script>
<script src="<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/resources/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/resources/jquery.jstree.js" type="text/javascript"></script>
<script src="<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/resources/jquery.blockUI.js" type="text/javascript"></script>

<div class="dashboardWidgetContentContainer">    
    <div class="dashboardWidgetScrollLarge">            
        <div id="promemoria" style="clear:both;">            
        </div>            
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        $("body").append("<ul id='menu' class='contextMenu'>\
            <li class='up'>\
                <a href='#text_asc'>Alfabetico ASC</a>\
            </li>\
            <li class='down'>\
                <a href='#text_desc'>Alfabetico DESC</a>\
            </li>\
            <li class='up separator'>\
                <a href='#date_asc'>Data ASC</a>\
            </li>\
            <li class='down'>\
                <a href='#date_desc'>Data DESC</a>\
            </li>\
        </ul>");

        function flattenNode(val){
            return val.replace("node_","");
        }
        var promemoria = $("#promemoria");
        var contentBlock = promemoria.parents("div.portlet-content:first");

        promemoria.jstree({
            "plugins" : ["themes","json_data","ui","cookies","dnd"],
            "themes" : {
                "theme" : "classic",
                "dots" : true,
                "icons" : false
            },
            "cookies" : {
                "save_selected" : false,
                "save_opened" : "jstree_open"
            },
            "json_data" : {
                "ajax" : {                    
                    "url" : "<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/ajax/ajax.php",
                    "data" : function (n) {                        
                        return {
                            "field" : "<?php print $field; ?>",                                                        
                            "operation" : "get_children",
                            "id" : n.attr ? flattenNode(n.attr("id")) : 0,
                            "order" : (n.attr && n.attr("order")) ? n.attr("order") : '',
                            "verso" : (n.attr && n.attr("verso")) ? n.attr("verso") : ''
                        }
                    }
                }
            }
        }).bind("move_node.jstree", function(e, data){
            contentBlock.block({
                message: "<img src='<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/resources/images/loading.gif' />",
                    overlayCSS:  {
                    backgroundColor: '#000',
                    opacity:         0.05
                },
                css: {
                    border:         'none',
                    backgroundColor:'transparent'
                    }
            });
            $.ajax({
                type: 'POST',
                url: "<?php print __CA_URL_ROOT__; ?>/app/widgets/promemoria/ajax/ajax.php?operation=save_node",
                data : {
                    "data" : JSON.stringify($.jstree._reference("#promemoria").get_json())
                },
                success : function (r) {
                    if(r.result !== "OK"){
                        $.jstree.rollback(data.rlbk);
                    }
                    contentBlock.unblock();
                },
                error : function(){  
                    $.jstree.rollback(data.rlbk);
                    contentBlock.unblock();
                },
                dataType : "json"
            });
        }).bind("loaded.jstree open_node.jstree",function(){
            $("li:not(.jstree-leaf) > a", this).contextMenu({
                menu: 'menu'
            }, function(action,el){
                var params = action.split("_");
                el.closest("li").attr({
                    "order": params[0],
                    "verso" : params[1]
                });
                $.jstree._reference("#promemoria").refresh(el);
            });
        });

        $("#promemoria a").live("dblclick", function () {
            location.href="<?php print __CA_URL_ROOT__; ?>/index.php/editor/objects/ObjectEditor/Edit/object_id/" + flattenNode($(this).parent().attr("id")); 
        });
    });
</script>
