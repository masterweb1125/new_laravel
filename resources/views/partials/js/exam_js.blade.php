    
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    }); 

    var exam_index_tbody = document.getElementById('exam_index_tbody');
    getInitExam();

    // for manage exam tab
    function getInitExam() {

        var ajaxOptions = {
            url:'exam_index',
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data:''
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            console.log('======= residences ===============');
            console.log(resp);
            let i = 0;
            let bb = '';
            for(let entry of resp.exams) {
                i++;
                bb += `<tr>
                        <td>`+ i +`</td>
                        <td>`+ entry.type +`</td>
                        <td>`+ entry.name +`</td><td><ul>`;
                for(let ef of resp.examforms) {
                    if(entry.id == ef.exam_id) { 
                        for(let f of resp.forms) {
                            if (f.id == ef.form_id) bb += `<li>Form `+ f.name +`</li>`;
                        }                        
                    }
                }
                bb +=   `</ul></td><td>Term`+ entry.term +`</td>
                        <td>`+ entry.year +`</td>
                        <td class="text-center">
                            <button type="button" class="btn" onclick="onEditExam('`+ i +`', '`+ entry.id +`', '`+ entry.type +`', '`+ entry.name +`', '`+ entry.term +`', '`+ entry.year +`', this);" style="background: white;">
                                <img src="global_assets/images/icon/edit.png" width=25 height=25 />
                            </button>
                            <button type="button" class="btn" onclick="onDeleteExam(`+ entry.id +`, this);" style="background: white;" >
                                <img src="global_assets/images/icon/delete.png" width=25 height=25 />
                            </button>
                        </td>
                    </tr>`;
                
            }
                            

            exam_index_tbody.innerHTML = bb;
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if (e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if (e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}            
            return e.status;
        });

    }
    let origin_type, origin_name, origin_term, origin_year, origin_i, origin_id, edit_status = false;
    let post_type, post_name, post_term, post_year, post_id;
    function onEditExam(i, id, type, name, term, year, myObj) {
        
        if (edit_status == true) {
            
            let origin_ss = exam_index_tbody.children[origin_i - 1].children;
            origin_ss[1].innerHTML= origin_type;
            origin_ss[2].innerHTML = origin_name;
            origin_ss[3].innerHTML = 'Term ' + origin_term;
            origin_ss[4].innerHTML = origin_year;
            origin_ss[5].innerHTML = `<button type="button" class="btn" onclick="onEditExam('`+ origin_i +`', '`+ origin_id +`', '`+ origin_type +`', '`+ origin_name +`', '`+ origin_term +`', '`+ origin_year +`', this);" style="background: white;">
                                <img src="global_assets/images/icon/edit.png" width=25 height=25 />
                            </button>
                            <button type="button" class="btn" onclick="onDeleteExam('`+ origin_id +`', this);" style="background: white;" >
                                <img src="global_assets/images/icon/delete.png" width=25 height=25 />
                            </button>`;
            edit_status = false;
        }
        origin_i = i; 
        origin_id = id; post_id = id;
        origin_name = name; post_name = name;
        origin_term = term; post_term = term;
        origin_type = type; post_type = type;
        origin_year = year; post_year = year;

        let ss = myObj.parentNode.parentNode.children;
        ss[1].innerHTML=`<select data-placeholder="Select Type" class="form-control" onchange="buffExamType(this);">
                            <option `+ (type == 'Ordinary_Exam' ? 'selected' : '') +` value="Ordinary_Exam">Ordinary_Exam</option>
                            <option  `+ (type == 'Consolidated_Exam' ? 'selected' : '') +` value="Consolidated_Exam">Consolidated_Exam</option>
                            <option  `+ (type == 'Year_Average' ? 'selected' : '') +` value="Year_Average">Year_Average</option>
                            <option  `+ (type == 'KCSE' ? 'selected' : '') +` value="KCSE">KCSE</option>
                        </select>`;
        ss[2].innerHTML = `<input type="text" value="`+ name +`"  onchange="buffExamName(this);" />`;
        ss[3].innerHTML = `<select data-placeholder="Select Teacher" class="form-control" onchange="buffExamTerm(this);">
                                <option `+ (term == 1 ? 'selected' : '') +` value="1">First Term</option>
                                <option `+ (term == 2 ? 'selected' : '') +` value="2">Second Term</option>
                                <option `+ (term == 3 ? 'selected' : '') +` value="3">Third Term</option>
                            </select>`;
        ss[4].innerHTML = `<select data-placeholder="Select Teacher" class="form-control" onchange="buffExamYear(this);">
                            <option `+ (year == 2022 ? 'selected' : '') +` value="2022">2022</option>
                            <option `+ (year == 2021 ? 'selected' : '') +` value="2021">2021</option>
                            <option `+ (year == 2020 ? 'selected' : '') +` value="2020">2020</option>
                            <option `+ (year == 2019 ? 'selected' : '') +` value="2019">2019</option>
                            <option `+ (year == 2018 ? 'selected' : '') +` value="2018">2018</option>
                        </select>`;
        ss[5].innerHTML = `<button type="button" class="btn" onclick="onUpdate(this);" style="background: white;">
                            <img src="global_assets/images/icon/save.png" width=25 height=25 />
                        </button>
                        <button type="button" class="btn" onclick="onCancel(this);" style="background: white;" >
                            <img src="global_assets/images/icon/cancel.png" width=25 height=25 />
                        </button>`;
        edit_status = true;

    }

    function onCancel(myObj) {
        
        let ss = myObj.parentNode.parentNode.children;
        ss[1].innerHTML= origin_type;
        ss[2].innerHTML = origin_name;
        ss[3].innerHTML = 'Term ' + origin_term;
        ss[4].innerHTML = origin_year;
        ss[5].innerHTML = `<button type="button" class="btn" onclick="onEditExam('`+ origin_i +`', '`+ origin_id +`', '`+ origin_type +`', '`+ origin_name +`', '`+ origin_term +`', '`+ origin_year +`', this);" style="background: white;">
                            <img src="global_assets/images/icon/edit.png" width=25 height=25 />
                        </button>
                        <button type="button" class="btn" onclick="onDeleteExam('`+ origin_id +`', this);" style="background: white;" >
                            <img src="global_assets/images/icon/delete.png" width=25 height=25 />
                        </button>`;
        edit_status = false;
    }

    function buffExamType(myObj) {
        console.log(myObj);
        post_type = myObj.options[myObj.selectedIndex].value;
        console.log('post_type ' + post_type);
    }

    function buffExamTerm(myObj) {
        console.log(myObj);
        post_term = myObj.options[myObj.selectedIndex].value;
        console.log('post_term ' + post_term);
    }

    function buffExamYear(myObj) {
        console.log(myObj);
        post_year = myObj.options[myObj.selectedIndex].value;
        console.log('post_year ' + post_year);
    }

    function buffExamName(myObj) {
        console.log(myObj);
        post_name = myObj.value;
        console.log('post_name ' + post_name);
    }
    
    function onUpdate(myObj) {
        let form1 = new FormData();    
        form1.append("type", post_type);
        form1.append("name", post_name);
        form1.append("term", post_term);
        form1.append("year", post_year);
        form1.append("id", post_id);

        var ajaxOptions = {
            url:'exam_update',
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
            let ss = myObj.parentNode.parentNode.children;
            
            ss[1].innerHTML= post_type;
            ss[2].innerHTML = post_name;
            ss[3].innerHTML = 'Term ' + post_term;
            ss[4].innerHTML = post_year;
            ss[5].innerHTML = `<button type="button" class="btn" onclick="onEditExam('`+ origin_i +`', '`+ post_id +`', '`+ post_type +`', '`+ post_name +`', '`+ post_term +`', '`+ post_year +`', this);" style="background: white;">
                                <img src="global_assets/images/icon/edit.png" width=25 height=25 />
                            </button>
                            <button type="button" class="btn" onclick="onDeleteExam('`+ post_id +`', this);" style="background: white;" >
                                <img src="global_assets/images/icon/delete.png" width=25 height=25 />
                            </button>`;
            edit_status = false;
            
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if (e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if (e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}            
            return e.status;
        });
    }

    function onDeleteExam(id, myObj) {
        console.log('id ' + id);
        console.log('myObj ' + myObj);
        let form1 = new FormData();    
        form1.append("id", id);

        var ajaxOptions = {
            url:'exam_delete',
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
            if(resp.ok == true) myObj.parentNode.parentNode.remove();
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if (e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if (e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}            
            return e.status;
        });
    }

    // for creating new exam
    var form = $(this);
    var exam_type_rad_elem = document.querySelectorAll('input[type=radio]');
    var exam_name_input_elem = document.querySelector('#exam_name');
    var exam_term_select_elem = document.querySelector('#exam_term');
    var exam_year_select_elem = document.querySelector('#exam_year');
    var exam_form = document.querySelectorAll('input[class="exam_form my-2 mx-3"]');
    var create_exam_submit_btn = document.querySelector('#create-exam-btn');

    $('#create_exam_form').on('submit', function(e) {
        e.preventDefault(); 
        
        let exam_type = '';
        for(let entry of exam_type_rad_elem) {
            if (entry.checked == true) {
                exam_type = entry.value;
                break;
            }
        }
        
        var exam_name = exam_name_input_elem.value;
        var exam_term = exam_term_select_elem.options[exam_term_select_elem.selectedIndex].value;
        var exam_year = exam_year_select_elem.options[exam_year_select_elem.selectedIndex].value;

        var exam_forms = [];

        for(let entry of exam_form) {
            if(entry.checked == true) {
                let min_subject_cnt_id = 'min_subject_cnt' + entry.value;
                let min_subject_cnt_elem = document.getElementById(min_subject_cnt_id);
                exam_forms.push({'form_id': entry.value, 'min_subject_cnt': parseInt(min_subject_cnt_elem.value)});
            }
        }
        disableBtn($(create_exam_submit_btn));

        let form1 = new FormData();    
        form1.append("exam_type", exam_type);
        form1.append("exam_name", exam_name);
        form1.append("exam_term", exam_term);
        form1.append("exam_year", exam_year);
        form1.append("exam_forms", JSON.stringify(exam_forms));

        var ajaxOptions = {
            url:form.attr('action'),
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data:form1
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            console.log('======= residences ===============');
            console.log(resp);
            resp.ok && resp.msg
            ? flash({msg:resp.msg, type:'success'})
            : flash({msg:resp.msg, type:'danger'});
            hideAjaxAlert();
            enableBtn($(create_exam_submit_btn));
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){var errors = e.responseJSON.errors; displayAjaxErr(errors);}
            if (e.status == 500){displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])}
            if (e.status == 404){displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])}            
            return e.status;
        });
    });
</script>