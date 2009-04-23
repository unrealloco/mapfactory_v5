function Menu()
{
    this.loadedType = null;
    this.working = false;

    this.init = function()
    {
        // loop through all sections
        $H(template).each(function(pair){
            var element = new Element('li', {
                'name': pair.key
            }).update(pair.value.name);

            // add a click, mouseover and mouseout observer
            element.observe('click', function(){
                Menu.load(pair.key);
            });
            element.observe('mouseover', addClassOver);
            element.observe('mouseout', removeClassOver);
            
            $('menu').insert(element);
        });
        
        // init other menu classes
        List_tool.init();
        Pagination.init();

        // reset
        List.shutdown();
        Edit.shutdown();
    };

    this.load = function(type)
    {
        // exit if we are loading data
        if (this.working){
            return;
        }
        
        // get all menu elements
        var sectionList = $$('#menu li');

        // select the element and unselect all other ones
        for (var i = 0; i < sectionList.length; i ++){
            if (sectionList[i].hasClassName('selected')){
                sectionList[i].removeClassName('selected');
            }else
            if (sectionList[i].readAttribute('name') == type){
                sectionList[i].addClassName('selected');
            }
        }
        
        // close edit if opened
        Edit.shutdown();
        List.shutdown();
        Pagination.shutdown();

        // close if allready opened
        if (Menu.loadedType == type){
            Menu.loadedType = null;
            return;
        }

        this.working = true;
        this.loadedType = type;

        // cache data
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/load.php', {
            parameters: 'type=' + type + '&orderBy=' + TPL.getParam('orderBy') + '&sortOrder=' + TPL.getParam('sortOrder'),
            onSuccess: function(xhr){
                Menu.load_callback(xhr.responseJSON);
            }
        });
    };

    this.load_callback = function(JSON)
    {
        Data.load(JSON);

        // start listing
        List.init(this.loadedType);

        this.working = false;
    };
}
