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

            // breadcrumb custom
            $breadcrumb = null;
            if(array_key_exists('breadcrumb', $config['forms'][$form])) {
                $breadcrumbMethod = $config['forms'][$form]['breadcrumb'];
                $breadcrumb = $this->_controller::$breadcrumbMethod(@$this->_item, @$this->_id);
            }

            // return view
            return View('softinline::jform', [
                'id' => $this->_id,
                'config' => $config,
                'form' => $config['forms'][$form],
                'controller' => $this->_controller,
                'item' => $this->_item,
                'breadcrumb' => $breadcrumb,
            ])->render();

        }

        /**
         * execute validations
         * process the form, 
         * and return the response
         * @return operation
         */
        public function submit($config, $form, $method=null, $tab=null, $id=null) {

            // ajax request
            $ajax = '';
            if(array_key_exists('ajax', $config)) {
                $ajax = $config['ajax'] ? '#' : '';
            }

            // replace dynamic {id} with id
            $config['url'] = $ajax.str_replace('{id}', $this->_id, $config['url']);
            
            // redirects url defailt
            $redirectOk = $config['url'].'#tab_'.\Request::get('tab');
            $redirectKo = $config['url'].'#tab_'.\Request::get('tab');

            // override redirectKo
            if(array_key_exists('redirectKo', $config['forms'][$form])) {
                if($config['forms'][$form]['redirectKo'] == 'back') {                                        
                    $redirectKo = url()->previous().'#tab_'.\Request::get('tab');
                }
                else {                    
                    $redirectKo = $ajax.str_replace('{id}', $this->_id, $config['forms'][$form]['redirectKo'] ).'#'.\Request::get('tab');
                }                
            }

            // override redirectOk
            if(array_key_exists('redirectOk', $config['forms'][$form])) {
                if($config['forms'][$form]['redirectOk'] == 'back') {                                        
                    $redirectOk = url()->previous().'#tab_'.\Request::get('tab');
                }
                else {                    
                    $redirectOk = $ajax.str_replace('{id}', $this->_id, $config['forms'][$form]['redirectOk'] ).'#'.\Request::get('tab');
                }                
            }
                                                
            // create or update message
            $msg = is_null($id) ? 'created' : 'updated';
                                    
            // check Validators
            $validator = $this->validate($config, $form, $tab);

            if(!$validator->fails()) {
                
                \DB::beginTransaction();

                try {
                    
                    // set custom action method instead create or update
                    if(array_key_exists('action', $config['forms'])) {
                        $method = '_'.$config['forms']['action'];                            
                    }
                                                  
                    // execute
                    $result = $this->_controller::$method($this->_item, $this->_id);

                    // default response info
                    $successStatus = $result;
                    $successMessageOk = ucfirst(trans('messages.'.$msg.'_ok'));
                    $successMessageKo = ucfirst(trans('messages.'.$msg.'_error'));

                    // is response is array override with other data
                    if(is_array($result)) {
                        $successStatus = $result['success'];
                        $successMessageOk = $result['message'];
                        $successMessageKo = $result['message'];
                    }
                                                          
                    if($successStatus) {

                        // after execute method check if redirectOk must be changed
                        if(array_key_exists('optionsPostSave', $config['forms'][$form])) {
                            if(\Request::get('optionsPostSave') != '') {
                                $redirectOk = $ajax.str_replace('{id}', $result->id, $config['forms'][$form]['optionsPostSave'][\Request::get('optionsPostSave')][3]);
                            }
                        }

                        \DB::commit();

                        if(\Request::ajax()) {

                            return \Response::json([
                                'success' => true,
                                'message' => $successMessageOk,
                                'type' => 'redirect',
                                'redirect' => $redirectOk,
                            ], 200);

                        }
                        else {

                            \Session::flash('message_success', $successMessageOk);

                            return \Redirect::to($redirectOk);

                        }
            
                    }
                    else {

                        \DB::rollback();

                        if(\Request::ajax()) {

                            return \Response::json([
                                'success' => false,
                                'message' => $successMessageKo,
                            ], 200);

                        }
                        else {

                            \Session::flash('message_error', $successMessageKo);
                                                
                            return \Redirect::to($redirectKo)
                                ->withInput();

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

                        \Session::flash('message_error', ucfirst(trans('messages.'.$msg.'_error')));
                        
                        return \Redirect::to($redirectKo)
                            ->withInput();

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
                        ->with('message_error', $validator->errors()->first());

                }

            }
            
        }

        /**
         * perform the validation
         * rules
         */
        private function validate($config, $form, $tab) {

            $validatorRules = [];

            if($config['forms'][$form]['tabs'][$tab]["type"] == 'form') {

                foreach($config['forms'][$form]['tabs'][$tab]['fields'] as $field) {

                    if($field['type'] != 'view') {

                        if($field['type'] == 'row' || $field['type'] == 'fieldset') {

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
         * delete the item
         */
        public function delete($config, $method) {

            // ajax request
            $ajax = '';
            if(array_key_exists('ajax', $config)) {
                $ajax = $config['ajax'] ? '#' : '';
            }

            // replace dynamic {id} with id
            $config['url'] = $ajax.str_replace('{id}', $this->_id, $config['url']);

            // if has item
            if($this->_item) {

                try {

                    // call to method
                    $response = $this->_controller::$method($this->_item);

                    $success = false;

                    // if the response isnt array
                    if(!is_array($response)) {

                        if($response) {
                            $success = true;
                            $msg = ucfirst(trans('messages.deleted_ok'));
                        }
                        else {
                            $success = false;
                            $msg = ucfirst(trans('messages.deleted_ko'));
                        }

                    }
                    else {

                        $success = $response['success'];
                        $msg = $response['message'];

                    }

                    if($success) {

                        \DB::commit();

                        if(\Request::ajax()) {

                            return \Response::json([
                                'success' => true,
                                'message' => $msg,
                                'type' => 'redirect',
                                'redirect' => $config['url'],
                            ], 200);

                        }
                        else {

                            \Session::flash('flash_message', $msg);
                            return \Redirect::to($config['url']);

                        }
                    }
                    else {
                        
                        if(\Request::ajax()) {

                            return \Response::json([
                                'success' => false,
                                'message' => $msg,
                            ], 200);

                        }
                        else {

                            return \Redirect::to($config['url'])
                                ->withInput()
                                ->with('message', $msg);

                        }
                    }
                    
                }
                // catch error
                catch(\Exception $e) {

                    \DB::rollback();

                    \Log::error('Error Message '.$e->getMessage());
                    \Log::error('Error Trace '.$e->getTraceAsString());

                    if(\Request::ajax()) {

                        return \Response::json([
                            'success' => false,
                            'message' => ucfirst(trans('messages.deleted_error')),
                        ], 200);

                    }
                    else {

                        return \Redirect::to($redirectKo)
                            ->withInput()
                            ->with('message', ucfirst(trans('messages.deleted_error')));
                    }

                }

            }
            // no item found
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
         * process form
         */
        public function process($config, $form, $tab, $item) {

            foreach($config['forms'][$form]['tabs'][$tab]['fields'] as $field) {

                if(\Request::has($field['field'])) {

                    if($field['type'] == 'text' || $field['type'] == 'textarea' || $field['type'] == 'editor' || $field['type'] == 'number') {
                        $item->{$field['field']} = \Request::get($field['field']) != '' ? \Request::get($field['field']) : null;
                    }

                    if($field['type'] == 'checkbox') {                        
                        $item->{$field['field']} = \Request::get($field['field']) == 1 ? 1 : 0;
                    }

                }

            }

            if($item->save()) {
                return true;
            }

            return false;
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
            
            // ajax request
            $ajax = '';
            if(array_key_exists('ajax', $config)) {
                $ajax = $config['ajax'] ? '#' : '';
            }

            // replace dynamic {id} with id
            $config['url'] = $ajax.str_replace('{id}', $this->_id, $config['url']);

            // if has item call to method
            if($this->_item) {

                // call method in class child
                $response = $this->_controller::$method($this->_item);

                // if the response isnt array
                if(!is_array($response)) {

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

                    if($response['success']) {
                    
                        return \Response::json([
                            'success' => true,
                            'message' => $response['message'],
                            'type' => 'redirect',
                            'redirect' => $config['url'],
                        ], 200);

                    }
                    else {

                        return \Response::json([
                            'success' => false,
                            'message' => $response['message'],
                        ], 200);

                    }

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