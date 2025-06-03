                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card card-success">
                                                    <div class="card-header">
                                                        <h5>Assigned Menus</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <fieldset>
                                                            <legend>Choose Menus to Revoke</legend>
                                                            <div id="assigned">
                                                                <div class="row">
                                                                <?php 
                                                                    //var_dump($assigned);
                                                                    if($assigned){
                                                                        foreach ($assigned as $role) {
                                                                ?>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <div class="form-check">
                                                                            <input type="checkbox" value="<?php echo $role['menu_id'];?>" class="form-check-input chk-assigned">
                                                                            <label for="" class="form-check-label"><?php echo $role['menu_name'];?></label>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                        }
                                                                    }
                                                                ?>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="row">
                                                            <div class="col-sm-12 mb-2">
                                                                <button type="button" class="btn btn-sm btn-warning" id="btn-revoke">Queue for Removal</button>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <textarea class="form-control" name="revoke-list" id="revoke-list" hidden></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                            
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="card card-info">
                                                    <div class="card-header">
                                                        <h5>Unassigned Menus</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <fieldset>
                                                            <legend>Choose Menus to Assign</legend>

                                                            <div id="unassigned">
                                                                <div class="row">
                                                                    <?php
                                                                        // var_dump($unassigned);
                                                                         if($unassigned){
                                                                            foreach ($unassigned as $role) { 
                                                                                $label_id = str_replace(" ","-",$role['menu_name']);
                                                                                ?>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <div class="form-check">
                                                                                        <input type="checkbox" value="<?php echo $role['menu_id'];?>" class="form-check-input chk-unassigned" id="<?php echo $label_id;?>">
                                                                                        <label for="<?php echo $label_id;?>" class="form-check-label"><?php echo $role['menu_name'];?></label>
                                                                                    </div>
                                                                                </div><?php
                                                                            }
                                                                         }
                                                                         ?>

                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="row">
                                                            <div class="col-sm-12 mb-2">
                                                                <button type="button" class="btn btn-sm btn-primary" id="btn-assign">Queue for Assignment</button>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <textarea class="form-control" name="assign-list" id="assign-list" hidden ></textarea>
                                                            </div>
                                                        </div>                                                      
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>

                                    </div>