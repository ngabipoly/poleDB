</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url();?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/js/adminlte.js"></script>
<!-- Print Elements -->
<script src="<?php echo base_url();?>assets/plugins/printThis/printThis.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url();?>assets/plugins/toastr/toastr.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url();?>assets/plugins/chart.js/Chart.min.js"></script>


<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
<script>

      // Auto-detect device location and fill inputs
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
              $('#latitude').val(position.coords.latitude.toFixed(6));
              $('#longitude').val(position.coords.longitude.toFixed(6));
            }, function(error) {
                toastr.error('Error fetching location: ' + error.message);
            }, {
              enableHighAccuracy: true,
              timeout: 10000,
              maximumAge: 0
          });
        } else {
            toastr.error('Geolocation is not supported by this browser.');
        }
    }

  $(document).ready(function () {

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

  <?php
    if($page === 'Dashboard'){ ?>
            // Load summary stats
        $.getJSON("<?php echo base_url('/dashboard/ summaryStats'); ?>", function (data) {
            console.log(data);
            $('#totalPoles').text(data.totalPoles);
            $('#totalDistricts').text(data.totalDistricts);
            $('#damagedPoles').text(data.damagedPoles);
            $('#goodPoles').text(data.goodPoles);
            $('#replanted').text(data.replantedPoles);
            $('#monthlyAddition').text(data.monthlyAddition);

            // Region Chart
            const regionLabels = data.PolesPerRegion.map(item => item.RegionName);
            const regionData = data.PolesPerRegion.map(item => parseInt(item.poles_count));
            new Chart($('#regionChart'), {
        type: 'bar',
        data: {
            labels: data.PolesByConditionPerRegion.regionNames,
            datasets: data.PolesByConditionPerRegion.stackedData
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Pole Status by Region'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
              legend: {
                      position: 'bottom'
              }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Poles'
                    }
                }
            }
        }
    });

            //chart - Poles by Condition
            let labels = data.PolesByCondition.map(e => e.pole_condition);
            let counts = data.PolesByCondition.map(e => e.count);
            // Condition Chart
              new Chart($('#conditionChart'), {
                  type: 'doughnut',
                  data: {
                  labels: labels,
                  datasets: [{
                      data: counts,
                      backgroundColor: ['#28a745', '#ffc107', '#6c757d']
                  }]
                  }
              });

              const sizeLabels = data.polesPerSize.map(item => item.SizeLabel);
              const sizeCounts = data.polesPerSize.map(item => parseInt(item.count));
   
            new Chart($('#sizeChart'), {
                type: 'doughnut',
                data: {
                labels: sizeLabels,
                datasets: [{
                    label: 'Poles by Size',
                    data: sizeCounts,
                    backgroundColor: ['#007bff', '#dc3545', '#6c757d', '#28a745']
                }]
                },
                options: { 
                  responsive: true,
                  plugins: {
                    legend: {
                      position: 'bottom',
                      labels: {
                        font: {
                          size: 14
                        }
                      }
                    },
                    title: {
                      display: true,
                      text: 'Poles by Size'
                    }
                  }
                }
            });
        });

      <?php } ?>


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

  $("#district-id").change(function () {
      const selectedOption = $(this).find("option:selected");

      const region_code = selectedOption.data('region-code');
      const region_name = selectedOption.data('region-name');
      const region_id = selectedOption.data('region-id');
      const district_code = selectedOption.data('district-code');

      const pole_code = `${region_code}-${district_code}`;

      console.log("Arranging pole code information: " + pole_code);

      $('#region_code').val(region_code);
      $('#region_name').val(region_name);
      $('#region_id').val(region_id);
      $('#infra-code').val(pole_code);
  });

  // Pole Size Management
  $(".add-pole-size").click(function(){
    $('#poleSizeId').val('');
    $('#poleType').val('');
    $('#size-meteres').val('');
    $('#poleSizeModalLabel').text('Add New Pole Size');
  })

  $(".edit-pole-size").click(function(){
    let id = $(this).data('id');
    let label = $(this).data('label');
    let size = $(this).data('size');
    $('#poleSizeId').val(id);
    $('#poleType').val(label);
    $('#size-meteres').val(size);    
    $('#poleSizeModalLabel').text('Edit Pole Size');
  })

  
 

  $(".delete-pole-size").click(function(){
    let id = $(this).data('id');
    let label = $(this).data('label');
    let size = $(this).data('size');

    $('#delPoleSizeId').val(id);
    $('#delPoleLabel').val(label);
    $('#delSizeMeteres').val(size);

    $('#deletePoleId').text(id);
    $('#deletePoleSizeLabel').text(label);
    $('#deletePoleSize').text(size);
  })

  //Pole Management Controls
