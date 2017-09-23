<?php
   require_once("service.php");
   
   $service=new NoticeService();
   
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	   // 使用者權限驗證
	   
	   
	   $user_id = '51';  //當前使用者Id
	   $user_unit = '102000';  //當前使用者部門
	   
	   $service->store($user_id , $user_unit);
	   
	  
	   
       /* 回到列表index */
	    header("Location: http://localhost/tp-notice/index.php");
		exit;
   }
   else
   {
		$id=0;
		if (isset($_GET['id'])) {
			$id=(int)$_GET['id'];
		}
		
		$data=$service->edit($id);

		$notice=$data[0];
		$attachment=$data[1];
		
	
		
		
   }


    
  
?>


<link rel=stylesheet type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel=stylesheet type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel=stylesheet type="text/css" href="./css/edit.css">

<div class="container">

    <h1>發送校務系統通知</h1>
    
<form enctype="multipart/form-data" id="form-notice" method="POST" action="">

   <div class="row">
            <div class="col-md-12">
                <label>通知內容</label>
                <textarea name="Content" class="form-control" rows="6" cols="50" ><?php echo $notice['Content']; ?></textarea>   
				
                <small id="err-Content" class="text-danger" style="display: none;">請輸入通知內容</small>
            </div>

        </div>
		<div class="row">
            <div class="col-md-4 mb-3">
                <label>附加檔案</label>
                <input id="attachment-file" name="Attachment" type="file">
				
				
                <div id="div-exist-attachment" class="form-inline" style="display: none;">
                    <input id="attachment-file_name" class="form-control" value="<?php echo $attachment['Name']; ?>" type="text" disabled> 
                    <button id="btn-del-attachment" class="btn btn-danger btn-sm">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <label>檔案顯示名稱</label>
                <input type="text" name="Attachment_Title" class="form-control" value="<?php echo $attachment['Title']; ?>" >
                <small id="err-filename" class="text-danger" style="display: none;">請輸入檔案顯示名稱</small>
            </div>
        </div>
        
        <div class="row" style="padding-top:10px">

            <div class="col-md-4">
                <label>通知對象身份</label>

                <div class="checkbox">
                    <label>
					
                        <input class="chk-roles" type="checkbox" name="Staff" id="chk-staff" value="<?php echo $notice['Staff']; ?>">
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        職員 <span id="level-text"></span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input class="chk-roles"  type="checkbox" name="Teacher" value="<?php echo $notice['Teacher']; ?>" >
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        教師
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input class="chk-roles"  type="checkbox" name="Student" value="<?php echo $notice['Student']; ?>">
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        學生
                    </label>
                </div>

                <small id="err-roles" class="text-danger" style="display: none;">請選擇通知對象身份</small>

            </div>
            <div class="col-md-8">
                <div id="unit-list" style="display:none">
                    <label>通知對象部門</label>
                    <button id="btn-edit-units" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 編輯
                    </button>
                    <textarea id="unit-names" class="form-control" rows="5" cols="50" disabled></textarea>
                    <input type="text" id="unit-codes" name="Units" value="<?php echo $notice['Units']; ?>" />
                    <small id="err-units" class="text-danger" style="display: none;">請選擇通知對象部門</small>
                </div>
                <div id="class-list" class="pad-top" style="display:none">
                    <label>通知對象班級</label>
                    <button id="btn-edit-classes" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 編輯
                    </button>
                    <textarea id="class-names" class="form-control" rows="5" cols="50" disabled></textarea>
                    <input type="text" id="class-codes" name="Classes" value="<?php echo $notice['Classes']; ?>"  />
                    <small id="err-classes" class="text-danger" style="display: none;">請選擇通知對象班級</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>備註</label>
                <textarea name="PS" class="form-control" rows="3" cols="50"><?php echo $notice['PS']; ?></textarea>
				
            </div>

        </div>
        <div class="row" style="padding-top:10px">
            <div class="col-md-12">
                <button class="btn btn-success" type="submit">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                    存檔
                </button>
				
				
            </div>

        </div>
	    <div class="row" >
            <div class="col-md-12">
				Id:<input type="text" name="ID" value="<?php echo $notice['Id']; ?>"  />
				Reviewed:<input type="text" name="Reviewed" value="<?php echo $notice['Reviewed']; ?>" />
				Levels:<input type="text" name="Levels" value="<?php echo $notice['Levels']; ?>" />
				Attachment_ID:<input type="text" name="Attachment_ID" value="<?php echo $attachment['Id']; ?>"  />
            </div>

        </div>

       

    </form>
    <div class="row">
        <div class="col-md-12">
            select-type:<input id="select-type" type="text" value="" />
            confirm-action:<input id="confirm-action" type="text" value="" />
        </div>

    </div>
   




    <button id="open-custom-modal" type="button" style="display:none" data-toggle="modal" data-target="#custom-modal">Open Modal</button>
    <div class="modal fade" id="custom-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button id="close-custom-modal" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="custom-modal-title"></h4>
                </div>
                <div class="modal-body tree-modal" id="custom-modal-content">
                    <div class="row" style="padding-bottom:10px">
                        <div class="col-md-6">
                            <div class="form-inline">
                                <button id="tree-select-all" class="btn btn-primary btn-sm">全選</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button id="tree-cancel-all" class="btn btn-default btn-sm">全取消</button>
                            </div>
                        </div>
                        <div class="col-md-6" id="div-level">
                            <div class="form-inline">
                                <div class="checkbox">
                                    <label>
                                        <input id="level-one" class="chk-levels" type="checkbox" value="1">
                                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                        一級主管
                                    </label>
                                </div>
                                <div style="padding-left:15px" class="checkbox">
                                    <label>
                                        <input id="level-two" class="chk-levels" type="checkbox" value="2">
                                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                        二級主管
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <ul id="treeview-members"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-select-done" class="btn btn-success">確定</button>

                </div>
            </div>
        </div>
    </div>


    <button id="btn-alert-modal" type="button" data-toggle="modal" data-target="#alert-modal">ALERT</button>
    <div class="modal fade" id="alert-modal" role="dialog">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button id="close-alert" type="button" class="close" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                    <h3><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> </h3>
                </div>
                <div class="modal-body" id="alert-content">

                </div>
                <div class="modal-footer" id="alert-footer">
                    <button type="button" id="btn-confirm-ok" class="btn btn-success">確定</button>

                    &nbsp; &nbsp;
                    <button type="button" id="btn-confirm-cancel" class="btn btn-default">取消</button>
                </div>
            </div>
        </div>
    </div>



