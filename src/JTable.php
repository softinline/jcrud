<?php

    namespace Softinline\JCrud;
    
    use Illuminate\Http\Request;

    class JTable
    {
        
        private $_controller;
        private $_id;


        /**
         * constructor
         * @param $id, is the base id for all requests
         * urls, etc...
         */
        public function __construct($id=null, $controller=null) {    

            $this->_id = $id;
            $this->_controller = $controller;

        }
        
        /**
         * return the controller
         * @return string
         */
        public function getController() {

            return $this->_controller;

        }

        /**
         * sets the controller
         * @return void
         */
        public function setController($controller) {

            $this->_controller = $controller;

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

            // breadcrumb custom
            $breadcrumb = null;
            if(array_key_exists('breadcrumb', $config['lists'][$list])) {
                $breadcrumbMethod = $config['lists'][$list]['breadcrumb'];
                $breadcrumb = $this->_controller::$breadcrumbMethod(@$this->_item, @$this->_id);
            }
            
            // return view
            return View('softinline::jtable', [
                'config' => $config,
                'list' => $config['lists'][$list],
                'breadcrumb' => $breadcrumb,
                'id' => @$this->_id,
            ])->render();

        }

    }
