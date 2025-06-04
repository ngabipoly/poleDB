</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url();?>public/assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>public/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url();?>public/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>public/assets/js/adminlte.js"></script>
<!-- Print Elements -->
<script src="<?php echo base_url();?>public/assets/plugins/printThis/printThis.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>public/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url();?>public/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>public/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url();?>public/assets/plugins/toastr/toastr.min.js"></script>
<script>
    //Date range picker
    $('.daterange').daterangepicker({
        locale: {
        format: 'YYYY-MM-DD'
      }
    })  

    //data table
    $(".data-table").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	
    
    //bind enter in manual loiading form
    $('#num-add').keyup(function(e){
      if(e.which===13){
        e.preventDefault();
        $('#btn-add-number').click();
      }
    });

    //Add MSISDN to loader area
    $('.btn-add-number').click(function(e){
        e.preventDefault();
        let sourceElm = $(this).data('source');
        let targetElm = $(this).data('target');
        let amountElm = $(this).data('amount');
        let pFormElm = $(this).data('parent');

        let feedBkElm = $('#load-status');
        let msisdn = $(sourceElm).val();
        let amount = $(amountElm).val();
        let prevEntries = $(targetElm).val();
        let newEntries = "";

        console.log(msisdn.length, msisdn.substr)

        if(!msisdn && !amount){
          feedBkElm.hide().empty();
          return false;
        }
        if(!msisdn){
          toastr.error("Please enter MSISDN");
          return false; 
        }

        if(!amount && amountElm){
          toastr.error("Please Enter Amount");
          return false; 
        }else if(amount && amountElm){
          newEntries=(!prevEntries)? `${msisdn},${amount}`:`\n${msisdn},${amount}`;
        }else{
          newEntries=(!prevEntries)? `${msisdn}`:`\n${msisdn}`;
        }

        if((msisdn.length ==9 && (msisdn.substring(0,2)=="71" || msisdn.substring(0,1)=="4"))||msisdn.substring(0,1)=="8"){
          const fieldCheck = checkRequired($(pFormElm).find('.required'),feedBkElm);
          if(fieldCheck===false){
            return false;
          }
          console.log(`Adding ${msisdn}, ${amount}`);
          $(targetElm).append(newEntries)
          $(sourceElm).val('');
          $(sourceElm).focus();
        }else{
          toastr.error("Invalid MSISDN");
          return false;          
        }
    })


    //post loadings
    $(document).on('click', '#btn-post-loading',function(e){
      e.preventDefault();
      let message = 'Posting Loadings';
      let feedBkElm = $('#load-status');
      let loadings = $('#loader-area').val();
      let uri = $(this).data('url');
      let form = $(this).closest('form');

      if(!loadings){
        return toastr.error("No Data Submitted!")
      }

      let data = {
        'loader-data':loadings,
      };

   try {
        const dataSubmission = async()=>{
			  const response = await submitData(uri,data,message,feedBkElm);
			  let returns = JSON.parse(response);
        console.log(`Returns, ${returns}`);
			  if(returns.status){

          form.find('textarea').text('');
          form[0].reset();
				  console.log(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`);
          $(feedBkElm).removeClass('alert-danger alert-warning').addClass('alert-success').text(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`).show();
			  }else{
				  console.error(`Operation Failed! ${returns}`);
				  feedBkElm.removeClass('alert-success alert-warning').addClass('alert-danger').text(`Error Creating Loadings File`).show() ;
				  feedBkElm.css('display','block');
			  }

		  } 
      dataSubmission();   
    }catch (error) {
      console.log(error)
    }         

    });
//Control display of Bulk and Single entry options for miscellenious actions
$('#o-type').change(function(){
  if($(this).val()=="I"){
    $('#bulk-ops').attr('hidden','hidden');
    $('#single-entry-ops').show();
  }else{
    $('#single-entry-ops').hide();
    $('#bulk-ops').removeAttr('hidden');
  }
})