</div>   <!--End Container-->

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<script src="./js/treeview.js"></script>



<script type="text/javascript">
    function isTrue(val){
        if(typeof val=='number'){
            return val > 0
        }else if(typeof val=='string'){
            if(val.toLowerCase()=='true') return true
            if(val=='1') return true
            return false
        }else if(typeof val=='boolean'){
            return val
        }
      
        return false
    }
    function setSelectedUnits(unitCodes, unitNames) {
       
        var textCode = '';
        for (var i = 0; i < unitCodes.length; i++) {
            textCode += unitCodes[i];
            if (i < unitCodes.length - 1) textCode += ',';
        }
        $('#unit-codes').val(textCode);

       
       
        var textName = '';
        for (var i = 0; i < unitNames.length; i++) {
            textName += unitNames[i];
            if (i < unitNames.length - 1) textName += ',';
        }


        $('#unit-names').val(textName);

        CloseCustomModal();

        //隱藏err-msg
        $("input[name='Units']").next().hide();
        //$('#unit-list').show();

    }
    
    function setSelectedClasses(codes, names) {

        var textCode = '';
        for (var i = 0; i < codes.length; i++) {
            textCode += codes[i];
            if (i < codes.length - 1) textCode += ',';
        }
        $('#class-codes').val(textCode);



        var textName = '';
        for (var i = 0; i < names.length; i++) {
            textName += names[i];
            if (i < names.length - 1) textName += ',';
        }


        $('#class-names').val(textName);

        CloseCustomModal();

        //隱藏err-msg
        $("input[name='Classes']").next().hide();
        $('#class-list').show();

    }
    function getSelectedUnits() {
        return $('#unit-codes').val();
    }
    function getSelectedClasses() {
        return $('#class-codes').val();
    }
   
    function fetchUnits() {

        //var url = 'http://203.64.37.41:9000/api/units';
        var url = 'http://school/api/units';
        return new Promise((resolve, reject) => {
            $.getJSON(url)
             .done(function (data) {
                 createNodeList(data);
                 resolve(true)
             }).fail(function (error) {
                 reject(error);
             });

        })
    }
    function fetchClasses() {
        //var url = 'http://203.64.37.41:9000/api/classes';
        var url = 'http://school/api/classes';
        return new Promise((resolve, reject) => {
            $.getJSON(url)
             .done(function (data) {
                createNodeList(data);
                resolve(true)
             }).fail(function (error) {
                 reject(error);
             });

        })
    }
    function createNodeList(data) {
      
        var html = '';

        for (var i = 0; i < data.length ; i++) {
            html += getNode(data[i]);
        }

        $("#treeview-members").html(html);
    }
    function getNode(unit) {
        var html = '<li>';

        if (unit.children && unit.children.length) {
            html += ' <i class="fa fa-plus"></i>';
            html += '<label>' + '<input data-name="' + unit.name + '"   data-id="' + unit.code + '"   type="checkbox" />'
            html += unit.name + '</label>';
        } else {
            html += '<label>' + '<input data-name="' + unit.name + '"   data-id="' + unit.code + '" class="hummingbirdNoParent"  type="checkbox" />'
            html += unit.name + '</label>';
        }


        if (unit.children && unit.children.length) {
            html += ' <ul>';
            for (var i = 0; i < unit.children.length ; i++) {
                html += getNode(unit.children[i]);
            }
            html += ' </ul>';
        }

        return html;

    }
    function iniUnitsTree() {
        var treeview = $("#treeview-members");
        var type = getSelectType();
    

        var selected_codes = '';
        if (type == 'unit') {
            selected_codes = getSelectedUnits();
        } else {
           
            selected_codes = getSelectedClasses();
        }

        
      
        treeview.hummingbird();

        if (!selected_codes) return;

        var selected_ids = selected_codes.split(',');
        for (var i = 0; i < selected_ids.length; i++) {
            treeview.hummingbird("checkNode", {
                attr: "data-id",
                name: selected_ids[i],
                expandParents: false
            });
        }
        
        //treeview.on("CheckUncheckDone", function () {
        //    var list = [];
        //    treeview.hummingbird("getChecked", {
        //        attr: "id",
        //        list: list,
        //        OnlyFinalInstance: true
        //    });

        //    alert(list.length);

        //});
    }
    function staffChecked() {
       
       
        return isTrue($("input[type='checkbox'][name='Staff']").val());
    }

    function teacherChecked() {
        return isTrue($("input[type='checkbox'][name='Teacher']").val());
    }

    function studentChecked() {
        return isTrue($("input[type='checkbox'][name='Student']").val());
    }

    

    function onStaffCheckChanged(checked) {
        
        if (checked) {
            var hideLevels = false;
            beginSelectUnits(hideLevels);
            $('#unit-list').show();
            $('#err-roles').hide();
        } else {
            if (!teacherChecked()) {
                $('#unit-list').hide();
            }
            
        }
    }
    function onTeacherCheckChanged(checked) {
       
        if (checked) {
            var hideLevels = true;
            beginSelectUnits(hideLevels);
            $('#unit-list').show();
            $('#err-roles').hide();
        } else {
            if (!staffChecked()) {
                $('#unit-list').hide();
            }
        }
    }
    function onStudentCheckChanged(checked) {
        if (checked) {
            beginSelectClasses();
            $('#class-list').show();
            $('#err-roles').hide();
        } else {
           
            $('#class-list').hide();
        }
    } 
    function beginSelectUnits(hideLevels) {
        setSelectType('unit');
      
        var units = fetchUnits();

        units.then(result => {
            iniUnitsTree();
            if (hideLevels) {
                $('#div-level').hide();
            } else {
                $('#div-level').show();
            }
            
            ShowCustomModal('請選擇發送對象部門');
        })
        .catch(error=> {
            OnError();
        })
    }

    function beginSelectClasses() {
        setSelectType('class');

        var classes = fetchClasses();

        classes.then(result => {
            iniUnitsTree();
          
            $('#div-level').hide();
            ShowCustomModal('請選擇發送對象班級');
        })
        .catch(error=> {
            OnError();
        })
    }

    
    function setSelectType(type) {
        $('#select-type').val(type)
    }
    function getSelectType() {
        return $('#select-type').val();
    }
    function onSelectDone() {
       
        var type = getSelectType();
        var id_list = [];
        $("#treeview-members").hummingbird("getChecked", {
            attr: "data-id",
            list: id_list,
            OnlyFinalInstance: true
        });

        if (!id_list.length) {
            if (type == 'unit') alert('請選擇部門');
            else alert('請選擇班級');

            return false;
        }

        var name_list = [];
        $("#treeview-members").hummingbird("getChecked", {
            attr: "data-name",
            list: name_list,
            OnlyFinalInstance: true
        });

        

        if (type == 'unit') {
            setSelectedUnits(id_list, name_list);
            
            setLevels();
        } else {
            setSelectedClasses(id_list, name_list);
        }
       

    }

    function setLevels()
    {
        var ids = '';
        if ($('#level-one').prop("checked")) ids = '1';

        if ($('#level-two').prop("checked")) {
            if (ids) ids += ',';

            ids += '2';
        }

        $("input[name='Levels']").val(ids);

        setLevelsText();
      

    }
    function getLevels() {
       return $("input[name='Levels']").val();
    }
    function setLevelsText() {
        var levels = getLevels();
        var text = '';
        if (levels) {
            var lavel_ids = levels.split(',');
            if (lavel_ids.indexOf('1') > -1) text += '一級主管';

            if (lavel_ids.indexOf('2') > -1) {
                if (text) text += ',';
                text += '二級主管';
            }

        }

        if (text) text = ' ( ' + text + ' )';

        $('#level-text').text(text);

       
      
    }
    //function onLevelChanged(val, checked) {
    //    alert('onLevelChanged');
    //    var levels = getLevels();
    //    if (checked) {
    //        if (levels) levels += ',' ;
    //        levels += val;
    //    } else {
    //        levels = levels.replace(val, ''); 
    //        if (levels.startsWith(',')) levels = levels.slice(0, 1);
    //        if (levels.endsWith(',')) levels = levels.slice(0, -1);
    //    }
    //    $("input[name='Levels']").val(levels);
    //}

   
    function CloseCustomModal() {
        $('#close-custom-modal').click();
    }

    function ShowCustomModal(title) {
        SetCustomModalTitle(title)
        $('#open-custom-modal').click();
    }

    function SetCustomModalTitle(title) {
        $('#custom-modal-title').text(title);
    }

    function ShowAlert(content,showBtn) {
        $('#alert-content').html(content);

        if (showBtn) {
            $('#alert-footer').show();
        } else {
            $('#alert-footer').hide();
        }
        $('#btn-alert-modal').click();
    }
    function CloseAlert() {
        $('#close-alert').click();
    }
   

    function OnError() {
        alert('系統發生錯誤, 請稍後再試');
    }

   
    function clearErrorMsg(target) {
       
        if (target.name == 'Content') {
            var inputContent = $("textarea[name='Content']");
            inputContent.next().hide();

            return;
        }


        var input = $("input[name='" + target.name + "']");
        
        input.next().hide();
     
    }
    function showErrors(msgs) {
        if (!msgs.length) return;
        var html = '<ul>';
        for (var i = 0; i < msgs.length; i++) {
            html += '<li>' + msgs[i] + '</li>';
           
        }

        html += '</ul>';

        var showBtn = false;
        ShowAlert(html, showBtn)
    }

    function getConfirmType() {
        return $('#confirm-action').val();
    }
    function setConfirmType(value) {
        $('#confirm-action').val(value);
    }

    function getAttachmentId() {
        return  $("input[name='Attachment_ID']").val();
       
    }
    function setAttachmentId(value) {
        $("input[name='Attachment_ID']").val(value);
    }

    function onConfirmOK() {
        CloseAlert();

        var type = getConfirmType();
        if (type == 'del-attachment') {
            delAttachment();
        }
    }

    function delAttachment() {
        alert('delAttachment');

        setAttachmentId('0');
        $("input[name='Attachment_Title']").val('');

        toggleFile();
    }

    function toggleFile(){
        var attachmentId=getAttachmentId();
        if(isTrue(attachmentId)){
            $('#attachment-file').hide();
            $('#div-exist-attachment').show();
        }else{
            $('#attachment-file').show();
            $('#div-exist-attachment').hide();
        }
    }

    function chkRoles() {
        $('.chk-roles').each(function () {
            $selected = isTrue($(this).val());
            $(this).prop("checked", $selected);

        });
    }
    function chkLevels() {
        var levels = getLevels();
        if (levels) {
            var lavel_ids = levels.split(',');
            $('.chk-levels').each(function () {
                $selected = lavel_ids.includes($(this).val());               
                $(this).prop("checked", $selected);

            });

            setLevelsText();
        }
        
    }

   
    function iniEdit() {
        toggleFile();

        chkRoles();
        chkLevels();

        

        var student = isTrue($("input[type='checkbox'][name='Student']").val());
        var teacher = isTrue($("input[type='checkbox'][name='Teacher']").val());
        var staff = isTrue($("input[type='checkbox'][name='Staff']").val());

        if (teacher || staff) {
            $('#unit-list').show();
        }

        if (student) {
            $('#class-list').show();
        }

    }

   

   

    
    $(document).ready(function () {

        iniEdit();

        $("input[type='checkbox'][name='Staff']").change(function () {
            var checked = $(this).prop("checked");
            $(this).val(checked);
            onStaffCheckChanged(checked);
        });
       
        $("input[type='checkbox'][name='Teacher']").change(function () {
            var checked = $(this).prop("checked");
            $(this).val(checked);
            var hideLevels=true;
            onStaffCheckChanged(checked, hideLevels);
        });
        $("input[type='checkbox'][name='Student']").change(function () {
            var checked = $(this).prop("checked");
            $(this).val(checked);
            onStudentCheckChanged(checked);
        });

        //$('.chk-levels').change(function () {
        //    var checked = $(this).prop("checked");
        //    var val = $(this).val();
        //    onLevelChanged(val ,checked);
        //});

        
        


        $('#btn-select-done').click(function () {
            onSelectDone();
        });
        $('#tree-select-all').click(function () {
            $("#treeview-members").hummingbird("checkAll");
        });
        $('#tree-cancel-all').click(function () {
            $("#treeview-members").hummingbird("uncheckAll");
        });


        $('#btn-edit-units').click(function (e) {
            e.preventDefault();
            beginSelectUnits();
        });

        $('#btn-edit-classes').click(function (e) {
            e.preventDefault();
            beginSelectClasses();
        });

        //$("#attachment-file").change(function () {
        //    if (document.getElementById('attachment-file').files.length) {

        //    } else {

        //    }
        //});

        $('#btn-del-attachment').click(function (e) {
            e.preventDefault();
            var content = '<h3>確定要刪除此附加檔案嗎?</h3>';
            var showBtn = true;
            ShowAlert(content, showBtn);

            setConfirmType('del-attachment');
           
        });

        $('#btn-confirm-ok').click(function (e) {
            e.preventDefault();
            onConfirmOK();
        });

        $('#btn-confirm-cancel').click(function (e) {
            e.preventDefault();
            CloseAlert();
        });


        $('#form-notice').keydown(function () {
            clearErrorMsg(event.target);
        });



        $('#form-notice').submit(function (e) {
          
             var canSubmit = true;
             var errMsgs = [];
             var inputContent = $("textarea[name='Content']");            
             if (!inputContent.val()) {
                 canSubmit = false;
                 inputContent.next().show();
                 errMsgs.push(inputContent.next().text());
             }

             if (document.getElementById('attachment-file').files.length) {
                 var inputAttachmentTitle = $("input[name='Attachment_Title']");   
                 if (!inputAttachmentTitle.val()) {
                     canSubmit = false;
                     inputAttachmentTitle.next().show();
                     errMsgs.push(inputAttachmentTitle.next().text());
                 }
             }

             var student = isTrue($("input[type='checkbox'][name='Student']").val());
             var teacher = isTrue($("input[type='checkbox'][name='Teacher']").val());
             var staff = isTrue($("input[type='checkbox'][name='Staff']").val());

             if (!student && !teacher && !staff) {
                 $('#err-roles').show();
                 errMsgs.push($('#err-roles').text());
                 canSubmit = false;
             }

             if (student) {
                 var  classes= $("input[name='Classes']").val();
                 if (!classes) {
                     canSubmit = false;
                     $('#class-list').show();
                     $("input[name='Classes']").next().show();
                     errMsgs.push($("input[name='Classes']").next().text());
                 }

             }

             if (teacher || staff) {
                 var units = $("input[name='Units']").val();
                 if (!units) {
                     canSubmit = false;
                     $('#unit-list').show();
                     $("input[name='Units']").next().show();
                     errMsgs.push($("input[name='Units']").next().text());
                 }

             }

             if (!canSubmit) {
                 showErrors(errMsgs);

                 return false;
             } 

             alert('submit');

             if (!student) {
                 $("input[name='Classes']").val('');
                 
             }

             if (!staff && !teacher) {
                 $("input[name='Units']").val('');
                 $("input[name='Levels']").val('');

             }


          

            

          
        });

        

       
        
    });




</script>
