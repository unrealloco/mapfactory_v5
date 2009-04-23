function Item(n)
{
    this.n = n;
    this.working = false;
    this.container;

    this.init = function()
    {
        Data.seek(this.n);

        // create main contenair
        this.container = Cache.newElement('li').writeAttribute('id', 'item_' + this.n);
        this.container.observe('mouseover', addClassOver);
        this.container.observe('mouseout', removeClassOver);
        if (List.itemOpened == Data.get('id', this.n)){
            if (Edit.isOpen)
            {
                Edit.n = this.n;
            }
            this.container.addClassName('selected');
        }

        // setup status
        if (TPL.getParam('statusToggle')){
            var status = Cache.doElement('item.status', function(){
                return new Element('div').addClassName('status');
            });
            if (Data.get('status') == 1){
                status.addClassName('on');
            }
            status.observe('click', this.toggleStatus.bind(this));
        }

        // setup title
        var title = Cache.newElement('span').writeAttribute('id', 'listItem_' + this.n);
        title.update(Data.get(TPL.getParam('previewField')));
        title.observe('click', this.click.bind(this));

        // insert elements into contenair
        this.container.insert(status);
        this.container.insert(title);
    };

    this.click = function(event){
        this.container.toggleClassName('selected');
        if (Edit.isOpen){
            if (Edit.n == this.n){
                Edit.shutdown();
                List.itemOpened = 0;
                return;
            }else{
                if ($('item_' + Edit.n)){
                    $('item_' + Edit.n).removeClassName('selected');
                }
            }
        }
        Edit.create(event, this.n);
        List.itemOpened = Data.get('id', this.n);
    }

    this.toggleStatus = function(event)
    {
        if (this.working){
            return;
        }
        this.working = true;

        Data.seek(this.n);

        var param = $H({
            type: Menu.loadedType,
            id: Data.get('id')
        });

        new Ajax.Request (ROOT_PATH+'backoffice/remote/toogleStatus.php', {
            parameters: param.toQueryString(),
            onSuccess: this.toggleStatus_callback.bind(this, event)
        });
    };

    this.toggleStatus_callback = function()
    {
        var event = arguments[0];

        Data.seek(this.n);

        Event.element(event).toggleClassName('on');
        Data.set('status', (Data.get('status') == 1) ? 0 : 1);
        this.working = false;
    }
}