//Add Number by Number function to miscellenious actions
$("#misc-exec").click(function(e){
  e.preventDefault();
    let actionType = $("#action-key").val();
    let opType = $("#o-type").val();
    let numbers = $("#o-queue").val();
    let form = $(this).closest('form');
    let url = $(this).data('url');
    let feedBkElm = $('#messages');
    let message = "Starting Operation.."

    if(!numbers){
      return toastr.error("Nothing in Queue! Please add MSISDNs.")
    }

    let formData = form.serialize();


    console.log(formData)
    try {
        const dataSubmission = async()=>{
			  const response = await submitData(url,formData,message,feedBkElm);
			  let returns = JSON.parse(response);
        console.log(`Returns, ${JSON.stringify(response)}`);
			  if(returns.status){

          form.find('textarea').text('');
          form[0].reset();
				  console.log(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`);
          $(feedBkElm).removeClass('alert-danger alert-warning').addClass('alert-success').text(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`).show();
			  }else{
				  console.error(`Operation Failed! ${returns}`);
				  feedBkElm.removeClass('alert-success alert-warning').addClass('alert-danger').text(`Error Creating Loadings File`).show() ;
				  feedBkElm.css('display','block');
          if(returns.message){
            toastr.error(`${returns.message}`)
          }
			  }

		  } 
      dataSubmission();   
    }catch (error) {
      console.log(error)
    }    

});


//load user information into modal
$('.user-edit').on('click',function(e){
  $("#user-mgr-h").text("Edit User");
  $('#uid').val($(this).data('uid'));
  $('#pf-number').val($(this).data('pf'));
  $('#first-name').val($(this).data('fname'));
  $('#last-name').val($(this).data('lname'));;
  $('#user-email').val($(this).data('uemail'));
  $('#user-role').val($(this).data('urole'));;
  $('#user-status').val($(this).data('ustatus'));
  $('#reset-pwd').show();
});

$('#new-user').on('click',function(){
    if($("#user-mgr-h").text()==="Edit User"){
      $("#user-mgr-h").text("New User");
      $("#frm-user-mgt").trigger("reset");
      $('#reset-pwd').hide();
    }  
});

//load role details for editing
$('.get-role-rights').on('click', function(e) {
    let url = $(this).data('url');
    let entity_id = $(this).data('roleid');
    let role_name = $(this).data('rolename');
    let role_status = $(this).data('rolestatus');
    let role_desc = $(this).data('desc');
    let entity_type = 'R';

    console.log(entity_id)

    $('#role-name').val(role_name);
    $('#role-id').val(entity_id);
    $('#entity-id').val(entity_id);
    $('#role-status').val(role_status);
    $('#role-desc').text(role_desc);
    $('#spn-role-name').text(role_name);
    $('#role-action').text(`Modify Role ${role_name}`);

    // Get Assigned Menu
    let data = { entity_id: entity_id, entity_type: entity_type };
    let elm = $('#roles')
    loadElm(url,data,elm,'Fetching Role Menus...')
});

//Queue Menus for Addition
$(document).on('click','#btn-assign',function(e){
    e.preventDefault();
    let assigned_menu_ids =''
    console.log('assigning')
    $('.chk-unassigned').each(function(){
      if ($(this).prop('checked')) {
        let menu_id = $(this).val() 
        // Concatenate menu IDs with colon delimiter
        assigned_menu_ids =(!assigned_menu_ids)? menu_id: `${assigned_menu_ids}:${menu_id}`;
      }      
    })
    $('#assign-list').text(assigned_menu_ids);
});

//Queue Menus for revockation
$(document).on('click','#btn-revoke',function(e){
    e.preventDefault();
    let revoked_menu_ids =''
    console.log('assigning')
    $('.chk-assigned').each(function(){
      if ($(this).prop('checked')) {
        let menu_id = $(this).val() 
        // Concatenate menu IDs with colon delimiter
        revoked_menu_ids =(!revoked_menu_ids)? menu_id: `${revoked_menu_ids}:${menu_id}`;
      }      
    })
    $('#revoke-list').text(revoked_menu_ids);
});

