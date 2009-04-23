function autoCompletion()
{
    this.n;
    this.param;
    this.id = 0;
    this.input;
    this.list;
    this.lastValue;
    this.timeout;
    this.working = false;
    this.hasResults = false;
    this.message;

    this.shutdown = function()
    {
        $('tool_' + this.n).remove();
        this.n = null;
        this.param = null;
        this.id = null;
        this.input = null;
        this.list = null;
        this.lastValue = null;
        this.timeout = null;
        this.working = null;
        this.hasResults = null;
        this.message = null;
    };

    this.create = function(n, param)
    {
        this.n = n;
        this.param = param;

        var container = new Element('form', {
            'action': '#',
            'id': 'tool_' + this.n,
            'class': 'autoCompletion'
        });

        this.input = Cache.newElement('input');
        this.list = Cache.newElement('ul');
        this.message = Cache.newElement('span');

        this.input.observe('blur', this.fieldBlur.bind(this));

        this.input.observe('keydown', this.fieldUpdated.bindAsEventListener(this));

        if (this.param.maxLength){
            this.input.writeAttribute('maxlength', this.param.maxLength);
        }

        this.list.hide();

        container.insert(Cache.newElement('label').update(param.label + ' :'));
        container.insert(this.input);
        container.insert(this.list);
        container.insert(this.message);
        $('tool').insert(container);

        Data.seek(this.n);

        this.id = Data.get(this.param.field);

        if (this.id != 0){
            var param = {
                'table': this.param.table,
                'table_field': this.param.table_field,
                'id': this.id
            };

            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/autoCompletion/load.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: this.create_callback.bind(this)
            });
        }
    };

    this.remove = function()
    {
    };

    this.create_callback = function(xhr)
    {
        this.input.value = xhr.responseText;
    };

    this.fieldUpdated = function(e)
    {
        var keyCode = e.keyCode || e.which;

        if (this.hasResults && keyCode == Event.KEY_UP){
            var item = this.getPreviousItem();
            this.input.value = this.lastValue = item.innerHTML;
            this.id = item.readAttribute('name');
            this.message.update();
        }else
        if (this.hasResults && keyCode == Event.KEY_DOWN){
            var item = this.getNextItem();
            this.input.value = this.lastValue = item.innerHTML;
            this.id = item.readAttribute('name');
            this.message.update();
        }else
        if (keyCode == 13){
            this.hasResults = false;
            this.list.hide();
            return false;
        }else{
            window.clearTimeout(this.timeout);
            if (!this.working){
                this.timeout = window.setTimeout(this.updateList.bind(this), 500);
            }
        }
    };

    this.fieldBlur = function()
    {
        this.timeout = window.setTimeout(this.closelist.bind(this), 100);
    };

    this.updateList = function()
    {
        this.working = true;

        var value = this.input.value;
        if (this.lastValue != value){
            this.lastValue = value;
            this.id = 0;

            this.message.update();

            if (value.blank()){
                this.list.update();
                this.list.hide();
                this.working = false;
                this.hasResults = false;
                return;
            }

            var param = {
                'table': this.param.table,
                'table_field': this.param.table_field,
                'value': value
            };

            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/autoCompletion/search.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: this.updateList_callback.bind(this)
            });
        }

        this.working = false;
    };

    this.updateList_callback = function(xhr)
    {
        var xml = xhr.responseXML;
        var value = this.input.value;

		this.list.update();
		this.message.update('Nouvelle entrée');

		if (XMLcount (xml) != 0){

	        for (var i = 0; i < XMLcount (xml); i ++){
				this.list.insert(this.newListItem(XMLresult (xml, i, 'value'), XMLresult (xml, i, 'id')));
				if (value == XMLresult (xml, i, 'value')){
				    this.id = XMLresult (xml, i, 'id');
				    this.message.update();
				}
			}
			this.list.show();
			this.hasResults = true;
		}else{
		    this.list.hide();
		    this.hasResults = false;
		}

		this.working = false;

		// if we typed while ajax request was going on
		if (value != this.input.value){
		    this.fieldUpdated();
		}
    };

    this.newListItem = function(value, id)
    {
        var item = Element('li', {
            'name': id
        }).update(value);

        item.observe('click', this.listItemClic.bind(this, value));

        return item;
    };

    this.listItemClic = function(){
        var value = arguments[0];
        this.input.value = value;
        this.id = 0;
        this.lastValue = value;
        this.message.update();
    };

    this.getNextItem = function()
    {
        var selected;
        var item;
        if(selected = this.list.down('li.selected')){
            selected.toggleClassName('selected');
            if (!(item = selected.next())){
                item = selected;
            }
        }else{
            item = this.list.down();
        }
        item.toggleClassName('selected');
        return item;
    };

    this.getPreviousItem = function()
    {
        var selected;
        var item;
        if(selected = this.list.down('li.selected')){
            selected.toggleClassName('selected');
            if (!(item = selected.previous())){
                item = this.newListItem('', 0);;
            }
        }else{
            item = this.newListItem('', 0);
        }
        item.toggleClassName('selected');
        return item;
    };

    this.closelist = function()
    {
        this.list.hide();
        this.hasResults = false;
    };

    this.save = function()
    {
        if (this.id == 0){
            this.working = true;

            var param = {
                'table': this.param.table,
                'table_field': this.param.table_field,
                'value': this.input.value
            };

            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/autoCompletion/save.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: this.save_callback.bind(this)
            });
        }else{
            Edit.setSaveData(this.param.field, this.id);
        }
    };

    this.save_callback = function(xhr)
    {
        Edit.setSaveData(this.param.field, xhr.responseText);
        this.working = false;
    };
}
