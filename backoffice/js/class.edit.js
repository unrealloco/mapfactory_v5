function Edit()
{
    this.n;
    this.widg;
    this.isOpen;
    this.working = false;
    this.saveParam;
    this.saveTimeout;
    this.toolList = new Array ();

    this.shutdown = function()
    {
        if (this.isOpen){
            this.toolList.each(function(pair) {
                pair.shutdown();
            });
            $('tool').hide();
            $('sfwupload_container').update();
        }

        $('edit').hide();
        this.n = null;
        this.widg = new Array();
        this.isOpen = false;
        this.toolList = new Array ();
    };

    this.create = function(event, n)
    {
        var time = new Date().getTime();

        this.shutdown();

        this.isOpen = true;
        this.n = n;

        Data.seek(this.n);

        // empty edit box and set title
        $('edit').update();
        $('edit').insert(Cache.newElement('h2').update(Data.get(TPL.getParam('previewField'))));

        // create save button
        var save = Cache.doElement('edit.save', function(){
            return new Element('span', {
                'class': 'button'
            }).update('enregistrer');
        });

        save.observe('click', this.save.bind(this));
        save.observe('mouseover', addClassOver);
        save.observe('mouseout', removeClassOver);

        var toolBar = Cache.doElement('edit.toolbar', function(){
            return new Element('div', {
                'class': 'toolbar'
            });
        });
        toolBar.update(save);
        $('edit').insert(toolBar);

        // grab template and generate all inputs
        var tpl = $H(TPL.getFieldList());
        tpl.each(function(pair) {
            Edit.newFeild(pair.key, pair.value);
        });

        // handle tools
        var tool = $H(TPL.getToolList());
        tool.each(function(pair) {
            if(pair.value.autoLoad){
                eval('this.toolList.push(new ' + pair.value.type + '())');
                this.toolList[this.toolList.length - 1].create(n, pair.value);
            }else{
                alert(pair.key + ' has to be started manualy');
            }
        }.bind(this));

        $('edit').show();
        $('tool').show();

        dbg('Form loaded in : ' + (new Date().getTime() - time) + 'ms');
    };

    this.save = function()
    {
        // exit if we are loading data
        if (Edit.working == true){
            return;
        }
        Edit.working = true;

        Data.seek(this.n);

        this.saveParam = {
            type: Menu.loadedType,
            id: Data.get('id')
        };

        if (TPL.getParam('makeGuid'))
        {
            this.saveParam.guid = TPL.getParam('makeGuid');
        }

        // grab template and colect fields data
        var tpl = $H(TPL.getFieldList());
        tpl.each(function(pair) {
            switch (pair.value.type){
                case 'input':
                    var value = $('edit')[pair.key].value;
                    this.saveParam['data[' + pair.key + ']'] = value;
                    Data.set(pair.key, value);
                    break;
                case 'checkbox':
                    var value = ($('edit')[pair.key].checked) ? 1 : 0;
                    this.saveParam['data[' + pair.key + ']'] = value;
                    Data.set(pair.key, value);
                    break;
                case 'select':
                    var value = $('edit')[pair.key].value;
                    this.saveParam['data[' + pair.key + ']'] = value;
                    Data.set(pair.key, value);
                    break;
                case 'radio':
                    var element = $('edit')[pair.key];
                    for(var i = 0; i < element.length; i++) {
                		if(element[i].checked) {
                		    var value = element[i].value;
                		    this.saveParam['data[' + pair.key + ']'] = value;
                		    Data.set(pair.key, value);
                			break;
                		}
                	}
                    break;
                case 'textarea':
                    if (pair.value.editor){
                        Edit.widg[pair.key].updateWidgInput ();
                    }
                    var value = $('edit')[pair.key].value;
                    this.saveParam['data[' + pair.key + ']'] = value;
                    Data.set(pair.key, value);
                    break;
            }
        }.bind(this));

        // handle tools
        var tool = $H(TPL.getToolList());
        this.toolList.each(function(pair) {
            pair.save();
        });

        // save data
        this.saveCallback();
    }

    this.setSaveData = function(field, value)
    {
        Data.seek(this.n);
        Data.set(field, value);
        Edit.saveParam['data[' + field + ']'] = value;
    }

    this.saveCallback = function()
    {
        var notReady = false;

        this.toolList.each(function(pair) {
            if (pair.working == true){
                window.clearTimeout(Edit.saveTimeout);
                Edit.saveTimeout = window.setTimeout('Edit.saveCallback();', 100);
                notReady = true;
            }
        });

        if (notReady == true){
            return;
        }

        new Ajax.Request (ROOT_PATH + 'backoffice/remote/save.php', {
            parameters: $H(this.saveParam).toQueryString(),
            onSuccess: function(xhr){
                Data.seek(this.n);
                $('listItem_' + Edit.n).update(Data.get('title'));
                $$('#edit h2')[0].update(Data.get(TPL.getParam('previewField')));
                List.reorder();
                List.showPage();
                Edit.working = false;
            }.bind(this)
        });
    }

    this.remove = function()
    {
        // handle tools
        var tool = $H(TPL.getToolList());
        this.toolList.each(function(pair) {
            pair.remove();
        });
    };

    this.newFeild = function(name, param)
    {
        // create line and label
        var line = Cache.doElement('edit.line', function(){
            return new Element('div').addClassName('line');
        });
        line.insert(Cache.newElement('label').update(param.label + ' :'));

        $('edit').insert(line);

        // create element
        switch (param.type){
            case 'input':
                line.insert(this.elementInput(name, param));
                break;
            case 'checkbox':
                line.insert(this.elementCheckbox(name, param));
                break;
            case 'select':
                line.insert(this.elementSelect(name, param));
                break;
            case 'radio':
                line.insert(this.elementRadio(name, param));
                break;
            case 'textarea':
                line.insert(this.elementTextarea(name, param));
                if(param.editor){
                    this.widg[name] = new widgEditor('textarea_' + name);
                }
                break;
        }
    };

    this.elementInput = function(name, param)
    {
        var element = Cache.newElement('input').writeAttribute('name', name);
        Data.seek(this.n);

        // handle value
        if(Data.isOK(name)){
            element.writeAttribute('value', Data.get(name));
        }else
        if (param.defaultValue){
            element.writeAttribute('value', param.defaultValue);
        }

        // handle maxLength
        if (param.maxLength){
            element.writeAttribute('maxlength', param.maxLength);
        }

        // handle isNumeric
        if (param.isNumeric){
            element.observe('change', function(){
                this.value = strRemoveNotnum(this.value);
            });
        }

        // handle editor
        if(param.editor){
            element.addClassName('widgeditor');
        }

        return element;
    };

    this.elementTextarea = function(name, param)
    {
        var element = Cache.newElement('textarea').writeAttribute('name', name);
        Data.seek(this.n);
        element.writeAttribute('id', 'textarea_' + name);

        // handle value
        if(Data.isOK(name)){
            element.update(Data.get(name));
        }else
        if (param.defaultValue){
            element.update(param.defaultValue);
        }

        // handle maxLength
        if (param.maxLength){
            element.observe('change', function(){
                this.value = this.value.substr(0, param.maxLength);
            });
        }

        // handle allowHTML
        if (!param.allowHTML){
            element.observe('change', function(){
                this.value = this.value.stripTags();
            });
        }

        return element;
    };

    this.elementCheckbox = function(name, param)
    {
        var element = Cache.newElement('input').writeAttribute('name', name);
        Data.seek(this.n);
        element.writeAttribute('type', 'checkbox');

        // handle value
        if(Data.get(name) == 1 || param.defaultValue == 1){
            element.writeAttribute('checked', 'checked');
        }

        return element;
    };

    this.elementSelect = function(name, param)
    {
        var element = Cache.newElement('select');
        element.writeAttribute('name', name);
        Data.seek(this.n);

        // handle multiple
        if(param.multiple){
            element.writeAttribute('multiple', 'multiple');
        }

        // handle values
        $H(param.option).each(function(pair) {
            var option = new Element('option', {
                'value': pair.key
            }).update(pair.value);

            if (Data.isOK(name)){
                if (Data.get(name) == pair.key){
                    option.writeAttribute('selected', 'selected');
                }
            }else
            if (param.defaultValue == pair.key){
                option.writeAttribute('selected', 'selected');
            }

            element.insert(option);
        }.bind(this));

        return element;
    };

    this.elementRadio = function(name, param)
    {
        var element = new Element('div').addClassName('radio_container');
        Data.seek(this.n);

        // handle values
        $H(param.option).each(function(pair) {
            var input = new Element('input', {
                'value': pair.key,
                'type': 'radio',
                'name': name
            });

            if (Data.isOK(name)){
                if (Data.get(name) == pair.key){
                    input.writeAttribute('checked', 'checked');
                }
            }else
            if (param.defaultValue == pair.key){
                input.writeAttribute('checked', 'checked');
            }

            var container = Cache.newElement('label').update(input);
            container.insert(pair.value);

            element.insert(container);
        }.bind(this));

        return element;
    };
}