//save to excel
$(".save_excel").click(function(e) {   
   let content = $(this).data('content');
   window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#content').html())); // content is the id of the DIV element  
   e.preventDefault();   
}); 

const loadElm = (url,data,element,init_msg)=>{
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        data: data,
        beforeSend: function(){
          toastr.info(init_msg);
        },
        success: function(response) {
            // Update the content of the #roles element with the received HTML
            element.html(response);
        },
        complete: function() {
          toastr.info("Loading Complete!")
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
};

//clear forms
    $(document).ready(function() {
        $('.reset').click(function() {
          let form = $(this).closest('form');
          form.find('textarea').text('');
          form[0].reset();
        });
  });

//General function to check that all required fields are set
const checkRequired =(element,errorElm)=>{
  let messages = '<ul>';
  let passed = true;
  element.each(function(){
      if($(this).val()==''){
        passed=false
         messages+=`<li> ${$(this).data('emptymsg')}</li>`;
      }
  })
  messages+='</ul>'
  errorElm.removeClass('alert-success alert-danger').addClass('alert-warning').html(messages);
  return passed;
}

//Ajax Submission
  const submitData=(url,data,message,messageElm) =>{
    const submitFormData = new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type:"POST",
                data:data,
                  beforeSend: function(){
                    toastr.info(`${message}`);
                  },
                  success: function(resp){
                    resolve(resp) 
                  },
                  error: function(xhr, status, error){
                    reject(error);
                  }
            });
      });  
      return submitFormData;
  }
  

  $(document).ready(function() {
    $('#add-district').click(function(e){
      e.preventDefault();
      $('#district-id').val('');
      $('#district-region-code').val('');
      $('#district-region-name').val('');
      $('#district-code').val('');
      $('#district-name').val('');
      $('#region-id').val('');
      $('#district-action').text('Add New District');
    });

//get district editing details
    $('.edit-district').click(function(){     
      let district_id = $(this).data('district-id');
      let region_code = $(this).data('district-region-code');
      let region_name = $(this).data('district-region-name');
      let code = $(this).data('district-code');
      let name = $(this).data('district-name');
      let region_id = $(this).data('region-id');
     
      $('#district-id').val(district_id);
      $('#district-region-code').val(region_code);
      $('#district-region-name').val(region_name);
      $('#district-code').val(code);
      $('#district-name').val(name);
      $('#region-id').val(region_id);

      $('#district-action').text(`Edit ${name} District`);
    })

    $('.delete-district').click(function(){
      let district_id = $(this).data('district-id');
      let district_name = $(this).data('district-name');
      let region_name = $(this).data('region-name');
      $('#spn-district-name').text(district_name);
      $('#spn-region-name').text(region_name);
      console.log('deleting district', district_id);
      $('#delete-district-id').val(district_id);
      $('#delete-district-name').val(district_name);
    })



    $('#reset-pwd').click(function(e){
      console.log('resetting');
      uri = "<?php echo base_url('administration/reset-pwd');?>";
      message="Resetting Password";
      feedBkElm=$('#rtn-errors');
      data = {
        uid: $('#uid').val(),
        'first-name':$('#first-name').val(),
        'last-name' :$('#last-name').val(),
        'user-email':$('#user-email').val()
      };

        try {
              const dataSubmission = async()=>{
              const response = await submitData(uri,data,message,feedBkElm);
              let returns = JSON.parse(response);
              console.log(`Returns, ${returns}`);
              if(returns.status=="success"){
                toastr.success(returns.msg)
              }else if(returns.status=="error"){
                toastr.error(returns.msg);
              }else{
                console.error(`Operation Failed! ${returns}`);
                feedBkElm.removeClass('alert-success alert-warning').addClass('alert-danger').text(`Error Creating Loadings File`).show() ;
                feedBkElm.css('display','block');
              }
            
            } 
            dataSubmission();   
          }catch (error) {
            console.log(error)
          } 
    });

  //bulk file handling
    $('#upload-form').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let file_elm = $('#load-input');
        let action_key =  $('#auto-ld');
        bulkFileUpload(form,file_elm,action_key);
    });

    $('#frm-misc-actions').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let file_elm = $('#action-file');
        let action_key =  $('#action-key');
        bulkFileUpload(form,file_elm,action_key)
    });
    $('#log-out').click(function(e){
      e.preventDefault();
      url = $(this).attr('href');
      location.replace(url);
    })
    $('.db-submit').submit(function(e){
      e.preventDefault();
      let target = $('#target-elm')
      let msg = $(this).data('initmsg');
      db_submit(target,$(this),msg);
    });


