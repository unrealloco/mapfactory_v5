function List_tool()
{
    this.init = function()
    {
        $('list_tool').hide();
        
        // tool add
        $('list_tool_add').observe('click', function(){
            List_tool.addItem();
        });
        $('list_tool_add').observe('mouseover', addClassOver);
        $('list_tool_add').observe('mouseout', removeClassOver);
        
        // tool remove
        $('list_tool_remove').observe('click', function(){
            List_tool.removeItem();
        });
        $('list_tool_remove').observe('mouseover', addClassOver);
        $('list_tool_remove').observe('mouseout', removeClassOver);
    };
    
    this.addItem = function()
    {
        var title = prompt('Titre du nouvel item :');
        
        if (title == null){
            return;
        }else
        if (title.blank()){
            this.addItem();
            return;
        }
        
        var param = $H({
            'type': Menu.loadedType,
            'previewField': TPL.getParam('previewField'),
            'title': title
        });

        if (TPL.getParam('makeGuid'))
        {
            param.set('guid', 1);
        }
        
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/add.php', {
            parameters: param.toQueryString(),
            onSuccess: function(xhr){
                Menu.addItem(xhr.responseXML.getElementsByTagName('item')[0]);
                List.reorder();
                List.showPage();
            }
        });
    };
    
    this.removeItem = function()
    {
        if(Edit.n == null){
            alert('Aucun iten n\'est séléctioné !');
            return;
        }
        
        if (!confirm('Effacer définitivement l\'item séléctionné ?')){
            return
        }
        
        Data.seek(Edit.n);
        
        var param = $H({
            type: Menu.loadedType,
            id: Data.get('id')
        });
        
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/remove.php', {
            parameters: param.toQueryString(),
            onSuccess: function(xhr){
            Edit.remove();
            Edit.shutdown();
            Data.remove(Edit.n);
            List.showPage();
            }.bind(this)
        });
    };
}
