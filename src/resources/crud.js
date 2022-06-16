crud = {
    
    tables: Array(),
    
    mapBoxAccessToken: null,    
    
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
                        var searchable = $(column.footer()).attr('data-searchable');

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

        // capture delete click event on row
        $(document).on('change', '.crud-toggle-enabled',  function() {
            crud.toggleEnable($(this));
        });

        // capture delete click event on row
        $(document).on('click', '.delete',  function() {
            crud.delete($(this));
        });

        // capture select all
        $(document).on('click', '.select-all-btn',  function() {            
            crud.selectAll($(this));
        });

        // capture select one
        $(document).on('click', '.selector',  function() {            
            var id = $(this).attr('id');
            var datatable = $(this).attr('data-datatable');            
            var index = $.inArray(id, crud.tables[datatable].selected);
            if ( index === -1 ) {
                crud.tables[datatable].selected.push(id);
            } 
            else {
                crud.tables[datatable].selected.splice( index, 1 );
            }
        });

    },
    formRequireds: function(frm) {        
        var elements = document.forms[frm].elements;
        for (i=0; i<elements.length; i++) {
            console.log(elements[i]);
            if(elements[i].classList.contains("frm-item-required")) {
                if(elements[i].value == '') {
                    alerts.show('ko', elements[i].getAttribute('data-title') + ' ' + i18n.t('is required'));
                    $("#"+elements[i].name).addClass('required');
                    return false;
                }
            }
        }        
        return true;
    },
    export: function(obj) {
        var url = $(obj).attr('data-url');
        var datatable = $(obj).attr('data-datatable');        
        if(crud.tables[datatable].selected.length > 0) {
            var bConfirm = confirm(i18n.t('are you sure of export') + ' ' + crud.tables[datatable].selected.length+' '+i18n.t('elements'));
            if(bConfirm) {
                $.ajax({
                    method: "post",
                    url: '/'+url+'/export-selected',
                    data: {
                        ids: JSON.stringify(crud.tables[datatable].selected),
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
    selectAll: function(obj) {
        var url = obj.attr('data-url');
        var datatable = obj.attr('data-datatable');
        if(crud.tables[datatable].selected.length > 0) {
            crud.tables[datatable].selected = Array();
            crud.tables[datatable].datatable.ajax.reload(null, false);
        }
        else {            
            $.ajax({
                method: "post",
                url: "/"+url+"/select-all",
                success: function(data) {                    
                    $.each(data.data, function(i, item) {
                        var id = data.data[i].id;
                        var index = $.inArray(id, crud.tables[datatable].selected); 
                        if ( index === -1 ) {
                            crud.tables[datatable].selected.push(id);
                        }
                    });                    
                },
                error: function (xhr, ajaxOptions, thrownError) { 
                    $(".wrapper").LoadingOverlay('hide');
                    alerts.show('ko', i18n.t('there was a problem'));
                },
                complete: function() {
                    $(".wrapper").LoadingOverlay('hide');
                    crud.tables[datatable].datatable.ajax.reload(null, false);
                }            
            });
        }
    },
    toggleEnable: function(obj) {
        var id = obj.attr('data-id');
        var url = obj.attr('data-url');
        var datatable = obj.attr('data-datatable');
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
                crud.tables[datatable].datatable.ajax.reload(null, false);
            }
        });
    },
    delete: function(obj) {    
        
        // get data
        var id = obj.attr('data-id');
        var url = obj.attr('data-url');        
        var title = obj.attr('data-title');

        // create the message
        var message = i18n.t('are you sure to delete');
        if(title != 'undefined' && title != undefined) {
            message = message + ' ['+title+']';
        }
        message = message + ' #'+id;

        // check prompt using swal or alert
        if (typeof Swal === 'object' || typeof Swal === 'function') {
            swal.promptCrudDelete(i18n.t('delete'),  message, 'warning', i18n.t('accept'), true, obj);
        }
        else {
            var bConfirm = confirm(message);
            if(bConfirm) {
                crud.deleteExec(obj);
            }
        }

    },
    deleteExec:function(obj) {
        var id = obj.attr('data-id');
        var url = obj.attr('data-url');
        var datatable = obj.attr('data-datatable');
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
                crud.tables[datatable].datatable.ajax.reload(null, false);
            }
        });
    },
    submit: function(frm) {
        if(typeof CKEDITOR != undefined && typeof CKEDITOR != 'undefined') {
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].updateElement();
            };
        }
        if(crud.formRequireds(frm)) {                        
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
                            if(data.redirect != '') {
                                location.href = data.redirect;
                            }
                        }
                        else {
                            alerts.show('ko', data.message);
                        }
                    },                        
                    complete: function() {
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
    selectPopUpOption: function(field, key) {
        $(".selectable-row").removeClass('selectable-row-selected');
        $("#"+field).val(key);
        $("#selectable-row-"+key).addClass('selectable-row-selected');
    }
}