function db_submit(resTarget,formSubmited,sendMsg){
  let alerts = '';
       $.ajax({
        type:'POST', 
        dataType:'json',
        url: $(formSubmited).attr('action'), 
        data:$(formSubmited).serialize(), 
        beforeSend: function() {
          //message to user confirming progress
          toastr.info(sendMsg);
          //empty multiple message element
          $(resTarget).html('');
        },
      success: function(response) {
          
          if($.isArray(response.msg)){
            //construct and show multiple messages if available
            let messages = '';
            $.each(response.msg,function(index,value){
              messages = messages+value+'<br/>';
            });
            alerts = '<div class="alert alert-info">'+messages+'</div>'
            $(resTarget).html(alerts).show();              
          }else{
            console.log(response);
          	if(response.status=='success'){
          		toastr.success(response.msg);
              $(formSubmited).trigger("reset");    
              if(typeof response.redirect_url){
                console.log(`Redirecting to ${response.redirect_url}`);
                location.replace(response.redirect_url)
              }  	
          	}else if(response.status=='error'){
          		toastr.error(response.msg)
          	}else{
              toastr.info(response.msg)
            }
          }        
        },
      complete: function() {
          
       },
      error: function(xhr){
        toastr.error(`${xhr.status}:${xhr.statusText} - ${xhr.responseText}`);
        console.log(xhr)
      }
  });
    
}

<?php
  if($page==='Load Numbers'){ ?>

 <?php }?>
    //load file details
    status = {S:'Submitted', P:'Processed'};
    file_table = $('#data-table').DataTable({
          processing: true,
          serverSide: false,
          // order datatable by first row descending
          order:[[0,'desc']],
          ajax: {
              url: '<?php echo site_url('front-office/wb-file-status');?>',
              type: 'POST'
          },
          columns: [
              { data: 'file_id',
                render: function(data, type,row){
                  return '<small><a href="#" data-fileid="' + data + '" data-url="<?php echo base_url();?>front-office/wb-file-details/" data-toggle="modal" class="load-file-dtls" data-target="#details-modal">' + data + '</a></small>';
                }
              },
              { data: 'source_file',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { data: 'in_file',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { data: 'submit_by',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { data: 'date_submitted',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { 
                data: 'file_status',
                render:function(data,type,row){
                  //console.log('data is',status)
                  if (data === 'S') {
                    return '<small>Submitted</small>';
                  } else if (data === 'P') {
                    return '<small>Processed</small>';
                  } else {
                    return '<small>'+data+'</small>'; // Handle other cases if necessary
                  }
                } 
              }
          ]
      });

    setInterval( function () {
      file_table.ajax.reload();
      }, 15000 );
});

function bulkFileUpload(form,file_elm,act_key){
  let url = form.attr('action');
  let formData = new FormData();
  let fileInput = file_elm[0];

  console.log(act_key.val());
  if(!act_key.val()){
    toastr.error("No Action Type Selected!");
    return false;
  }

  formData.append('action-type', act_key.val());
  formData.append('file', fileInput.files[0]);
  $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              console.log(response);
                if(response.status=='success'){
                  toastr.success(response.message);
                }else{
                  toastr.error(response.message);
                }
                file_elm.val('');
            },
            error: function(xhr, status, error) {
              console.log(xhr);
              toastr.error(error);
            }
        });  
}

$('#print-report').click(function(){
  let elm = $(this).data('report');
    console.log('attempting printing',elm)
    print_elm(elm)
})

function print_elm(elem){
  $(elem).printThis();
}
</script>
</body>
</html>