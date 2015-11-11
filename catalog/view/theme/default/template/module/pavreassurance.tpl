<?php

if (!empty($pavreassurances)) { ?>
		<div class="box box-module-pavreassurances <?php echo $prefix_class ?>">
                <div class="box-content">
                    <div class="row box-outer">
                            <?php $i=1?>
                            <?php foreach ($pavreassurances as $reassurance) { ?>
                            	<?php if (!$onlymodal) { ?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 column">
                                    <div class="reassurances-left">
                                        <span class="icon-name pull-left fa <?php echo $reassurance['select_icon'] ?>"></span>
                                        <div class="description media-body">
                                            <h4><?php echo $reassurance['title']; ?></h4>
                                            <?php echo htmlspecialchars_decode($reassurance['caption']); ?>
                                            <!-- Button trigger modal -->
                                            <a href="javascript:void(0);" class="pavreassurances arrow" data-toggle="modal" data-target="<?php echo empty($reassurance['reassurance_3rdmodel'])?"#myModal".$i:"#".$reassurance['reassurance_3rdmodel'];?>"></a>
                                            <div class="mask" style="display:none;">
                                                <?php echo htmlspecialchars_decode($reassurance['detail']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <!-- Modal -->
                                <div class="modal fade" id="myModal<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-body">
                                            <?php echo htmlspecialchars_decode($reassurance['detail']); ?>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <?php $i++;?>
                            <?php } ?>
                    </div>
                </div>
		</div>
	<?php } ?>


