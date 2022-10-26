<p align="center">
    <img src="https://www.softinline.com/wp-content/uploads/2021/04/logo-250-black.png">
</p>

## About Softinline jCrud

Softinline jCrud is a package designed for help in crud datatable and forms generation, 
you can define in json files directly linked with controller / models, adding support for export, select rows, adding more buttons, all using the same style.

## Components

Softinline jCrud is a set of some components:
- jConfig (load files of config) and offers methods for get/set some configs in realtime access
- jTable (from json define file, can show diferent datatables, setting cols, orderable, searchable, etc...), one define file can store multiple lists under the tag list with diferents names, by default
we are using 'index' list
- jForm, this class offers functionality about preapre form with diferent tabs, rows, and multiple input types (text, number, datetime, email, password, editor, custom, view, etc...)
- jcrud.js (this js store information about datatables created, select rows, validation function before submit forms, etc...)

## jForm field types
```
- custom -> call method in controller parent to show some div or HTML code
- file
- date
- datetime
- text
- number
- password
- email
- button -> execute the 'action' key on json defines (normally javascript onclick event)
- checkbox
- textarea
- editor -> replaced with ckeditor needed the plugin installed and initialized
- select -> call controller method defined in 'selector' key on json defines see samples
- select-multiple -> call controller allow multiple selection, controller must return array with 'all' and 'selected' elements
- checkbox-multiple -> same as select-multiple but in checkbox format
- json -> displays information in json field (array) using <pre>
- view -> call view and load, view receives the $config, $item, etc vars with information (see documentation)

*** 
- childrens -> if field element has a children method, the fields inside can be displayed using a validation method that returns true or not, for example if you want to display more fields depends on select response.
```

## Some samples

You can define your files in app/Defines (ex), in your controller you can use jConfig class to load your defines files like this

```php
var $_jconfig;

/**
* Create a new controller instance.
* @return void
*/
public function __construct() {
    $this->_jconfig = new \Softinline\JCrud\JConfig();
    $this->_jconfig->load(app_path().'/Defines/admin-users.json', 'admin-users');
}
```

```json
This is a simple basic sample define for table users:
                                
{
    "admin-users":{
        "model":"User",            
        "title":"users",
        "titleCustom":false,        
        "url":"admin/users",
        "layout":"layouts.other",
        "lists":{
            "index":{
                "wrapper":false,
                "datatable":true,
                "class":false,
                "name":"admin-users",
                "cols":[                    
                    {
                        "name":"id",
                        "field":"id",
                        "orderable":true,
                        "searchable":true
                    },
                    {
                        "name":"created_at",
                        "field":"created_at",
                        "orderable":true,
                        "searchable":true
                    },
                    {
                        "name":"email",
                        "field":"email",
                        "orderable":true,
                        "searchable":true
                    },
                    {                        
                        "name":"actions",
                        "field":"actions",
                        "orderable":false,
                        "searchable":false
                    }
                ],                
                "actions":{
                    "add":true,
                    "export":true,
                    "selector":true
                },
                "options":[
                    ["export", "fa fa-file", "js:crud.export"],
                    ["reports", "fa fa-list", "link:admin/users/reports"]
                ],
                "rowCallBack":false,
                "drawCallBack":false
            }
        },
        "forms":{
            "add":{
                "wrapper":false,
                "tabs":{
                    "general":{                    
                        "key":"general",
                        "title":"general",
                        "type":"form",
                            "fields":[                            
                            {
                                "type":"email",
                                "required":true,
                                "title":"email",
                                "field":"email"
                            },
                            {
                                "type":"password",
                                "required":false,
                                "title":"password",
                                "field":"password"
                            }          
                        ],
                        "extraButtons":[]
                    },
                    "advanced":{
                        "key":"advanced",
                        "title":"advanced",
                        "type":"form",
                            "fields":[                            
                            {
                                "type":"textarea",
                                "required":true,
                                "title":"advanced1",
                                "field":"advanced2"
                            }
                        ],
                        "extraButtons":[]
                    },
                    "images":{
                        "key":"images",
                        "title":"images",
                        "type":"form",
                            "fields":[                            
                            {
                                "type":"file",
                                "required":true,
                                "title":"file",
                                "field":"file"
                            }
                        ],
                        "extraButtons":[]
                    }
                }
            },
            "edit":{
                "wrapper":false,
                "field_title":"email",
                "tabs":{
                    "general":{                    
                        "key":"general",
                        "title":"general",
                        "type":"form",
                            "fields":[                            
                            {
                                "type":"email",
                                "required":true,
                                "title":"email",
                                "field":"email"
                            },
                            {
                                "type":"password",
                                "required":false,
                                "title":"password",
                                "field":"password"
                            }                            
                        ],
                        "extraButtons":[]
                    }                    
                }
            }
        }
    }
}
```

if you want use rows and cols based on bootstrap you can use a special type field 'row' like this, each you can have n fields of the every type (text, password, editor, date, etc...)

```json
{
    "type":"row",
    "fields":[
        {
            "type":"text",
            "required":false,
            "title":"field1",
            "field":"field1"
        },
        {
            "type":"text",
            "required":false,
            "title":"field2",
            "field":"field2"
        },
    ]
}
```

## License

The Softinline/JCrud is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
