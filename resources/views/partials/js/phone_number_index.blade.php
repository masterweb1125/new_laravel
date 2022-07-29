<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    }); 

    var phone_number_submit_btn = document.querySelector('#phone_number_submit');
    $('form.phone-number-store').on('submit', function(ev){
        ev.preventDefault();   

        var client_name = $('#client_name').val();    
        var phone_number = document.getElementById('phone_number').value;   

        disableBtn($(phone_number_submit_btn));

        let form1 = new FormData();    
        form1.append("client_name", client_name);
        form1.append("phone_number", phone_number);

        var ajaxOptions = {
            url:$(this).attr('action'),
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data:form1
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            console.log('======= response data ===============');
            console.log(resp);
            resp.ok && resp.msg
            ? flash({msg:resp.msg, type:'success'})
            : flash({msg:resp.msg, type:'danger'});
            
            enableBtn($(phone_number_submit_btn));
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if (e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if (e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}   
            enableBtn($(phone_number_submit_btn));         
            return e.status;
        });


    });


    function deletePhoneNumber(id, myObj) {

        var div1 = myObj.parentNode.parentNode;
        
        let form1 = new FormData();    
        form1.append("id", id);

        var ajaxOptions = {
            url:'/phone_numbers/delete',
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data: form1,
        };

        var req = $.ajax(ajaxOptions);

        req.done(function(resp){
            console.log('result resp')
            console.log(resp);
            
            resp.ok && resp.msg
               ? flash({msg:resp.msg, type:'success'})
               : flash({msg:resp.msg, type:'danger'});
            hideAjaxAlert();
            div1.remove();
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if(e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Phone number is duplicated. Try again!'])}
            if(e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' Not Page! '])}            
            return e.status;
        });
    }
    

</script>