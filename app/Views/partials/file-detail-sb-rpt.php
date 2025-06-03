<div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <img src="<?php echo base_url('public/assets/img/utel_logo.jpg');?>" class="report-logo" alt="utel-logo">
                   <center><small class="text-center"><strong>Action Report</strong></small></center> 
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <?php 
                    $status =($file_smry['file_status']==='S')? 'Pending' : 'Processed';
                    $rec_user = $user_dtls;
                ?>
                <div class="col-sm-4 invoice-col">
                  <?php 
                  $action = array_slice(explode('_', $file_smry['in_file']),2);
                  $last = explode('.',$action[count($action)-1]);
                  $action[count($action)-1] =$last[0];
                  $action = ucwords(implode(' ',$action));
                  
                  ?>

                  <address>
                    <b>Source File:</b> <?php echo $file_smry['source_file'];?><br>
                    <b>File Status:</b> <?php echo $status?><br>
                    <b>Completed  :</b> <?php echo $file_smry['response_date'];?><br>
                    <b>Operation  :</b> <?php echo $action;?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  
                  <address>
                    <b>Records Submitted:</b><?php echo $file_smry['records_read'];?><br>
                    <b>Records Imported:</b><?php echo $file_smry['records_imported'];?><br>
                    <b>Succeded:</b><?php echo $file_smry['records_executed'];?><br>
                    <b>Failed:</b><?php echo $file_smry['records_failed'];?><br>
                  </address>
                </div>
                <!-- /.col -->
                <?php 
                  $serve_last_name = ($rec_user && $rec_user['lastname'])? $rec_user['lastname']:null;
                  $serve_first_name = ($rec_user && $rec_user['firstname'])? $rec_user['firstname']:null;
                ?>
                <div class="col-sm-4 invoice-col">
                  <b>Transaction Date:</b> <?php echo date('Y-m-d', strtotime($file_smry['date_submitted']) );?><br>
                  <b>Served By:</b> <?php if($serve_last_name||$serve_first_name){echo  "{$serve_last_name}, {$serve_first_name}";}?><br>
                  <b>Print Date:</b> <?php echo date('Y-m-d');?><br>
                  <b>Print By:</b> <?php echo "{$user->lastname}, {$user->firstname}";?>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <!-- Loadings Report -->
          <?php 
              if(strpos($file_smry['in_file'],'auto_load.in')){?>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">

                      <table class="table table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                          <th title="Phone Number"><small><b>MSISDN</b></small></th>
                          <th title="Load Amount"><small><b>Amount</b></small></th>
                          <th title="Old Balance Amount"><small><b>Balance Before</b></small></th>
                          <th title="New Balance Amount"><small><b>Balance After</b></small></th>
                          <th title="Transaction Status"><small><b>Status</b></small></th> 
                        </tr>
                        </thead>
                        <tbody>                    
                            <?php 
                            $total = 0;
                            $total_load = 0;
                            $total_failed=0;
                            $total_pend=0;
                            foreach ($file_details as $row) { ?>
                                <tr>    
                                    <td><small><?php echo $row['msisdn'];?></small></td>
                                    <td><small><?php echo number_format($row['loadamount'],0,'.',',');?></small></td>
                                    <td><small><?php echo  number_format($row['amountbefore'],0,'.',',');?></small></td>
                                    <td>
                                        <small>
                                            <?php 
                                                echo number_format($row['amountafter'],0,'.',','); 
                                                $total += $row['amount'];
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small><?php 
                                            if($row['status']=='S'){
                                                $total_pend  += $row['amount'];
                                                echo 'Pending';
                                            }elseif($row['status']=='P'){
                                                $total_load += $row['amount'];
                                                echo 'Loaded';
                                            }else{
                                                $total_failed  += $row['amount'];
                                                echo 'Failed';
                                            }
                                        ?>
                                        </small></td>
                                </tr>  
                            <?php }?>                    
                        </tbody>
                      </table>

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">

                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Amount Per Status:</p>

                  <div class="table-responsive">
                    <table class="table table-sm">
                      <tbody>
                      <tr>
                        <th style="width:50%"><small><b>Pending:</b></small></th>
                        <td><small><?php echo number_format($total_pend,0,'.',',');?></small></td>
                      </tr>
                      <tr>
                        <th><small><b>Failed:</b></small></th>
                        <td><small><?php echo number_format($total_failed,0,'.',','); ?></small></td>
                      </tr>
                      <tr>
                        <th style="width:50%"><small><b>Loaded:</b></small></th>
                        <td><small><?php echo number_format($total_load,0,'.',',');?></small></td>
                      </tr>
                      <tr>
                        <th><small><b>Total:</b></small></th>
                        <td><small><?php echo number_format($total,0,'.',',');?></small></td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
        <!-- /Loadings Report -->

        <?php  } ?>

              <!-- idle_to_active Report -->
              <?php 
              if(strpos($file_smry['in_file'],'idle_to_active.in')){?>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">

                      <table class="table table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                          <th title="Phone Number"><small><b>MSISDN</b></small></th>
                          <th title="Old Balance Amount"><small><b>Status Before</b></small></th>
                          <th title="New Balance Amount"><small><b>Status After</b></small></th>
                          <th title="New Balance Amount"><small><b>Operation Status</b></small></th>
                        </tr>
                        </thead>
                        <tbody>                    
                            <?php 
                            $total = 0;
                            $total_found = 0;
                            $total_failed=0;
                            $total_pend=0;
                            foreach ($file_details as $row) { ?>
                                <tr>    
                                    <td><small><?php echo $row['msisdn'];?></small></td>
                                    <td><small><?php echo $row['accountstatebefore'];?></small></td>
                                    <td><small><?php echo  $row['accountstateafter'];?></small></td>
                                    <td>
                                        <small><?php 
                                            if($row['status']=='S'){
                                                $total_pend++;
                                                echo 'Pending';
                                            }elseif($row['status']=='P'){
                                                $total_found++;
                                                echo 'Activated';
                                            }else{
                                                $total_failed++;
                                                echo 'Failed';
                                            }
                                            $total++;
                                        ?>
                                        </small></td>
                                </tr>  
                            <?php }?>                    
                        </tbody>
                      </table>

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">

                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Summary:</p>

                  <div class="table-responsive">
                    <table class="table table-sm">
                      <tbody>
                      <tr>
                        <th style="width:50%"><small><b>Pending:</b></small></th>
                        <td><small><?php echo number_format($total_pend,0,'.',',');?></small></td>
                      </tr>
                      <tr>
                        <th><small><b>Failed:</b></small></th>
                        <td><small><?php echo number_format($total_failed,0,'.',','); ?></small></td>
                      </tr>
                      <tr>
                        <th style="width:50%"><small><b>Activated:</b></small></th>
                        <td><small><?php echo number_format($total_found,0,'.',',');?></small></td>
                      </tr>
                      <tr>
                        <th><small><b>Total:</b></small></th>
                        <td><small><?php echo number_format($total,0,'.',',');?></small></td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
        <!-- /Loadings Report -->
        <?php  } ?>

              <!-- deactive_to_active Report -->
              <?php 
              if(strpos($file_smry['in_file'],'deactive_to_active.in')){?>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">

                      <table class="table table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                          <th title="Phone Number"><small><b>MSISDN</b></small></th>
                          <th title="Old Balance Amount"><small><b>Status Before</b></small></th>
                          <th title="New Balance Amount"><small><b>Status After</b></small></th>
                          <th title="Old Service Stop"><small><b>Old Service Expiry Date</b></small></th>
                          <th title="New Service Stop"><small><b>New Service Expiry Date</b></small></th>
                          <th title="New Balance Amount"><small><b>Operation Status</b></small></th>
                        </tr>
                        </thead>
                        <tbody>                    
                            <?php 
                            $total = 0;
                            $total_found = 0;
                            $total_failed=0;
                            $total_pend=0;
                            foreach ($file_details as $row) { ?>
                                <tr>    
                                    <td><small><?php echo $row['msisdn'];?></small></td>
                                    <td><small><?php echo $row['accountstatebefore'];?></small></td>
                                    <td><small><?php echo  $row['accountstateafter'];?></small></td>
                                    <td><small><?php echo  $row['callservicestop'];?></small></td>
                                    <td><small><?php echo  $row['nw_callservicestop'];?></small></td>
                                    <td>
                                        <small><?php 
                                            if($row['status']=='S'){
                                                $total_pend++;
                                                echo 'Pending';
                                            }elseif($row['status']=='P'){
                                                $total_found++;
                                                echo 'Activated';
                                            }else{
                                                $total_failed++;
                                                echo 'Failed';
                                            }
                                            $total++;
                                        ?>
                                        </small></td>
                                </tr>  
                            <?php }?>                    
                        </tbody>
                      </table>

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">

                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Summary:</p>

                  <div class="table-responsive">
                    <table class="table table-sm">
                      <tbody>
                      <tr>
                        <th style="width:50%"><small><b>Pending:</b></small></th>
                        <td><small><?php echo number_format($total_pend,0,'.',',');?></small></td>
                      </tr>
                      <tr>
                        <th><small><b>Failed:</b></small></th>
                        <td><small><?php echo number_format($total_failed,0,'.',','); ?></small></td>
                      </tr>
                      <tr>
                        <th style="width:50%"><small><b>Activated:</b></small></th>
                        <td><small><?php echo number_format($total_found,0,'.',',');?></small></td>
                      </tr>
                      <tr>
                        <th><small><b>Total:</b></small></th>
                        <td><small><?php echo number_format($total,0,'.',',');?></small></td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
        <!-- /deactive_to_active Report -->
        <?php  } ?>

              <!-- query_history Report -->
              <?php 
              if(strpos($file_smry['in_file'],'query_history.in')){?>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">

                      <table class="table table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                          <th title="Phone Number"><small><b>MSISDN</b></small></th>
                          <th title="Phone Number"><small><b>Profile</b></small></th>
                          <th title="Status as of Balance Date"><small><b>Status</b></small></th>
                          <th title="Service Start Date"><small><b>Start Date</b></small></th>
                          <th title="Service Stop Date"><small><b>Stop Date</b></small></th>
                          <th title="End of Day Balance Amount"><small><b>EoD Balance</b></small></th>
                          <th title="Date of Balance"><small><b>Balance Date</b></small></th>
                        </tr>
                        </thead>
                        <tbody>                    
                            <?php 

                            foreach ($history as $row) { ?>
                                <tr>    
                                    <td><small><?php echo $row['msisdn'];?></small></td>
                                    <td><small><?php echo $row['profileName'];?></small></td>
                                    <td><small><?php echo  $row['status'];?></small></td>
                                    <td><small><?php echo  $row['svcStDate'];?></small></td>
                                    <td><small><?php echo  $row['svcStopDate'];?></small></td>
                                    <td><small><?php echo  number_format($row['accountLeft'],0,'.',',') ;?></small></td>
                                    <td><small><?php echo  $row['balanceDate'];?></small></td>
                                </tr>  
                            <?php }?>                    
                        </tbody>
                      </table>

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
        <!-- /query_history Report -->
        <?php  } ?>

              <!-- deactive_to_active Report -->
              <?php 
              if(strpos($file_smry['in_file'],'query_subscriber.in')){?>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                      <table class="table table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                          <th title="Phone Number"><small><b>MSISDN</b></small></th>
                          <th title="Service Status"><small><b>Status</b></small></th>
                          <th title="Service Stop Date"><small><b>Expiry Date</b></small></th>
                          <th title="User Type"><small><b>Profile</b></small></th>
                          <th title="Subscriber Balance"><small><b>Balance</b></small></th>
                          <!--<th title="Onnet SMSs"><small><b>Onnet SMSs</b></small></th>
                          <th title="Expiry"><small><b>Expiry</b></small></th>
                          <th title="Crossnet SMSs"><small><b>Crossnet SMSs</b></small></th>
                          <th title="Expiry"><small><b>Expiry</b></small></th>
                          <th title="Data Balance"><small><b>Data Balance</b></small></th>
                          <th title="Data Expiry"><small><b>Data Expiry</b></small></th>
                          <th title="KwickTock Balance"><small><b>KwickTock Balance</b></small></th>
                          <th title="KickTock Expiry"><small><b>KwickTock Expiry</b></small></th>                          
                          <th title="KwickTock Balance"><small><b>KwickTock Balance</b></small></th>
                          <th title="KickTock Expiry"><small><b>KwickTock Expiry</b></small></th>                         
                          <th title="Endobo Balance"><small><b>Endobo Free Mins</b></small></th>
                          <th title="Endobo Expiry"><small><b>Endobo Expiry</b></small></th>                         
                          <th title="Minutes"><small><b>Minutes</b></small></th>
                          <th title="Minutes Expiry"><small><b>Minutes Expiry</b></small></th>                        
                          <th title="Bonus"><small><b>Bonus</b></small></th>
                          <th title="Bonus Expiry"><small><b>Bonus Expiry</b></small></th>                         
                          <th title="Endobo Night"><small><b>Endobo Night</b></small></th>
                          <th title="Endobo Night Expiry"><small><b>Endobo Night Expiry</b></small></th>                          
                          <th title="Bundle(INT'L)"><small><b>Bundle(INT'L)</b></small></th>
                          <th title="Bundle(INT'L) Expiry"><small><b>Bundle(INT'L) Expiry</b></small></th> <!-->
                        </tr>
                        </thead>
                        <tbody>                    
                            <?php 
                            $total = 0;
                            $total_found = 0;
                            $total_failed=0;
                            $total_pend=0;
                            foreach ($file_details as $row) { ?>
                                <tr>    
                                    <td><small><?php echo $row['msisdn'];?></small></td>
                                    <td><small><?php echo $row['num_status'];?></small></td>
                                    <td><small><?php echo date('Y-m-d', strtotime($row['callservicestop']));?></small></td>
                                    <td><small><?php echo  $row['user_type'];?></small></td>
                                    <td><small><?php echo number_format($row['accountleft'],0,'.',','); ?></small></td>
                                    <!--<td><small><?php echo  $row['specresleft01'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate01']));?></small></td>
                                    <td><small><?php echo  $row['specresleft02'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate02']));?></small></td>
                                    <td><small><?php echo  $row['specresleft03'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate03']));?></small></td>
                                    <td><small><?php echo  $row['specresleft04'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate04']));?></small></td>
                                    <td><small><?php echo  $row['specresleft05'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate05']));?></small></td>
                                    <td><small><?php echo  $row['specresleft06'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate06']));?></small></td>
                                    <td><small><?php echo  $row['specresleft07'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate07']));?></small></td>
                                    <td><small><?php echo  $row['specresleft08'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate08']));?></small></td>
                                    <td><small><?php echo  $row['specresleft09'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate09']));?></small></td>
                                    <td><small><?php echo  $row['specresleft10'];?></small></td>
                                    <td><small><?php echo  date('Y-m-d', strtotime($row['specresdate10']));?></small></td><!-->
                                </tr>  
                            <?php }?>                    
                        </tbody>
                      </table>
                      

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
        <!-- /deactive_to_active Report -->
        <?php  } ?>


