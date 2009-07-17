
    var IE = '\v'=='v';
    var working = {};
    var map_id = null;


    //////////////////////////
    //
    // MORE MAP GAME
    //
    //////////////////////////

    var morMapGame_cache = {};

    function moreMapGameShowPage(gameId, page)
    {
        if (morMapGame_cache[gameId])
        {
            if (morMapGame_cache[gameId][page])
            {
                $('moreMapGame').update(morMapGame_cache[gameId][page]);
                return;
            }
        }
        else
        {
            morMapGame_cache[gameId] = {};
            morMapGame_cache[gameId][0] = $('moreMapGame').innerHTML;
        }

        $('moreMapGame_pagination').setStyle({visibility: 'hidden'});

        var param = {
            'gameId': gameId,
            'page': page
        };

        new Ajax.Request (ROOT_PATH + 'remote/moreMapGameShowPage.php', {
            parameters: $H(param).toQueryString(),
            onSuccess: function(xhr)
            {
                console.log(xhr.responseText);
                $('moreMapGame').update(xhr.responseText);
                morMapGame_cache[gameId][page] = xhr.responseText;
            }
        });
    }


    //////////////////////////
    //
    // FILTER
    //
    //////////////////////////


    function filterSubmit()
    {
        $('filter').submit();
    }


    //////////////////////////
    //
    // SEARCH
    //
    //////////////////////////


    function searchSubmit(resetGame, resetGametype, resetQuery)
    {
        var input;

        if (resetGame)
        {
            input = $('search_game');

            if (input)
            {
                input.value = 0;
            }
        }

        if (resetGametype)
        {
            input = $('search_gametype')

            if (input)
            {
                input.value = 0;
            }
        }

        if (resetQuery)
        {
            input = $('search_query');

            if (input)
            {
                input.value = '';
            }
        }

        $('search').submit();
    }


    //////////////////////////
    //
    //  SUBMIT
    //
    //////////////////////////

    var submitSwfu1;
    var submitSwfu2;
    var submitTotalByte = 0;
    var submitUploadedByte = 0;
    var submitPreviousByte = 0;
    var submitData = 0;

    function initSubmit()
    {
        var form = $('submitMap');

        if (form)
        {
            $('submitMapProgress').hide();
            $('submitMapDimer').hide();
            $('submitMapComplete').hide();

            createAutoField($(form.author), 'author', 'name');

            submitSwfu1 = new SWFUpload(
            {
    			flash_url : ROOT_PATH + 'js/lib/swfupload.swf',
                        upload_url: ROOT_PATH + 'remote/uploadScreenshot.php',

    			file_size_limit: 1024 * 2,
    			file_types: '*.jpg;*.gif;*.png',
                        file_types_description: 'JPG/GIF/PNG image, 1Mb maximum',
    			file_upload_limit: 0,
    			file_queue_limit : 10,
    			debug: false,
    			use_query_string: false,

    			// Button settings
    			button_image_url: ROOT_PATH + "media/image/layout/xp_pload_61x22.png",	// Relative to the Flash file
    			button_width: "61",
    			button_height: "22",
    			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
    			button_placeholder_id: 'submitScreenshot',
    			button_cursor : SWFUpload.CURSOR.HAND,

                        file_queue_error_handler : _screenshotQueueError,
    			file_queued_handler: _screenshotQueued,
                        upload_progress_handler: _screenshotUploadProgress,
    			upload_complete_handler: _screenshotUploadComplete,
    			upload_success_handler: _screenshotUploadSuccess,
    			upload_error_handler : _uploadError
    		});

            submitSwfu2 = new SWFUpload({
    			flash_url : ROOT_PATH + 'js/lib/swfupload.swf',
                        upload_url: ROOT_PATH + 'remote/uploadFile.php',

    			file_size_limit: 1024 * 150,
    			file_types: '*.zip',
                        file_types_description: 'ZIP file, 150Mb maximum',
    			file_upload_limit: 1,
    			file_queue_limit : 1,
    			debug: false,
    			use_query_string: false,

    			// Button settings
    			button_image_url: ROOT_PATH + "media/image/layout/xp_pload_61x22.png",	// Relative to the Flash file
    			button_width: "61",
    			button_height: "22",
    			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILE,
    			button_placeholder_id: 'submitFile',
    			button_cursor : SWFUpload.CURSOR.HAND,

                        file_queue_error_handler : _fileQueueError,
    			file_queued_handler: _fileQueued,
                        upload_progress_handler: _fileUploadProgress,
    			upload_complete_handler: _fileUploadComplete,
    			upload_success_handler: _fileUploadSuccess,
    			upload_error_handler : _uploadError
    		});
        }
    }

    function _fileQueueError(file, code, message)
    {
        switch (code)
        {
            case -100:
                alert('ERROR: Queue limit exceeded ! You can\'t send more than 10 images.');
                break;

            case -110:
                alert('ERROR: File exceeds size limit. Screenshot files size must be lower than 2Mb.');
                break;

            case -120:
                alert('ERROR: Zero byte file.');
                break;

            case -130:
                alert('ERROR: Invalide filetype. Screenshot files must be of type JPG, PNG or GIF.');
                break;
        }
    }

    function _screenshotQueueError(file, code, message)
    {
        switch (code)
        {
            case -100:
                alert('ERROR: Queue limit exceeded ! You can\'t send more than 1 Zip file.');
                break;

            case -110:
                alert('ERROR: File exceeds size limit. Zip file size must be lower than 150Mb.');
                break;

            case -120:
                alert('ERROR: Zero byte file.');
                break;

            case -130:
                alert('ERROR: Invalide filetype. Your map must be compressed in a ZIP file.');
                break;
        }
    }

    function _uploadError(file, code, message)
    {
        submitCancel();

        switch (code)
        {
            case -200:
            case -210:
            case -220:
            case -250:
            case -260:
                alert('ERROR ' + code + ': An internal error occured. Try again later or send us this message.');
                break;

            case -230:
                alert('ERROR: Security error. Your network security might be to hight. Try again from an other network.');
                break;

            case -240:
                alert('ERROR: Upload limit exceeded.');
                break;

            case -270:
                alert('ERROR: File validation failed.');
                break;

            case -280:
            case -290:
                break;
        }
    }

    function _screenshotUploadSuccess(fileObj, data)
    {
        if (typeof submitData.screenshot == 'undefined')
        {
            submitData.screenshot = data;
        }

        submitData.screenshot = submitData.screenshot + ',' + data;
    }

    function _screenshotQueued(fileObj)
    {
        submitTotalByte += fileObj.size;

        var info = new Element('a', {
            'href': 'javascript:nothing();'
        }).update(this.getStats().files_queued + ' screenshot waiting ... (click to reset)');

        info.observe('click', function()
        {
            this.remove();
            while(submitSwfu1.cancelUpload()){}
        });

        $('submitScreenshotList').update(info);
    }

    function _screenshotUploadProgress(fileObj, bytesLoaded)
    {
        submitUpdateProgress(bytesLoaded);
    }

    function _screenshotUploadComplete(fileObj)
    {
        if (submitSwfu1.getStats().files_queued > 0){
			submitSwfu1.startUpload();
		}else{
		    submitSwfu2.startUpload();
		}
    }

    function _fileUploadSuccess(fileObj, data)
    {
        submitData.file_id = data;
    }

    function _fileQueued(fileObj)
    {
        submitTotalByte += fileObj.size;

        var info = new Element('a', {
            'href': 'javascript:nothing();'
        }).update(this.getStats().files_queued + ' file waiting ... (click to reset)');

        info.observe('click', function()
        {
            this.remove();
            while(submitSwfu2.cancelUpload()){}
        });

        $('submitFileList').update(info);
    }

    function _fileUploadProgress(fileObj, bytesLoaded)
    {
        submitUpdateProgress(bytesLoaded);
    }

    function _fileUploadComplete(fileObj)
    {
        $('submitMapProgress').down().next().update('Upload completed with success !');
        $('submitMapProgress').down('a').hide();

        $('submitMapPreview').writeAttribute('src', ROOT_PATH + '/screenshot/80x60/submitPreview-' + submitData.screenshot.split(',')[0] + '.jpg');

        submitData = 0;

        var block = $('submitMapComplete');

        block.setStyle({
            'display': 'block'
        });

        Effect.Fade(block, {
            duration: 3,
            from: 0,
            to: 1
        });

        Effect.Fade($('submitMap'));
    }

    function submitCancel()
    {
        while(submitSwfu1.cancelUpload()){}
        while(submitSwfu2.cancelUpload()){}

        submitSwfu1.stopUpload();
        submitSwfu2.stopUpload();

        $('submitFileList').update();
        $('submitScreenshotList').update();

        if (submitData)
        {
            new Ajax.Request (ROOT_PATH + '/remote/submitMapCancel.php', {
                parameters: $H(submitData).toQueryString(),
                onSuccess: function(xhr)
                {
                    submitData = 0;

                    Effect.Fade($('submitMapProgress'));
                    Effect.Fade($('submitMapDimer'));

                    $('submitMapComplete').hide();
                }
            });
        }
    }

    function submitUpdateProgress(bytesLoaded)
    {
        if (bytesLoaded < submitPreviousByte)
        {
            submitPreviousByte = 0;
        }

        submitUploadedByte += bytesLoaded - submitPreviousByte;
        submitPreviousByte = bytesLoaded;

        var block = $('submitMapProgress');
        var percent = 0;

        if (submitTotalByte !== 0)
        {
            percent = Math.ceil((submitUploadedByte / submitTotalByte) * 100);
        }

        block.down().setStyle({
            'width': percent + '%'
        });

        block.down().next().update('Uploading ... ' + percent + '%');
    }

    function submitMap()
    {
        var form = $('submitMap');

        var title = form.title.value.stripScripts().stripTags().strip();
        var game = form.game.value.stripScripts().stripTags().strip();
        var gametype = form.gametype.value.stripScripts().stripTags().strip();
        var author = form.author.value.stripScripts().stripTags().strip();
        var description = form.description.value.stripScripts().stripTags().strip();

        if (title.blank() || game.blank() || gametype.blank() || author.blank())
        {
            alert('Oups ! you did not fill all the form :)');
            return;
        }

        if (submitSwfu1.getStats().files_queued === 0)
        {
            alert('You did not select any screenshots !');
            return;
        }

        if (submitSwfu2.getStats().files_queued === 0)
        {
            alert('You did not select any file !');
            return;
        }

        $('submitMapProgress').show();
        $('submitMapDimer').show();

        var param = {
            'title': title,
            'game': game,
            'gametype': gametype,
            'author': author,
            'description': description
        };

        new Ajax.Request (ROOT_PATH + '/remote/submitMap.php', {
            parameters: $H(param).toQueryString(),
            onSuccess: function(xhr)
            {
                submitData = xhr.responseJSON;

                if (submitData.toString() === '0')
                {
                    alert('An ERROR occured. Try again or contact us.');
                    return;
                }

                submitSwfu1.addPostParam('mapId', submitData.map_id.toString());
                submitSwfu2.addPostParam('mapId', submitData.map_id.toString());
                submitSwfu1.startUpload();
            }
        });
    }


    //////////////////////////
    //
    //  AUTO FIELD
    //
    //////////////////////////

    var autoTimeout;
    var autoValue;
    var autoInput;
    var autoList;
    var autoTable;
    var autoField;

    function createAutoField(input, table, field)
    {
        input.observe('focus', function()
        {
            if (autoInput == this)
            {
                return;
            }

            autoValue = this.value;
            autoInput = this;
            autoTable = table;
            autoField = field;
            autoTimeout = null;
            autoList = this.up().down('ul');
        });

        input.observe('blur', function()
        {
            window.clearTimeout(autoTimeout);
            window.setTimeout(autoCloseList, 100);
        });

        input.observe('keydown', autoFieldUpdated);
        input.up().addClassName('auto');
        input.writeAttribute('autocomplete', 'off');

        var list = new Element('ul');
        list.hide();

        Element.insert(input.up(), {
            'bottom': list
        });
    }

    function autoCloseList()
    {
        autoList.hide();
    }

    function autoFieldUpdated(e)
    {
        var keyCode = e.keyCode || e.which;
        var item = null;

        if (autoList.childElements().length !== 0 && keyCode == Event.KEY_UP){
            item = autoGetPreviousItem();
            autoInput.value = autoValue[autoTable] = item.innerHTML;
        }else
        if (autoList.childElements().length !== 0 && keyCode == Event.KEY_DOWN){
            item = autoGetNextItem();
            if (item)
            {
                autoInput.value = autoValue[autoTable] = item.innerHTML;
            }
        }else
        if (keyCode == 13){
            autoList.update();
            autoList.hide();
            e.preventDefault();
            return false;
        }else{
            window.clearTimeout(autoTimeout);
            if (typeof working['auto_' + autoTable] == 'undefined' || !working['auto_' + autoTable])
            {
                autoTimeout = window.setTimeout(autoUpdateList, 50);
            }
            else
            {
                autoTimeout = window.setTimeout(autoUpdateList, 500);
            }
        }
    }

    function autoUpdateList()
    {
        var value = autoInput.value;

        working['auto_' + autoTable] = true;

        if (autoValue != value){
            autoValue = value;

            if (value.blank()){
                autoList.update();
                autoList.hide();
                working['auto_' + autoTable] = false;
                return;
            }

            var param = {
                'table': autoTable,
                'field': autoField,
                'value': autoValue
            };

            new Ajax.Request (ROOT_PATH + '/remote/autoCompletionSearch.php', {
                parameters: $H(param).toQueryString(),
                onSuccess: autoCallback
            });
        }
        else
        {
            working['auto_' + autoTable] = false;
        }
    }

    function autoCallback(xhr)
    {
        var data = xhr.responseJSON;

		autoList.update();

		if (data.length !== 0)
        {
	        for (var i = 0; i < data.length; i ++){
				autoList.insert(autoListItem(data[i].value));
			}
            autoList.show();
		}else{
		    autoList.hide();
		}

		// if we typed while ajax request was going on
		if (autoValue != autoInput.value){
            working['auto_' + autoTable] = false;
		    autoUpdated();
		}
		else
		{
		  autoTimeout = window.setTimeout(function(){working['auto_' + autoTable] = false;}, 500);
		}
    }

    function autoListItem(value)
    {
        var item = Element('li').update(value);

        item.observe('click', autoListItemClick.bind(item, value));

        return item;
    }

    function autoListItemClick()
    {
        var value = arguments[0];

        autoValue = value;
        autoInput.value = value;
    }

    function autoGetNextItem()
    {
        var selected;
        var item;

        selected = autoList.down('li.selected');

        if(selected)
        {
            selected.toggleClassName('selected');
            if (!(item = selected.next())){
                item = selected;
            }
        }
        else
        {
            item = autoList.down();
        }
        item.toggleClassName('selected');
        return item;
    }

    function autoGetPreviousItem()
    {
        var selected;
        var item;

        selected = autoList.down('li.selected');

        if(selected)
        {
            selected.toggleClassName('selected');
            if (!(item = selected.previous())){
                return selected;
            }
        }
        else
        {
            return false;
        }
        item.toggleClassName('selected');
        return item;
    }


    //////////////////////////
    //
    //  ACTIVITY
    //
    //////////////////////////

    var activityIsOpen = false;
    var activityOverTimeout;
    var activityPreviewTimeout;
    var activityImageTimeout;
    var avtivityPreviewId = 0;
    var activityPreviewTop = 0;
    var activityPreviewInit = false;

    function initActivity()
    {
        var activity = $('latestActivity');

        if (activity)
        {
            $('activityPreview').hide();
            $('activityPreview').setStyle(
            {
                visibility: 'visible'
            });

            var mapList = activity.childElements();

            for (var i = 0; i < mapList.length; i ++)
            {
                mapList[i].observe('mouseover', activitySetPreview);
                mapList[i].observe('mouseover', activityOver);
                mapList[i].observe('mouseout', activityOut);
            }

            activityPreviewAdjustTop();
        }
    }

    function activityPreviewAdjustTop()
    {
        var block = $('activityPreview');

        var top = block.cumulativeOffset()[1];

        if (top <= activityPreviewTop + 2 && top >= activityPreviewTop - 2)
        {
            window.setTimeout(activityPreviewAdjustTop, 500);
        }
        else
        {
            block.setStyle({
                'top': (((top * 3) + activityPreviewTop) / 4) + 'px'
            });

            window.setTimeout(activityPreviewAdjustTop, 30);
        }
    }

    function activitySetPreview()
    {
        var id = this.readAttribute('name');

        if (id != avtivityPreviewId)
        {
            activityPreviewTop = this.cumulativeOffset()[1];

            if (!activityPreviewInit)
            {
                var block = $('activityPreview');

                block.setStyle({
                    'top': activityPreviewTop + 'px',
                    'backgroundImage': 'none'
                });

                block.update();

                activityPreviewInit = true;
            }

            var newImage = new Element('img',
            {
    			'src': '/screenshot/160x120/preview-' + id + '.jpg'
    		});

    		waitPicReady(newImage, function(img)
    		{
                avtivityPreviewId = id;
                activitySetPreviewCallback(img);
            });
        }
    }

    function activitySetPreviewCallback(img)
    {
        window.clearTimeout(activityImageTimeout);

        if (working.activityImage)
        {
            activityImageTimeout = window.setTimeout(function()
            {
                activitySetPreviewCallback(img);
            }, 200);
            return;
        }

        activityImageTimeout = window.setTimeout(function()
        {
            working.activityImage = true;

            var block = $('activityPreview');
            var oldImg = block.down();

            if (oldImg)
            {
                block.setStyle({
                    'background': 'url(' + block.down().readAttribute('src') + ')'
                });
            }

			img.hide();
            $('activityPreview').update(img);

            Effect.Appear(img, {
        		duration: 0.4,
        		afterFinishInternal: function()
        		{
                    working.activityImage = false;
        		}
        	});
        }, 100);
    }

    function activityOver()
    {
        window.clearTimeout(activityOverTimeout);
        window.clearTimeout(activityPreviewTimeout);

        if (working.activity || activityIsOpen)
        {
            activityPreviewTimeout = window.setTimeout(activityOver, 500);
            return;
        }

        activityTimeout = window.setTimeout(function()
        {
            working.activity = true;

            Effect.Appear('activityPreview', {
        		duration: 0.2,
        		afterFinishInternal: function()
        		{
                    working.activity = false;
                    activityIsOpen = true;
        		}
        	});
    	}, 50);
    }

    function activityOut()
    {
        activityOverTimeout = window.setTimeout(function()
        {
            activityOutCallback();
        }, 50);
    }

    function activityOutCallback()
    {
        window.clearTimeout(activityPreviewTimeout);

        if (working.activity || !activityIsOpen)
        {
            activityPreviewTimeout = window.setTimeout(activityOut, 200);
            return;
        }

        activityTimeout = window.setTimeout(function()
        {
            working.activity = true;

            Effect.Fade('activityPreview', {
        		duration: 0.5,
        		afterFinishInternal: function()
        		{
                    working.activity = false;
                    activityIsOpen = false;
                    activityPreviewInit = false;
        		}
        	});
        }, 800);
    }


    //////////////////////////
    //
    //  CONTACT US
    //
    //////////////////////////

    function contactUs()
    {
        var contactForm = $('contactUs');

        if (contactForm)
        {
            var name = contactForm.name.value.stripScripts().stripTags().strip();
            var email = contactForm.email.value.stripScripts().stripTags().strip();
            var subject = contactForm.subject.value.stripScripts().stripTags().strip();
            var message = contactForm.message.value.stripScripts().stripTags().strip();

            if (name.blank() || email.blank() || subject.blank() || message.blank())
            {
                alert('Oups ! you did not fill the form :)');
            }
            else
            if (!checkemail(email))
            {
                alert('Invalide email addresse :(');
            }
            else
            {
                var param = {
                    'name': name,
                    'email': email,
                    'subject': subject,
                    'message': message
                };

                new Ajax.Request (ROOT_PATH + '/remote/contactUs.php', {
                    parameters: $H(param).toQueryString(),
                    onSuccess: function(xhr){
                        var notice = new Element('li').update('Thank you !<br />Your message has been sent to the team. We\'ll give an answer very soon !');

                        contactForm.replace(notice);
                    }
                });
            }
        }
    }


    //////////////////////////
    //
    //  COMMENT
    //
    //////////////////////////

    var commentPage = 0;
    var activeRepply = 0;

    function initComment()
    {
        var commentBlock = $('commentList');

        if (commentBlock)
        {
            var commentList = commentBlock.down('ul').childElements();

            for (var i = 0; i < commentList.length; i ++)
            {
                var button = commentList[i].down('em');

                button.observe('click', commentRepply);
                button.observe('mouseover', addClassOver);
                button.observe('mouseout', removeClassOver);
            }
        }
    }

    function commentRepply()
    {
        var commentRepplyForm = $('commentRepplyForm');
        if (commentRepplyForm)
        {
            commentRepplyForm.remove();
            if (activeRepply == this.readAttribute('name'))
            {
                this.update('>> Reply');
                return;
            }
        }

        activeRepply = this.readAttribute('name');
        this.update('>> Cancel');

        var line1 = new Element('div');
        line1.insert(new Element('label').update('Name:'));
        line1.insert(new Element('input', {
            'type': 'text',
            'name': 'name',
            'maxlength': 32,
            'value': '',
            'class': 'field'
        }));

        var line2 = new Element('div');
        line2.insert(new Element('label').update('Message:'));
        line2.insert(new Element('textarea', {
            'name': 'message'
        }));

        var submit = new Element('input', {
            'type': 'submit',
            'value': 'Post comment',
            'class': 'submit'
        });

        var form = new Element('form', {
            'id': 'commentRepplyForm',
            'name': 'commentRepplyForm',
            'action': 'javascript:commentPostRepply(' + activeRepply + ');'
        });
        form.insert(line1);
        form.insert(line2);
        form.insert(submit);

        Element.insert(this.up(), {
            bottom: form
        });
    }

    function commentPostRepply(parent_id)
    {
        var name = document.commentRepplyForm.name.value.stripScripts().stripTags().strip();
        var message = document.commentRepplyForm.message.value.stripScripts().stripTags().strip();

        if (name.blank() || message.blank())
        {
            alert('You must enter a comment and fill you name !');
            return;
        }

        var param = {
            'name': name,
            'message': message,
            'parent_id': parent_id,
            'map_id': map_id
        };

        new Ajax.Request (ROOT_PATH + '/remote/commentPostRepply.php', {
            parameters: $H(param).toQueryString(),
            onSuccess: function(xhr){
                toogleBlock('commentList', true);
                commentShowPage(0);

                $('commentRepplyForm').remove();
            }
        });
    }

    function commentPost()
    {
        var name = document.commentForm.name.value.strip().stripScripts().stripTags();
        var message = document.commentForm.message.value.strip().stripScripts().stripTags();

        if (name.blank() || message.blank())
        {
            alert('You must enter a comment and fill you name !');
            return;
        }

        var param = {
            'name': name,
            'message': message,
            'map_id': map_id
        };

        new Ajax.Request (ROOT_PATH + '/remote/commentPost.php', {
            parameters: $H(param).toQueryString(),
            onSuccess: function(){
                document.commentForm.name.value = '';
                document.commentForm.message.value = '';
                toogleBlock('commentList', true);
                commentShowPage(0);
            }
        });
    }

    function commentShowPage(n)
    {
        if (typeof n != 'number')
        {
            return;
        }

        var param = {
            'p': n,
            'map': map_id
        };

        new Ajax.Request (ROOT_PATH + '/remote/commentShowPage.php', {
            parameters: $H(param).toQueryString(),
            method: 'get',
            onSuccess: function(xhr){
                $('commentList').update(xhr.responseText);
                commentPage = n;
                initComment();
            }
        });
    }


    //////////////////////////
    //
    //  TOGGLE
    //
    //////////////////////////

    function toogleBlock(id, forced)
    {
        if (working[id])
        {
            return;
        }
        working[id] = true;

        var block = $(id);

        if (typeof forced != 'undefined')
        {
            if (forced)
            {
                if (block.hasClassName('on'))
                {
                    return;
                }

                if (IE)
                {
                    block.show();
                    working[id] = false;
                }
                else
                {
                    Effect.SlideDown(block, {
                        duration: 0.5,
                        afterFinishInternal: function(effect) {
                            working[id] = false;
                        }
                    });
                }

                block.addClassName('on');
                block.previous().addClassName('on');
            }
            else
            {
                if (block.hasClassName('on'))
                {
                    block.removeClassName('on');
                    block.previous().removeClassName('on');
                }
                else
                {
                    return;
                }

                if (IE)
                {
                    block.hide();
                    working[id] = false;
                }
                else
                {
                    Effect.SlideUp(block, {
                        duration: 0.5,
                        afterFinishInternal: function(effect) {
                            working[id] = false;
                            block.hide();
                        }
                    });
                }
            }
        }
        else
        {
            if (block.hasClassName('on'))
            {
                if (IE)
                {
                    block.hide();
                    working[id] = false;
                }
                else
                {
                    Effect.SlideUp(block, {
                        duration: 0.5,
                        afterFinishInternal: function(effect) {
                            working[id] = false;
                            block.hide();
                        }
                    });
                }

                block.removeClassName('on');
                block.previous().removeClassName('on');
            }
            else
            {
                if (IE)
                {
                    block.show();
                    working[id] = false;
                }
                else
                {
                    Effect.SlideDown(block, {
                        duration: 0.5,
                        afterFinishInternal: function(effect) {
                            working[id] = false;
                        }
                    });
                }

                block.addClassName('on');
                block.previous().addClassName('on');
            }
        }
    }


    //////////////////////////
    //
    //  RATTING
    //
    //////////////////////////

    function initRatting()
    {
        var rattingBlock = $('ratting');

        if (rattingBlock)
        {
            var rattingLines = rattingBlock.childElements();

            for (var i = 0; i < rattingLines.length; i ++)
            {
                if (rattingLines[i].hasClassName('on'))
                {
                    rattingLines[i].observe('mouseover', rattingOver);
                    rattingLines[i].observe('mouseout', rattingOut);

                    var stars = rattingLines[i].childElements();

                    for (var s = 1; s < stars.length; s ++){
                        stars[s].observe('mouseover', startOver);
                        stars[s].observe('click', startClick);
                    }
                }
            }
        }
    }


    function rattingOver()
    {
        if (!this.hasClassName('hover'))
        {
            this.addClassName('hover');
        }
    }

    function rattingOut()
    {
        if (this.hasClassName('hover'))
        {
            this.removeClassName('hover');
        }

        var score = this.readAttribute('name');

        setRatting(this, score);
    }

    function startOver()
    {
        var stars = this.up().childElements();
        var matched = false;

        for (var i = 0; i < stars.length; i ++)
        {
            if (this == stars[i])
            {
                setRatting(this.up(), i);
                break;
            }
        }
    }

    function startClick()
    {
        var block = this.up();

        if (working['ratting_' + block.readAttribute('name')])
        {
            return;
        }
        working['ratting_' + block.readAttribute('name')] = true;

        if (block.hasClassName('on'))
        {
            block.removeClassName('on');
            block.addClassName('off');

            var blockList = block.up().childElements();
            var n = 0;
            var i = 0;

            for (i = 0; i < blockList.length; i ++)
            {
                if (blockList[i] == block)
                {
                    n = i;
                    break;
                }
            }

            var starList = block.childElements();
            var s = 0;

            block.stopObserving('mouseover', rattingOver);
            block.stopObserving('mouseout', rattingOut);

            for (i = 0; i < starList.length; i ++)
            {
                starList[i].stopObserving('mouseover', startOver);
                starList[i].stopObserving('click', startClick);

                if (starList[i] == this)
                {
                    s = i;
                }
            }

            var param = {
                's': s,
                'n': n,
                'mapId': map_id
            };

            new Ajax.Request (ROOT_PATH + '/remote/ratting.php', {
                parameters: $H(param).toQueryString(),
                method: 'post',
                onSuccess: function(xhr){
                    setRatting(block, xhr.responseText);

                    working['ratting_' + block.readAttribute('name')] = false;

                    Effect.Pulsate(block, {
                        duration: 1,
                        pulses: 2
                    });
                }
            });
        }
    }

    function setRatting(block, n)
    {
        var stars = block.childElements();

        for (var i = 0; i < stars.length; i ++)
        {
            if (i <= n)
            {
                if (!stars[i].hasClassName('on'))
                {
                    stars[i].addClassName('on');
                }
            }
            else
            {
                if (stars[i].hasClassName('on'))
                {
                    stars[i].removeClassName('on');
                }
            }
        }
    }


    //////////////////////////
    //
    //  INIT
    //
    //////////////////////////

    window.onload = function ()
    {
        initRatting();
        initComment();
        initActivity();
        initSubmit();
    };

