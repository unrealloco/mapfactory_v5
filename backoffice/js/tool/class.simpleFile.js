function simpleFile()
{
    this.n;
    this.identifier;
    this.param;
    this.swfu;
    this.progress;
    this.startButton;
    this.listButton;
    this.fileList;
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

        var container = new Element('form',{
            'id': 'tool_' + this.n,
            'class': 'simpleFile'
        });
        
        // progress
        this.progress = Cache.newElement('span');
		
		// selectButton
		var button = new Element('div', {
			'id': this.identifier + '_button'
		});
		
		// listButton
		this.listButton = Cache.doElement('simpleFile.listButton', function(){
            var e = new Element('span').addClassName('button');
            return e;
        });
        this.listButton.update((this.param.multipleFiles) ? 'Afficher les fichiers' : 'Afficher le fichier');
		this.listButton.observe('click', this.list.bind(this));
        
        // fileList
        this.fileList = Cache.newElement('ul');
        this.fileList.hide();
        
        // message
        this.message = Cache.newElement('span');
        this.message.hide();
        
        // startButton
        this.startButton = Cache.doElement('simpleFile.startButton', function(){
            return new Element('button', {
    			'type': 'button',
    			'class': 'startButton'
    		}).update('envoyer');
        });

        var settings = {
			flash_url : ROOT_PATH + 'js/lib/swfupload.swf',
            upload_url: ROOT_PATH + 'backoffice/remote/tool/simpleFile/upload.php',
			post_params: {
                'data': '',
                'id': Data.get('id'),
                'folder': param.folder,
                'table': param.table,
                'extention': param.file_extention,
                'multipleFiles': (param.multipleFiles == true) ? '1' : '0'
            },
            
			file_size_limit: param.file_size_limit,
			file_types: '*.' + param.file_extention,
			file_types_description: param.file_types_description,
			file_upload_limit: 0,
			file_queue_limit : 0,
			custom_settings: {
                'progress': this.progress,
                'startButton': this.startButton,
                'listButton': this.listButton,
                'fileList': this.fileList,
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
        container.insert(this.fileList);
        
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
            'id': Data.get('id'),
            'folder': this.param.folder,
            'table': this.param.table,
            'extention': this.param.file_extention
        };
        
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleFile/remove.php', {
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
            'table': this.param.table,
            'extention': this.param.file_extention
        };
        
        new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleFile/list.php', {
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
			}
        }
        
        this.listButton.hide();
        this.fileList.show();
    }
    
    this.newFileItem = function(id, file, name)
    {
        var item = Cache.newElement('li');
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
		
		var me = this;
		
		deleteLink.observe('mouseover', addClassOver);
        deleteLink.observe('mouseout', removeClassOver);
		deleteLink.observe('click', this.del.bind(this, item, id));
		
		renameLink.observe('mouseover', addClassOver);
        renameLink.observe('mouseout', removeClassOver);
		renameLink.observe('click', this.rename.bind(this, link, id));
		
		item.insert(renameLink);
		item.insert(deleteLink);
		item.insert(link);
		
		return item;
    };
    
    this.del = function()
    {
        if (confirm('Etes vous sur de vouloir effacer ce fichier ?')){
            var element = arguments[0];
            var id = arguments[1];

            var param = $H({
                'table': this.param.table,
                'folder': this.param.folder,
                'extention': this.param.file_extention,
                'id': id
            });
            
            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleFile/delete.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: function(xhr){
                    element.remove();
                }
            });
        }
    };
    
    this.rename = function()
    {
        var element = arguments[0];
        var id = arguments[1];
        var name = prompt('Nom du fichier :', element.innerHTML);

        if (!name.blank()){
            name = name.substr(0, 255);

            var param = $H({
                'table': this.param.table,
                'name': name,
                'id': id
            });
            
            new Ajax.Request (ROOT_PATH + 'backoffice/remote/tool/simpleFile/rename.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: function(xhr){
                    element.update(name);
                }
            });
        }
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
        this.customSettings.progress.update(this.getStats ().files_queued + ' fichier en attente');
        this.customSettings.startButton.show();
        this.customSettings.listButton.hide();
        this.customSettings.fileList.hide();
    };
    
    this._uploadProgress = function(fileObj, bytesLoaded)
    {
        if (bytesLoaded == fileObj.size){
			this.customSettings.progress.update('Envoi terminé, ' + (this.getStats ().files_queued - 1) + ' fichier restant');
		}else{
			this.customSettings.progress.update('Envoi ' + Math.ceil((bytesLoaded / fileObj.size) * 100) + ' % (' + Math.ceil(bytesLoaded / 1024) +' / ' + Math.ceil(fileObj.size / 1024) + 'ko), ' + this.getStats ().files_queued + ' fichier restant');
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
