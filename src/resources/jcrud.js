jcrud = {    
    // tables
    // array of datatables
    tables: Array(),
    // mapBoxAccessToken
    // this is used on mapBox js plugin to set the token key
    mapBoxAccessToken: null,    
    // init
    // init jcrud settings
    init:function() {
        
        // extends datatable
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: "/js/datatables/i18n/"+app.locale+".json",
            },
            pageLength : 10,
            stateSave: true,
            initComplete: function () {
                            	
            	// Get table
            	var table = this.api();
                                                                               
                // FIX:ajax use search col
                if(table.settings().ajax.url() != null) {
                                    
                    table.columns().every(function () {    

                        var column = this;
                        var searchable = $(column.footer()).attr('jcrud-data-searchable');

                        if(searchable == "true" || searchable == true) {

                            var input = document.createElement('input');
                            input.setAttribute('class', 'form-control');

                            // Add input for searching                                                
                            $(input).appendTo($(column.footer()).empty());

                            // Add events that make search
                            $('input', column.footer()).on('keyup change', function () {

                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }

                            });

                        }
                        else {

                            var span = document.createElement('span');
                            span.textContent = ' ';

                            // Add input for searching                    
                            $(input).appendTo($(column.footer()).empty());

                        }

                    });
                    
                }
                
                // Restore state
                if(table.state.loaded()!=null) {

                    var state = table.state.loaded();

                    // Fill inputs added with info of search when reloading page 
                    table.columns().eq(0).each(function (colIdx) {    

                        var colSearch = state.columns[colIdx].search;
                        var column = this;

                        if (colSearch.search) {
                            // Only if exists the input
                            if($(table.column(colIdx).footer()).html()!=null) $('input', table.column(colIdx).footer()).val(colSearch.search);
                        }

                    });

                }

            }       

        });

        // datepicker
        $(document).on('focus', ".date-picker", function() {
            $(this).datepicker({
                dateFormat: 'dd/mm/yy',
                showAnim: 'fadeIn'
            });
        });

        // datetimepicker
        $(document).on('focus', ".datetime-picker", function() {
            $(this).datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                "format": "MM/DD/YYYY HH:mm:ss",
                "locale":"es",
                "icons": {
                    "time": "fa fa-clock",
                    "date": "fa fa-calendar",
                    "up": "fa fa-arrow-up",
                    "down": "fa fa-arrow-down",
                    "today":"fa fa-calendar-check"
                }            
            });
        });

        // timepicker
        $(document).on('focus', ".time-picker", function() {
            $(this).datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                "format": "HH:mm:ss",
                "locale":"es",
                "icons": {
                    "time": "fa fa-clock",
                    "date": "fa fa-calendar",
                    "up": "fa fa-arrow-up",
                    "down": "fa fa-arrow-down",
                    "today":"fa fa-calendar-check"
                }
            });
        });

        // capture delete click event on row
        $(document).on('change', '.jcrud-toggle-enabled',  function() {
            jcrud.toggleEnable($(this));
        });

        // capture delete click event on row
        $(document).on('click', '.jcrud-delete',  function() {
            jcrud.delete($(this));
        });

        // capture select all
        $(document).on('click', '.jcrud-select-all-btn',  function() {            
            jcrud.selectAll($(this));
        });

        // capture select one
        $(document).on('click', '.jcrud-selector',  function() {            
            var id = $(this).attr('id');
            var datatable = $(this).attr('jcrud-data-datatable');            
            var index = $.inArray(id, jcrud.tables[datatable].selected);
            if ( index === -1 ) {
                jcrud.tables[datatable].selected.push(id);
            } 
            else {
                jcrud.tables[datatable].selected.splice( index, 1 );
            }
        });

    },
    // formRequireds
    // check for required elements
    formRequireds: function(frm) {
        var elements = document.forms[frm].elements;
        for (i=0; i<elements.length; i++) {
            if(elements[i].classList.contains("jcrud-frm-item-required")) {
                var process = true;
                // check parent visibility
                var elementParent = $(elements[i]).closest('.jcrud-childrens-div');
                if(elementParent.length > 0) {
                    if(!elementParent.is(":visible")) {                        
                        process = false;
                    }
                }
                // if finally need to check
                if(process) {                    
                    if(elements[i].value == '') {                    
                        alerts.show('ko', elements[i].getAttribute('jcrud-data-title') + ' ' + i18n.t('is required'));
                        $("#"+elements[i].name).addClass('jcrud-required');
                        return false;
                    }
                }
            }
        }        
        return true;
    },
    // export
    // export to excel file
    export: function(obj) {
        var url = $(obj).attr('jcrud-data-url');
        var datatable = $(obj).attr('jcrud-data-datatable');        
        if(jcrud.tables[datatable].selected.length > 0) {
            var bConfirm = confirm(i18n.t('are you sure of export') + ' ' + jcrud.tables[datatable].selected.length+' '+i18n.t('elements'));
            if(bConfirm) {
                $.ajax({
                    method: "post",
                    url: '/'+url+'/export-selected',
                    data: {
                        ids: JSON.stringify(jcrud.tables[datatable].selected),
                    },
                    success: function(data) {
                        if (data.success) {
                            var $a=$("<a>");
                            $a.attr("href",data.data);                          
                            $("body").append($a);
                            $a.attr("download",datatable+".xlsx");
                            $a[0].click();
                            $a.remove();
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) { 
                        alerts.show('ko', i18n.t('there was a problem'));
                    },
                    complete: function() {
                        alerts.show('ok', i18n.t('export completed'));
                    }            
                });
            }
        }
        else {
            alerts.show('ko', i18n.t('nothing to export'));
        }
    },
    // selectAll
    // select all elements using sessions in controller stored
    selectAll: function(obj) {
        var url = obj.attr('jcrud-data-url');
        var datatable = obj.attr('jcrud-data-datatable');
        if(jcrud.tables[datatable].selected.length > 0) {
            jcrud.tables[datatable].selected = Array();
            jcrud.tables[datatable].datatable.ajax.reload(null, false);
        }
        else {            
            $.ajax({
                method: "post",
                url: "/"+url+"/select-all",
                success: function(data) {                    
                    $.each(data.data, function(i, item) {
                        var id = data.data[i].id;
                        var index = $.inArray(id, jcrud.tables[datatable].selected); 
                        if ( index === -1 ) {
                            jcrud.tables[datatable].selected.push(id);
                        }
                    });                    
                },
                error: function (xhr, ajaxOptions, thrownError) { 
                    $(".wrapper").LoadingOverlay('hide');
                    alerts.show('ko', i18n.t('there was a problem'));
                },
                complete: function() {
                    $(".wrapper").LoadingOverlay('hide');
                    jcrud.tables[datatable].datatable.ajax.reload(null, false);
                }            
            });
        }
    },
    // toggleEnable
    // enable / disable element using toggle-enable
    toggleEnable: function(obj) {
        var id = obj.attr('jcrud-data-id');
        var url = obj.attr('jcrud-data-url');
        var datatable = obj.attr('jcrud-data-datatable');
        $("body").LoadingOverlay('show');
        $.ajax({
            method: "post",
            url: "/"+url+"/"+id+"/toggle-enable",
            success: function(data) {
                if (data.success) {
                    alerts.show('ok', data.message);
                }
                else{
                    alerts.show('ko', data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("body").LoadingOverlay('hide');                
            },
            complete: function() {
                $("body").LoadingOverlay('hide');                
                jcrud.tables[datatable].datatable.ajax.reload(null, false);
            }
        });
    },
    // delete
    // delete elment using swal if was found of confirm js
    delete: function(obj) {    
        
        // get data
        var id = obj.attr('jcrud-data-id');
        var url = obj.attr('jcrud-data-url');        
        var title = obj.attr('jcrud-data-title');

        // create the message
        var message = i18n.t('are you sure to delete');
        if(title != 'undefined' && title != undefined) {
            message = message + ' ['+title+']';
        }
        message = message + ' #'+id+'?';

        // check prompt using swal or alert
        if (typeof Swal === 'object' || typeof Swal === 'function') {
            swal.promptCrudDelete(i18n.t('delete'),  message, 'warning', i18n.t('accept'), true, obj);
        }
        else {
            var bConfirm = confirm(message);
            if(bConfirm) {
                jcrud.deleteExec(obj);
            }
        }

    },
    // deleteExec
    // execute delete action
    deleteExec:function(obj) {
        var id = obj.attr('jcrud-data-id');
        var url = obj.attr('jcrud-data-url');
        var datatable = obj.attr('jcrud-data-datatable');
        $("body").LoadingOverlay('show');
        $.ajax({
            method: "post",
            url: "/"+url+"/"+id+"/delete",
            success: function(data) {                    
                if (data.success) {
                    alerts.show('ok', data.message);
                }
                else{
                    alerts.show('ko', data.message);
                }
            },
            complete: function() {
                $("body").LoadingOverlay('hide');
                jcrud.tables[datatable].datatable.ajax.reload(null, false);
            }
        });
    },
    // submitSelectOptionPostSave
    // if optionsPostSave is used on json defines, show modal for after save action
    submitSelectOptionPostSave:function() {
        $("#modal-options-post-save").modal();
    },
    // submit
    // exeucte submit form
    submit:function(frm) {
        if(typeof CKEDITOR != undefined && typeof CKEDITOR != 'undefined') {
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].updateElement();
            };
        }
        if(jcrud.formRequireds(frm)) {
            var action = $("#"+frm).attr('action');
            var data = new FormData(document.getElementById(frm));
            $("#btn-submit").find('.loading').addClass("fa fa-spinner spin");        
            $("#btn-submit").prop("disabled",true);
            $("body").LoadingOverlay('show');
            if(app.ajax) {
                $.ajax({
                    method: "post",
                    url: action,
                    data: data,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data) {                
                        if(data.success) {
                            alerts.show('ok', data.message);
                            if(data.type == 'redirect') {                                
                                if(data.redirect != '') {
                                    var fullUrl = window.location.origin+"/"+data.redirect;
                                    if(location.href == fullUrl) {
                                        location.reload();
                                    }
                                    else {
                                        location.href = data.redirect;
                                    }                                    
                                }
                            }
                            else if(data.type == 'response') {
                                $("#"+data.element).html(data.html);
                            }
                            else if(data.type == 'download') {
                                var $a=$("<a>");
                                $a.attr("href",data.data);                          
                                $("body").append($a);
                                $a.attr("download",data.file);
                                $a[0].click();
                                $a.remove();
                            }
                        }
                        else {
                            alerts.show('ko', data.message);
                        }
                    },                        
                    complete: function() {
                        $("#modal-options-post-save").modal('hide');
                        $("body").LoadingOverlay('hide');
                        $("#btn-submit").find('.loading').removeClass("fa fa-spinner spin");        
                        $("#btn-submit").prop("disabled", false);
                    }
                });
            }
            else {
                $("#"+frm).submit();
            }
        }      
    },
    // selectPopUpOption
    // select popup option when options are displayed on modal
    selectPopUpOption: function(field, key) {
        $(".jcrud-selectable-row").removeClass('jcrud-selectable-row-selected');
        $("#"+field).val(key);
        $("#jcrud-selectable-row-"+key).addClass('jcrud-selectable-row-selected');
    },
    // updateQueryStringParameter
    // update param value or added in url query string param
    updateQueryStringParameter: function(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            if(value != '') {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else{
                return uri.replace(re, '');
            }
        }
        else {
            return uri + separator + key + "=" + value;
        }
    }
}