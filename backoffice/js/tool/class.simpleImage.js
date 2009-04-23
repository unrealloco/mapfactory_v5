function simpleImage()
{
    this.n;
    this.identifier;
    this.param;
    this.swfu;
    this.progress;
    this.startButton;
    this.listButton;
    this.fileList;
    this.preview;
    this.message;
    
    this.shutdown = function()
    {
        $('tool_' + this.n).remove();
        this.n = null;
        this.param = null;
        this.progress = null;
        this.startButton = null;
        this.listButton = null;
        this.fileList = null;
        this.preview = null;
        this.message = null;
    };
    
    this.save = function()
    {
        return;
    };

    this.create = function(n, param)
    {
        this.n = n;
        this.param = param;
        this.identifier = new Date().getTime();
        
        Data.seek(this.n);
        
        var container = new Element('form', {
            'id': 'tool_' + this.n,
            'class': 'simpleImage'
        });
        
        // progress
        this.progress = Cache.newElement('span');
		
		// selectButton
		var button = new Element('div', {
			'id': this.identifier + '_button'
		});
		
		// listButton
		this.listButton = Cache.doElement('simpleImage.listButton', function(){
            var e = new Element('span').addClassName('button');
            return e;
        });
        this.listButton.update((this.param.multipleFiles) ? 'Afficher les fichiers' : 'Afficher le fichier');
		this.listButton.observe('click', this.list.bind(this));
		this.listButton.hide();
        
        // fileList
        this.fileList = Cache.newElement('ul');
        this.fileList.hide();
        
        // preview
        this.preview = Cache.doElement('simpleImage.preview', function(){
            return new Element('img').writeAttribute('src', ROOT_PATH + 'backoffice/img/preview_empty.gif');
        });
        this.preview.hide();
        
        // clear
        var clear = Cache.doElement('simpleImage.cache', function(){
            return new Element('div').setStyle({
                'clear': 'both'
            });
        });
        
        // message
        this.message = Cache.newElement('span');
        this.message.hide();
        
        // startButton
		this.startButton = Cache.doElement('simpleImage.startButton', function(){
            return new Element('button', {
    			'type': 'button',
    			'class': 'startButton'
    		}).update('envoyer');
        });
        
        var settings = {
			flash_url : ROOT_PATH + 'js/lib/swfupload.swf',
            upload_url: ROOT_PATH + 'backoffice/remote/tool/simpleImage/upload.php',
			post_params: {
                'data': '',
                'id': Data.get('id'),
                'folder': param.folder,
                'table': param.table,
                'size': $A(param.size).toJSON(),
                'multipleFiles': (param.multipleFiles) ? '1' : '0'
            },
            
			file_size_limit: 1024,
			file_types: '*.jpg;*.gif;*.png',
            file_types_description: 'JPG/GIF/PNG image, 1Mo maximum',
			file_upload_limit: 0,
			file_queue_limit : 0,
			custom_settings: {
                'progress': this.progress,
                'startButton': this.startButton,
                'listButton': this.listButton,
                'fileList': this.fileList,
                'preview': this.preview,
                'message': this.message
            },
			debug: false,
			use_query_string: true,

			// Button settings
			button_image_url: "img/xp_pload_61x22.png",	// Relative to the Flash file
			button_width: "61",
			button_height: "22",
			button_action : (this.param.multipleFiles)?SWFUpload.BUTTON_ACTION.SELECT_FILES:SWFUpload.BUTTON_ACTION.SELECT_FILE,
			button_placeholder_id: this.identifier + '_button',
			button_cursor : SWFUpload.CURSOR.HAND,
			button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
			
			file_queued_handler: this._fileQueued,
            file_dialog_start_handler: this._fileDialogStart,
            upload_progress_handler: this._uploadProgress,
            upload_success_handler: this._uploadSuccess,
			upload_complete_handler: this._uploadComplete
		};

        container.insert(Cache.newElement('label').update(param.label + ' :'));
        container.insert(this.startButton);
        container.insert(button);
        container.insert(this.message);
        container.insert(this.progress);
        container.insert(this.listButton);
        container.insert(this.preview);
        container.insert(this.fileList);
        container.insert(clear);
        
        $('tool').insert(container);
        
        this.swfu = new SWFUpload(settings);
        
        this.startButton.observe('click', this.upload.bind(this));
        this.startButton.hide();
        
        this.list();
    };
    
    this.remove = function()
    {
        Data.seek(this.n);
        
        var param = {
            'type': Menu.loadedType,
            'id': Data.get('id'),
            'folder': this.param.folder,
            'table': this.param.table,
            'field': this.param.field,
            'size': $A(this.param.size).toJSON()
        };
        
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleImage/remove.php', {
            parameters: $H(param).toQueryString(),
            method: 'get'
        });
    };
    
    this.upload = function()
    {
        this.swfu.startUpload();
    };
    
    this.list = function()
    {
        Data.seek(this.n);
        
        var param = {
            'id': Data.get('id'),
            'folder': this.param.folder,
            'table': this.param.table
        };
    
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleImage/list.php', {
            parameters: $H(param).toQueryString(),
            onSuccess: this.list_callback.bind(this)
        });
    };
    
    this.list_callback = function(xhr)
    {
        var xml = xhr.responseXML;

        this.fileList.update();
        
        if (XMLcount (xml) == 0){
            this.message.update('0 fichier trouvé');
            this.message.show();
        }else{
            for (var i = 0; i < XMLcount (xml); i ++){
                this.fileList.insert(this.newFileItem(
                    XMLresult (xml, i, 'id'),
                    XMLresult (xml, i, 'file'),
                    XMLresult (xml, i, 'name')
                ));
                if (i == 0){
                    this.showPreview(XMLresult (xml, i, 'id'));
                }
            }
        }
        
        this.listButton.hide();
        this.fileList.show();
        this.preview.show();
    };
    
    this.newFileItem = function(id, file, name)
    {
        Data.seek(this.n);
        
        var item = new Element('li').writeAttribute('id', 'simpleImage_' + this.identifier + '_file_' + id);
        var link = new Element('a', {
            'href': ROOT_PATH + file,
            'target': '_blank'
        });

        if (name)
        {
            link.update(name);
        }
        
        var deleteLink = Cache.doElement('simpleFile.deleteLink', function(){
            return new Element('span').update('effacer');
        });
        
        var renameLink = Cache.doElement('simpleFile.renameLink', function(){
            return new Element('span').update('renomer');
        });
        
        var selectLink = Cache.doElement('simpleImage.selectLink', function(){
            return new Element('span').addClassName('selectLink');
        });
        selectLink.writeAttribute('id', 'simpleImage_' +  this.identifier + '_' + id);
        selectLink.addClassName('simpleImage_' +  this.identifier);
        if (Data.get(this.param.field) == id){
            selectLink.addClassName('selected');
        }
        
        item.observe('mouseover', this.showPreview.bind(this, id));
        
        deleteLink.observe('mouseover', addClassOver);
        deleteLink.observe('mouseout', removeClassOver);
        deleteLink.observe('click', this.del.bind(this, item, id));
        
        renameLink.observe('mouseover', addClassOver);
        renameLink.observe('mouseout', removeClassOver);
        renameLink.observe('click', this.rename.bind(this, link, id));
        
        selectLink.observe('click', this.select.bind(this, selectLink, id));
        
        item.insert(selectLink);
        item.insert(renameLink);
        item.insert(deleteLink);
        item.insert(link);
        
        return item;
    };
    
    this.showPreview = function()
    {
        var id = arguments[0];
        this.preview.writeAttribute('src', ROOT_PATH + 'backoffice/img/preview/' + this.param.table + '_' + id + '.jpg')

        var lineList = this.fileList.childElements();
        for (var i = 0; i < lineList.length; i ++){
            if (lineList[i].hasClassName('selected')){
                lineList[i].removeClassName('selected');
            }
        }
        
        var element = $('simpleImage_' + this.identifier + '_file_' + id);
        
        if (!element.hasClassName('selected')){
            element.addClassName('selected');
        }
    }
    
    this.del = function()
    {
        if (confirm('Etes vous sur de vouloir effacer ce fichier ?')){
            var element = arguments[0];
            var id = arguments[1];

            var param = $H({
                'type': Menu.loadedType,
                'table': this.param.table,
                'folder': this.param.folder,
                'field': this.param.field,
                'size': $A(this.param.size).toJSON(),
                'selected_image_id': Data.get(this.param.field, this.n),
                'image_id': id,
                'id': Data.get('id', this.n)
            });
            
            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleImage/delete.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: function(xhr){
                    element.remove();
                    this.showPreview();
                }.bind(this)
            });
        }
    };
    
    this.rename = function()
    {
        var element = arguments[0];
        var id = arguments[1];
        var name = prompt('Nom du fichier :', $('simpleImage_file_' + id + '_name').innerHTML);
        
        if (!name.blank()){
            name = name.substr(0, 255);
        
            var param = $H({
                'table': this.param.table,
                'name': name,
                'id': id
            });
            
            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleImage/rename.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: function(xhr){
                    element.update(name);
                }
            });
        }
    };
    
    this.select = function()
    {
        var element = arguments[0];
        var id = arguments[1];

        Data.seek(this.n);

        var param = $H({
            'type': Menu.loadedType,
            'field': this.param.field,
            'id': Data.get('id'),
            'image_id': id
        });

        new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleImage/select.php', {
            parameters: $H(param).toQueryString(),
            onSuccess: function(xhr){
                $$('span.simpleImage_' +  this.identifier + '.selectLink').each(function(item)
                {
                    if (item.hasClassName('selected'))
                    {
                        item.removeClassName('selected');
                    }
                });
                element.toggleClassName('selected');
                Data.set(this.param.field, id);
            }.bind(this)
        });
    };
    
    this._fileDialogStart = function()
    {
        this.customSettings.progress.update();
        this.customSettings.startButton.hide();
        this.customSettings.message.hide();
        this.cancelUpload();
    };
    
    this._fileQueued = function(fileObj)
    {
        this.customSettings.progress.update(this.getStats ().files_queued + ' image en attente');
        this.customSettings.startButton.show();
        this.customSettings.progress.show();
        this.customSettings.listButton.hide();
        this.customSettings.fileList.hide();
        this.customSettings.preview.hide();
    };
    
    this._uploadProgress = function(fileObj, bytesLoaded)
    {
        if (bytesLoaded == fileObj.size){
			this.customSettings.progress.update('Envoi terminé, traitement en cours, ' + (this.getStats ().files_queued - 1) + ' image restante');
		}else{
			this.customSettings.progress.update('Envoi ' + Math.ceil((bytesLoaded / fileObj.size) * 100) + ' % (' + Math.ceil(bytesLoaded / 1024) +' / ' + Math.ceil(fileObj.size / 1024) + 'ko), ' + this.getStats ().files_queued + ' image restante');
		}
    };
    
    this._uploadComplete = function(fileObj){
		if (this.getStats().files_queued > 0){
			this.startUpload();
		}else{
		    this.customSettings.progress.hide();
            this.customSettings.startButton.hide();
            this.customSettings.listButton.show();
		}
	};
    
    this._uploadSuccess = function(fileObj, data)
    {
        // alert(data);
    };
}