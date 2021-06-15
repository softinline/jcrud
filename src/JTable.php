<?php

    namespace Softinline\JCrud;
    
    use Illuminate\Http\Request;

    class JTable
    {
        
        private $_id;


        /**
         * constructor
         * @param $id, is the base id for all requests
         * urls, etc...
         */
        public function __construct($id=null) {            
            $this->_id = $id;
        }

        /**
         * return generated
         * html string
         * for table using a view
         * @return string
         */
        public function table($config, $list) {

            // replace dynamic {id} with id
            $config['url'] = str_replace('{id}', $this->_id, $config['url']);
            
            // url parent if has in config
            if(array_key_exists('url_parent', $config)) {
                $config['url_parent'] = str_replace('{id}', $this->_id, $config['url_parent']);
            }
            
            // return view
            return View('softinline::jtable', [
                'config' => $config,
                'list' => $config['lists'][$list]
            ])->render();

        }

    }
