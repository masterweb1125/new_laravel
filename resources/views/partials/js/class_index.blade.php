<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    }); 

    function deleteSupervisor(formId, myObj) {

        var div1 = myObj.parentNode.parentNode;
        
        let form1 = new FormData();    
        form1.append("formId", formId);

        var ajaxOptions = {
            url:'/classes/delete_supervisor',
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data: form1,
        };

        var req = $.ajax(ajaxOptions);

        req.done(function(resp){

            let all_teachers = resp.all_teachers;
            let div2 = '';
            for(let i = 0; i < all_teachers.length; i++) {
                div2 += `<option value="` + all_teachers[i].id + `">` + all_teachers[i].name + `</option>`;
            }
            div1.innerHTML = `<select required data-placeholder="Assign" class="form-control " onchange="assignSupervisor(`+ formId +`, this)" data-id="`+ formId +`">
                                                     <option value="">Assign</option>
                                                     ` + div2 + `
                                                  </select>`;
            resp.ok && resp.msg
               ? flash({msg:resp.msg, type:'success'})
               : flash({msg:resp.msg, type:'danger'});
            hideAjaxAlert();
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if(e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if(e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}            
            return e.status;
        });
    }
    function assignSupervisor(formId, myObj) {
      
        let td1 = myObj.parentNode;
        var teacher_id = myObj.options[myObj.selectedIndex].value;   
        
        let form1 = new FormData();    
        form1.append("formId", formId);
        form1.append("teacher_id", teacher_id);
        var ajaxOptions = {
            url:'/classes/assign_supervisor',
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data: form1,
        };

        var req = $.ajax(ajaxOptions);

        req.done(function(resp){
            
            let teacher_name = resp.teacher_name;
            td1.innerHTML = `<div class="d-flex align-items-center justify-content-between">                                        
                                <p style="margin: 0;">` + teacher_name + `</p>
                                <a class="btn" title="Delete this user" onclick="deleteSupervisor(` + formId + `, this);">
                                    <img src="/global_assets/images/icon/delete.png" width="20" height="20"/>
                                </a>
                            </div>`;
            resp.ok && resp.msg
               ? flash({msg:resp.msg, type:'success'})
               : flash({msg:resp.msg, type:'danger'});
            hideAjaxAlert();
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if(e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if(e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}            
            return e.status;
        });
    }

</script>