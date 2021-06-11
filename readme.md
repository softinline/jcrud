<p align="center">
    <img src="https://www.softinline.com/wp-content/uploads/2021/04/logo-250-black.png">
</p>

## About Softinline jCrud

Softinline jCrud is a package designed for help in crud datatable and forms generation, 
you can define in json files directly linked with controller / models, adding support for export, select rows, adding more buttons, all using the same style.

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
        "title_custom":false,
        "entity":"admin/users",
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
