crud = {
    tables: Array(),
    init:function() {
        // capture delete click event on row
        $(document).on('click', '.delete',  function() {
            crud.delete($(this));
        });            
        // capture select all
        $(document).on('click', '.select-all-btn',  function() {            
            crud.selectAll($(this));
        });  
    },
    formRequireds: function(frm) {                
        var elements = document.forms[frm].elements;
        for (i=0; i<elements.length; i++){        
            if(elements[i].classList.contains("frm-item-required")) {                
                if(elements[i].value == '') {                    
                    alerts.show('ko', $("#"+elements[i].name).prev('label').html() + ' ' + i18n.t('is required'));
                    $("#"+elements[i].name).addClass('required');
                    return false;
                }
            }
        }        
        return true;
    },
    export: function(obj) {
        var entity = $(obj).attr('data-entity');
        var datatable = $(obj).attr('data-datatable');        
        if(crud.tables[datatable].selected.length > 0) {
            var bConfirm = confirm(i18n.t('are you sure of export') + ' ' + crud.tables[datatable].selected.length+' '+i18n.t('elements'));
            if(bConfirm) {
                $.ajax({
                    method: "post",
                    url: '/'+entity+'/export-selected',
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
        var entity = obj.attr('data-entity');
        var datatable = obj.attr('data-datatable');
        if(crud.tables[datatable].selected.length > 0) {
            crud.tables[datatable].selected = Array();
            crud.tables[datatable].datatable.ajax.reload(null, false);
        }
        else {            
            $.ajax({
                method: "post",
                url: "/"+entity+"/select-all",
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
        var entity = obj.attr('data-entity');
        var datatable = obj.attr('data-datatable');
        $("body").LoadingOverlay('show');
        $.ajax({
            method: "post",
            url: "/"+entity+"/"+id+"/toggle-enable",
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
        var id = obj.attr('data-id');
        var entity = obj.attr('data-entity');
        var datatable = obj.attr('data-datatable');
        var bConfirm = confirm(i18n.t('are you sure to delete') + ' ['+entity+'] #'+id+'?');
        if(bConfirm) {
            $("body").LoadingOverlay('show');
            $.ajax({
                method: "post",
                url: "/"+entity+"/"+id+"/delete",
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
        }
    },
    submit: function(frm) {
        if(crud.formRequireds(frm)) {
            var action = $("#"+frm).attr('action');            
            if(typeof CKEDITOR != undefined && typeof CKEDITOR != 'undefined') {
                for (var i in CKEDITOR.instances) {
                    CKEDITOR.instances[i].updateElement();
                };
            }
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
    }
}