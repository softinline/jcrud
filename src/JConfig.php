<?php

    namespace Softinline\JCrud;
    
    use Illuminate\Http\Request;

    class JConfig
    {

        private $_config;
        private $_file;

        /**
         * return the file name loaded
         * @return string
         */
        public function getFile() {

            return $this->_file;

        }

        /**
         * return the config loaded
         * @return array
         */
        public function getConfig() {

            return $this->_config;

        }

        /**
         * return the url
         * @return string
         */
        public function getUrl($id=null) {

            return str_replace('{id}', $id, $this->_config['url']);

        }

        /**
         * return the confir list
         */
        public function getListName($list) {

            return $this->_config['lists'][$list]['name'];

        }

        /**
         * return the item
         * @return string
         */
        public function getLayout() {

            return $this->_config['layout'];

        }

        /**
         * sets the item
         * @return void
         */
        public function setLayout($layout) {

            $this->_config['layout'] = $layout;            

        }

        /**
         * load config file
         * and config definition
         * @return bool;
         */
        public function load($file, $cfg) {

            if(file_exists($file)) {

                $this->_file = $file;

                $tmp = json_decode(file_get_contents($this->_file), true);
            
                if(array_key_exists($cfg, $tmp)) {

                    $this->_config = $tmp[$cfg];

                }
                else {

                    \App::abort(400, 'config ['.$cfg.'] not found');

                }

            }
            else {

                \App::abort(400, 'def file ['.$file.'] not found');

            }

        }
        
    }
