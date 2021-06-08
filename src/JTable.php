<?php

    namespace Softinline\JCrud;
    
    use Illuminate\Http\Request;

    class JTable
    {
             
        /**
         * return generated
         * html string
         * for table using a view
         * @return string
         */
        public function table($config, $list) {
                        
            // return view
            return View('softinline::jtable', [
                'config' => $config,
                'list' => $config['lists'][$list]
            ])->render();

        }

    }
