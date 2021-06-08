<?php

    namespace Softinline\JCrud;
    
    use Illuminate\Http\Request;

    class JForm
    {
        
        private $_controller;
        private $_item;

        /**
         * return the controller
         * @return string
         */
        public function getController() {

            return $this->_controller;

        }

        /**
         * sets the controller
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
         */
        public function setItem($item) {

            $this->_item = $item;

        }
        
        /**
         * return generated
         * html string
         * for form using a view
         */
        public function form($config, $form) {
                                                
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
         */
        public function submit($config, $form, $method=null, $tab=null, $id=null) {

            $item = null;
            if(!is_null($id)) {
                $model = \App::make('\\App\\Models\\'.$config['model']);
                $item = $model::getById($id);
            }
            $validator = $this->validate($config, $form, $tab);

            $redirectOk = $config['url'];
            $redirectKo = $config['url'];
            
            $msg = is_null($item) ? 'created' : 'updated';
            
            // check Validators
            if(!$validator->fails()) {

                \DB::beginTransaction();

                try {

                    if(array_key_exists('action', $config['forms'])) {
                        $method = '_'.$config['forms']['action'];                            
                    }
                                                                                            
                    // execute
                    $result = $this->_controller::$method($item);
                                                            
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
        
    }
