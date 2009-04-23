window.onload = function () {
    $('loading').hide();

    Menu.init();
};

var Data = new Data();
var Cache = new Cache();
var TPL = new TPL();
var Menu = new Menu();
var Pagination = new Pagination();
var List = new List();
var List_tool = new List_tool();
var Edit = new Edit();

// TOOLS callbacks :
// shutdown, create (if autoload), save (return saved in 'field')

// config
var template = {
    /********************
    *   MAPS
    ********************/
    map: {
        // config
        name:           'Maps',
        previewField:   'title',
        statusToggle:   true,
        orderBy:        'date',
        sortFunc:       'numeric',
        sortOrder:      'DESC',
        makeGuid:       'title',

        field:
        {
            title:{
                type:           'input',
                label:          'Title',
                defaultValue:   '',
                maxLength:      32,
                isNumeric:      false
            },
            description: {
                type:           'textarea',
                label:          'Description',
                allowHTML:      true,
                editor:         true
            }
        },
        
        tool:
        {
            game: {
                type:           'autoCompletion',
                label:          'Game',
                autoLoad:       true,
                table:          'game',
                table_field:    'name',
                field:          'game_id',
                maxLength:      255
            },
            gametype: {
                type:           'autoCompletion',
                label:          'Gametype',
                autoLoad:       true,
                table:          'gametype',
                table_field:    'name',
                field:          'gametype_id',
                maxLength:      255
            },
            author: {
                type:           'autoCompletion',
                label:          'Author',
                autoLoad:       true,
                table:          'author',
                table_field:    'name',
                field:          'author_id',
                maxLength:      255
            },
            date: {
                type:           'simpleDate',
                label:          'Publish date',
                autoLoad:       true,
                field:          'date'
            },
            image: {
                type:           'simpleImage',
                label:          'Screenshots',
                autoLoad:       true,
                table:          'map_image',
                field:          'image_id',
                folder:         'media/image/screenshot/',
                size:           ['80x60', '160x120', '640x480'],
                multipleFiles:  true
            },
            file: {
                type:           'simpleFile',
                label:          'ZIP File',
                autoLoad:       true,
                table:          'map_file',
                folder:         'media/map/',
                multipleFiles:   false,
                file_size_limit:        1024 * 500,
                file_extention:         'zip',
                file_types_description: 'Zip file, 80Mo maximum'
            }
        }
    },
    
    /********************
    *   AUTHOR
    ********************/
    author: {
        // config
        name: 'Mappers',
        previewField: 'name',
        statusToggle: true,
        orderBy: 'name',
        sortFunc: 'ialpha',
        sortOrder: 'ASC',
        makeGuid: 'name',

        field: {
            name: {
                type: 'input',
                label: 'Nickname',
                defaultValue: '',
                maxLength: 32,
                isNumeric: false
            },
            website: {
                type: 'input',
                label: 'Website',
                defaultValue: '',
                maxLength: 255,
                isNumeric: false
            },
            description: {
                type: 'textarea',
                label: 'Description',
                allowHTML: false,
                editor: false,
                maxLength: 255
            }
        }
    },
    
    /********************
    *   GAME
    ********************/
    game: {
        // config
        name: 'Games',
        previewField: 'name',
        statusToggle: true,
        orderBy: 'name',
        sortFunc: 'ialpha',
        sortOrder: 'ASC',
        makeGuid: 'name',

        field: {
            name: {
                type: 'input',
                label: 'Name',
                defaultValue: '',
                maxLength: 32,
                isNumeric: false
            },
            description: {
                type: 'textarea',
                label: 'Description',
                allowHTML: false,
                editor: false,
                maxLength: 255
            }
        }
    },
    
    /********************
    *   GAMETYPE
    ********************/
    gametype: {
        // config
        name: 'Gametypes',
        previewField: 'name',
        statusToggle: true,
        orderBy: 'name',
        sortFunc: 'ialpha',
        sortOrder: 'ASC',
        makeGuid: 'name',

        field: {
            name: {
                type: 'input',
                label: 'Name',
                defaultValue: '',
                maxLength: 32,
                isNumeric: false
            },
            description: {
                type: 'textarea',
                label: 'Description',
                allowHTML: false,
                editor: false,
                maxLength: 255
            }
        }
    },
    
    /********************
    *   MAP COMMENTS
    ********************/
    map_comment: {
        // config
        name: 'Comments',
        previewField: 'name',
        statusToggle: true,
        orderBy: 'date',
        sortFunc: 'numeric',
        sortOrder: 'DESC',

        field: {
            name: {
                type: 'input',
                label: 'Name',
                defaultValue: '',
                maxLength: 32,
                isNumeric: false
            },
            message: {
                type: 'textarea',
                label: 'Message',
                allowHTML: false,
                editor: false
            }
        },
        
        tool: {
            date: {
                type: 'simpleDate',
                label: 'Date',
                autoLoad: true,
                field: 'date'
            }
        }
    }
};
