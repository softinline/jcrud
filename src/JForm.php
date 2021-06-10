<?php

    namespace Softinline\JCrud;
    
    use Illuminate\Http\Request;

    class JForm
    {
        
        private $_controller;
        private $_item;
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
         * return the item
         * @return string
         */
        public function getItem() {

            return $this->_item;

        }

        /**
         * sets the item
         * @return void
         */
        public function setItem($item) {

            $this->_item = $item;

        }
        
        /**
         * return generated
         * html string
         * for form using a view
         * @param array $config with the array of config
         * @param string $form, string name of the form
         * @return string
         */
        public function form($config, $form) {

            // replace dynamic {id} with id
            $config['url'] = str_replace('{id}', $this->_id, $config['url']);
                                                
            // return view
            return View('softinline::jform', [
                'config' => $config,
                'form' => $config['forms'][$form],
                'controller' => $this->_controller,
                'item' => $this->_item,
            ])->render();

        }

        /**
         * execute validations
         * process the form, 
         * and return the response
         * @return operation
         */
        public function submit($config, $form, $method=null, $tab=null, $id=null) {

            // replace dynamic {id} with id
            $config['url'] = str_replace('{id}', $this->_id, $config['url']);

            $item = null;
            if(!is_null($id)) {
                $model = \App::make('\\App\\Models\\'.$config['model']);
                $item = $model::getById($id);
            }
                                
            $redirectOk = $config['url'];
            $redirectKo = $config['url'];
                                    
            $msg = is_null($item) ? 'created' : 'updated';
                                    
            // check Validators
            $validator = $this->validate($config, $form, $tab);

            if(!$validator->fails()) {
                
                \DB::beginTransaction();

                try {

                    if(array_key_exists('action', $config['forms'])) {
                        $method = '_'.$config['forms']['action'];                            
                    }
                                                                                            
                    // execute
                    $result = $this->_controller::$method($item, $this->_id);
                                                            
                    if($result) {

                        \DB::commit();

                        if(\Request::ajax()) {

                            return \Response::json([
                                'success' => true,
                                'message' => ucfirst(trans('messages.'.$msg.'_ok')),
                                'type' => 'redirect',
                                'redirect' => $redirectOk,
                            ], 200);

                        }
                        else {
                            
                            \Session::flash('flash_message', ucfirst(trans('messages.'.$msg.'_ok')));
                            return \Redirect::to($redirectOk);
                                                            
                        }
            
                    }
                    else {

                        \DB::rollback();

                        if(\Request::ajax()) {

                            return \Response::json([
                                'success' => false,
                                'message' => ucfirst(trans('messages.'.$msg.'_error')),
                            ], 200);

                        }
                        else {
                                                            
                            return \Redirect::to($redirectKo)
                                ->withInput()
                                ->with('message', ucfirst(trans('messages.'.$msg.'_error')));
                            
                        }

                    }              

                }
                catch(\Exception $e) {

                    \DB::rollback();

                    \Log::error('Error Message '.$e->getMessage());
                    \Log::error('Error Trace '.$e->getTraceAsString());

                    if(\Request::ajax()) {

                        return \Response::json([
                            'success' => false,
                            'message' => ucfirst(trans('messages.'.$msg.'_error')),
                        ], 200);

                    }
                    else {

                        return \Redirect::to($redirectKo)
                            ->withInput()
                            ->with('message', ucfirst(trans('messages.'.$msg.'_error')));

                    }

                }

            }
            
            else {

                if(\Request::ajax()) {

                    return \Response::json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                    ], 200);                

                }
                else {

                    return \Redirect::to($redirectKo)
                        ->withInput()
                        ->with('message', $validator->errors()->first());

                }

            }
            
        }

        /**
         * perform the validation
         * rules
         */
        private function validate($config, $form, $tab) {

            // prepare array of data
            $validatorRules = [];

            if($config['forms'][$form]['tabs'][$tab]["type"] == 'form') {
                foreach($config['forms'][$form]['tabs'][$tab]['fields'] as $field) {
                    if($field['type'] != 'view') {
                        if($field['type'] == 'row') {
                            foreach($field['fields'] as $subfield) { 
                                if($subfield['required'] === 'true' || $subfield['required'] === true ) {
                                    $validatorRules[$subfield['field']] = 'required';
                                }    
                            }
                        }
                        else {
                            if($field['required'] === 'true' || $field['required'] === true ) {
                                $validatorRules[$field['field']] = 'required';
                            }
                        }
                    }
                }
            }             

            // make validator
            $validator = \Validator::make(\Request::all(), $validatorRules);

            return $validator;

        }

        /**
         * delete the item id
         */
        public function delete($config, $method) {

            // ajax request
            $ajax = '';
            if(array_key_exists('ajax', $config)) {
                $ajax = $config['ajax'] ? '#' : '';
            }

            // replace dynamic {id} with id
            $config['url'] = $ajax.str_replace('{id}', $this->_id, $config['url']);
                                                
            if($this->_item) {
                
                $result = $this->_controller::$method($this->_item);

                // attempt to delete
                if($result) {

                    if(\Request::ajax()) {

                        return \Response::json([
                            'success' => true,
                            'message' => ucfirst(trans('messages.deleted_ok')),
                            'type' => 'redirect',
                            'redirect' => $config['url'],
                        ], 200);

                    }
                    else {

                        \Session::flash('flash_message', ucfirst(trans('messages.deleted_ok')));
                        return \Redirect::to($config['url']);

                    }
            
                }
                else {

                    if(\Request::ajax()) {

                        return \Response::json([
                            'success' => false,
                            'message' => ucfirst(trans('messages.deleted_error')),
                        ], 200);

                    }
                    else {

                        return \Redirect::to($config['url'])
                            ->withInput()
                            ->with('message', ucfirst(trans('messages.deleted_error')));

                    }

                }

            }
            else {

                if(\Request::ajax()) {

                    return \Response::json([
                        'success' => false,
                        'message' => ucfirst(trans('messages.item_not_found')),
                    ], 200);

                }
                else {

                    return \Redirect::to($config['url'])
                        ->withInput()
                        ->with('message', ucfirst(trans('messages.item_not_found')));

                }

            }

        }

        /**
         * encapsulate export method
         * using Spreadsheet package
         * @return string with data
         */
        public function export($config, $method) {
                        
            // increase vars
            ini_set('max_input_vars', 3000);
            ini_set('suhosin.get.max_vars', 3000);
            ini_set('suhosin.post.max_vars', 3000);
            ini_set('suhosin.request.max_vars', 3000);
            set_time_limit(0);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();            
            $sheet = $spreadsheet->getActiveSheet();

            // call method in class child
            $sheet = $this->_controller::$method($sheet);

            // create writer
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            // prepare for download
            header("Content-Type:application/vnd.ms-excel; charset=utf-8");
            header("Content-type:application/x-msexcel; charset=utf-8");
            header('Content-Disposition: attachment; filename="file.xlsx"');
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            ob_start();
            $writer->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();

            // send to browser
            return \Response::json([
                'success' => true,
                'data'=>"data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            ]);        

        }

        /**
         * toggle enable
         */
        public function toggleEnable($config, $method) {

            // replace dynamic {id} with id
            $config['url'] = str_replace('{id}', $this->_id, $config['url']);

            if($this->_item) {

                // call method in class child
                $response = $this->_controller::$method($this->_item);
                
                if($response) {
                    
                    return \Response::json([
                        'success' => true,
                        'message' => ucfirst(trans('messages.updated_ok')),
                        'type' => 'redirect',
                        'redirect' => $config['url'],
                    ], 200);

                }
                else {

                    return \Response::json([
                        'success' => false,
                        'message' => ucfirst(trans('messages.updated_error')),
                    ], 200);

                }

            }
            else {

                return \Response::json([
                    'success' => false,
                    'message' => ucfirst(trans('messages.item_not_found')),
                ], 200);

            }

        }
        
    }