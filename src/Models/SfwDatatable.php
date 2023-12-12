<?php

    namespace Softinline\JCrud\Models;
        
    use Illuminate\Database\Eloquent\Model;
    
    class SfwDatatable extends Model
    {
        
        // incrementing
        public $incrementing = false;

        // table
        protected $table = 'sfw_datatables';

        /**
         * get by id
         */
	    public static function getById($id) {

		    return self::find($id);

        }
    
    }