let map; // Declare globally to reuse
let mapInitialized = false;

$('#map-tab').click(function (e) {
    e.preventDefault();

    // Toggle tabs
    $('#map-view').addClass('active');
    $('#pole-list').removeClass('active');
    $('#map-view-tab').addClass('active show');
    $('#pole-list-tab').removeClass('active show');
    $('#pole-map').show();

    console.log('Map tab clicked');

    if (!mapInitialized) {
        initMap();
        mapInitialized = true;
        console.log('Map initialized');
    } else {
        setTimeout(() => {
            map.invalidateSize(); // Refresh map display
            console.log('Map refreshed');
        }, 300);
    }
});

function initMap() {
    // Define custom icon styles using colored PNGs
    const iconBaseUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/';
    const icons = {
        Good: new L.Icon({
            iconUrl: iconBaseUrl + 'marker-icon-green.png',
            shadowUrl: iconBaseUrl + 'marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }),
        Damaged: new L.Icon({
            iconUrl: iconBaseUrl + 'marker-icon-yellow.png', // Yellow closest to orange
            shadowUrl: iconBaseUrl + 'marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }),
        Stolen: new L.Icon({
            iconUrl: iconBaseUrl + 'marker-icon-red.png',
            shadowUrl: iconBaseUrl + 'marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }),
        Replanted: new L.Icon({
            iconUrl: iconBaseUrl + 'marker-icon-blue.png',
            shadowUrl: iconBaseUrl + 'marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }),
        Default: new L.Icon.Default()
    };

    // Create map
    const map = L.map('pole-map').setView([0.3476, 32.5825], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Use marker cluster group
    const markerCluster = L.markerClusterGroup();

    // Load pole data
    $.getJSON("<?php echo base_url('pole/getPoleMapData') ?>", function (poles) {
        poles.forEach(pole => {
            const icon = icons[pole.pole_condition] || icons.Default;
            const condition = pole.pole_condition || 'Default';
                const popupContent = `
                  <div class="popup-${condition.toLowerCase()} leaflet-popup-custom">
                      <strong>${pole.PoleCode}</strong><br>
                      <strong>District:</strong> ${pole.districtName}<br>
                      <strong>Size:</strong> ${pole.SizeLabel}<br>
                      <strong>Status:</strong> ${condition}
                  </div>
             `;
            const marker = L.marker([parseFloat(pole.latitude), parseFloat(pole.longitude)], {
                icon: icon
            }).bindPopup(popupContent).on('popupopen', function (e) {
                const popup = e.popup;
                const colorClass = `popup-${condition.toLowerCase()}`;

                // Add color class to content wrapper and tip
                const popupEl = popup.getElement();
                if (popupEl) {
                    popupEl.querySelector('.leaflet-popup-content-wrapper')?.classList.add(colorClass);
                    popupEl.querySelector('.leaflet-popup-tip')?.classList.add(colorClass);
                }
            });
            markerCluster.addLayer(marker);
        });
        map.addLayer(markerCluster);
    });

    // Fix layout when tab is shown
    $('a[data-toggle="tab"][href="#map-view"]').on('shown.bs.tab', function () {
        setTimeout(() => map.invalidateSize(), 300);
    });
}

//Pole Management Controls
  $('.btn-infra').click(function(e){
    let title = $(this).data('action');
    let infra_type = $(this).data('infra-type');

    //reset form
    resetAllFields();
    $('#elm-type').val(infra_type);
    // Ensure district-id is editable when adding new infrastructure
    $('#district-id').prop('readonly', false);
    if (infra_type == 'Manhole') {
      $('.pole-data').hide();
      $('.manhole-data').show();
      $('#circular-data').hide();
      $('.non-circular-data').show();
      $('.infra-form').data('initmsg', 'Adding New Manhole');
    }else if (infra_type == 'Pole') {
      $('.manhole-data').hide();
      $('.pole-data').show();
      $('#pole-condition option[value="stolen"]').remove();
      $('.infra-form').data('initmsg', 'Adding new pole');
    }

      $('#action-title').text(title);
  });

  $('#manhole-circular').change(function(){
    if ($(this).is(':checked')) {
      $('#circular-data').show();
      $('.non-circular-data').hide();
    }else{
      $('#circular-data').hide();
      $('.non-circular-data').show();
    }
  });

  // Switch between Manhole and Pole listings
  $('.list-switch').click(function(e) {
    e.preventDefault();
    let show_list = $(this).data('show');
    let hide_list = $(this).data('hide');
    $(show_list).show();
    $(hide_list).hide();
    $(".data-table").DataTable().columns.adjust().responsive.recalc();
  });

  $('#add-media-type').click(function(e){
      e.preventDefault();
      $('#carryTypeId').val('');
      $('#carryTypeName').val('');
      $('#carryTypeDescription').val('');
      $('#media-type-modalLabel').text('Add New Media Type');
    });

  $('.edit-media-type').click(function(e){
      e.preventDefault();      
      let media_type_id = $(this).data('id');
      let media_type_name = $(this).data('name');
      let media_type_desc = $(this).data('description');
      $('#carryTypeId').val(media_type_id);
      $('#carryTypeName').val(media_type_name);
      $('#carryTypeDescription').val(media_type_desc);
      $('#media-type-modalLabel').text(`Edit Media Type ${media_type_name}`);
    });

    $('.delete-media-type').click(function(e){
        e.preventDefault();      
        let media_type_id = $(this).data('delete-media-type-id');
        let media_type_name = $(this).data('delete-media-type-name');
        $('#deleteCarryTypeId').val(media_type_id);
        $('#deleteCarryTypeName').val(media_type_name);
        $('#delMediaTypeName').text(media_type_name); 
    });

    //add media capacity
    $('#add-media-capacity').click(function(e){
        e.preventDefault();
        $('#carryCapacityId').val('');
        $('#carryTypeId').val(''); 
        $('#carryCapacityLabel').val('');
        $('#carryCapacityValue').val('');
        $('#carryCapacityDescription').text('');
        $('#media-capacity-modalLabel').text('Add New Media Capacity');
    });

    //Edit Carry capacities
    $('.edit-media-capacity').click(function(e){
        e.preventDefault();
        let media_capacity_id = $(this).data('id');
        let media_capacity_label = $(this).data('label');
        let media_capacity_value = $(this).data('value');
        let media_type_id = $(this).data('type-id');
        let media_description = $(this).data('description');

        $('#carryCapacityId').val(media_capacity_id);
        $('#carryCapacityLabel').val(media_capacity_label);
        $('#carryCapacityValue').val(media_capacity_value);
        $('#carryTypeId').val(media_type_id);
        $('#carryCapacityDescription').text(media_description);
        $('#media-capacity-modalLabel').text(`Edit Media Capacity ${media_capacity_label}`);
    })

    //Delete Media Capacity
    $('.delete-media-capacity').click(function(e){
        e.preventDefault();
        let media_capacity_id = $(this).data('id');
        let media_capacity_label = $(this).data('label');

        $('#deleteCarryCapacityId').val(media_capacity_id);
        $('#deleteCarryCapacityLabel').val(media_capacity_label);
        $('#delCapacityLabel').text(media_capacity_label); 
    });



    $(document).on('click', '.edit-infra', function () {
        resetAllFields();
        const data = {
            element_id: $(this).data('element-id'),
            element_code: $(this).data('element-code'),
            pole_size: $(this).data('pole-size'),
            district_id: $(this).data('district-id'),
            latitude: $(this).data('latitude'),
            longitude: $(this).data('longitude'),
            pole_type: $(this).data('pole-type'),
            element_condition: $(this).data('element-condition'),
            title: $(this).data('infra-title'),
            infra_type: $(this).data('infra-type'),
            manhole_length: $(this).data('manhole-length'),
            manhole_width: $(this).data('manhole-width'),
            manhole_depth: $(this).data('manhole-depth'),
            manhole_diameter: $(this).data('manhole-diameter'),
        };

        populateCommonFields(data);

        switch (data.infra_type) {
            case 'Pole':
                showPoleFields(data);
                break;
            case 'Manhole':
                showManholeFields(data);
                break;
            default:
                console.warn('Unhandled infra type:', data.infra_type);
        }
    });

  $('.delete-pole').click(function(){
      let pole_id = $(this).data('pole-id');
      let pole_code = $(this).data('name');
      $('#delete-pole-id').val(pole_id);
      $('#delete-pole-code').val(pole_code);

      $('#delete-pole-name').text(pole_code);
      $('#delete-pole-id').text(pole_id);
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


   function resetAllFields() {
        $('#element-form').trigger('reset');
        $('.pole-data, .manhole-data, #circular-data, .non-circular-data').hide();
        $('#manhole-circular').prop('checked', false);
        $('#pole-size, #pole-type').val('');
        $('#elm-id, #infra-code, #latitude, #longitude, #elm-condition, #district-id, #elm-type').val('');
        $('#district-id').prop('disabled', false);
    }

    function populateCommonFields(data) {
        $('#elm-id').val(data.element_id);
        $('#elm-type').val(data.infra_type);
        $('#infra-code').val(data.element_code);
        $('#elm-condition').val(data.element_condition || '');
        $('#district-id').val(data.district_id).prop('disabled', true);
        $('#latitude').val(data.latitude);
        $('#longitude').val(data.longitude);
        $('#infra-type').val(data.infra_type);
        $('#action-title').text(`${data.title} ${data.element_code}`);
        $('.infra-form').data('initmsg', data.title);
    }

    function showPoleFields(data) {      
        $('.pole-data').show();
        $('#pole-size').val(data.pole_size);
        $('#pole-type').val(data.pole_type);

        // Ensure 'Stolen' option is present (you may need to adapt this logic)
        if ($('#elm-condition option[value="Stolen"]').length === 0) {
            $('#elm-condition').append('<option value="Stolen">Stolen</option>');
        }

    }

    function showManholeFields(data) {
        $('.manhole-data').show();
        $('#manhole-width').val(data.manhole_width || 0);
        $('#manhole-length').val(data.manhole_length || 0);
        $('#manhole-depth').val(data.manhole_depth || 0);
        $('#manhole-diameter').val(data.manhole_diameter || 0);

        const isCircular = parseFloat(data.manhole_diameter || 0) > 0;
        $('#manhole-circular').prop('checked', isCircular);

        toggleManholeShapeFields(isCircular);
    }

    function toggleManholeShapeFields(isCircular) {
        if (isCircular) {
            $('#circular-data').show();
            $('.non-circular-data').hide();
        } else {
            $('#circular-data').hide();
            $('.non-circular-data').show();
        }
    }

    // Handle circular switch toggle
    $('#manhole-circular').on('change', function () {
        toggleManholeShapeFields($(this).is(':checked'));
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
                console.log(`Redirecting to ${response.redirect_url} in 3 seconds`);
                setTimeout(function() {
                  location.replace(response.redirect_url);
                }, 3000);
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
  });
</script>
</body>
</html>