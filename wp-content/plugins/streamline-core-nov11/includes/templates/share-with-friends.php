<button type="button" href="#" data-toggle="modal" data-target="#modalShare" class="btn btn-lg btn-block btn-warning" id="btn-share">
    <i class="glyphicon glyphicon-share"></i> <?php _e( 'Share With Friends', 'streamline-core' ) ?>
</button>
<form class="frm-property-share" name="frmShare" ng-init="share.unit_id=<?php echo $unit_id ?>;share.seo_page_name='<?php echo $location_name ?>';share.start_date='<?php echo $start_date ?>';share.end_date='<?php echo $end_date ?>';share.occupants='<?php echo $occupants ?>';share.occupants_small='<?php echo $occupants_small ?>';share.pets='<?php echo $pets ?>';share.nonce='<?php echo wp_create_nonce( 'share-with-friends' ); ?>';" novalidate>
    <input type="hidden" ng-model="share.unit_id" />
    <input type="hidden" ng-model="share.start_date" />
    <input type="hidden" ng-model="share.end_date" />
    <input type="hidden" ng-model="share.occupants" />
    <input type="hidden" ng-model="share.occupants_small" />
    <input type="hidden" ng-model="share.pets" />
    <input type="hidden" ng-model="share.nonce" name="nonce" />

    <div class="modal fade" id="modalShare" tabindex="-2" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                    aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e( 'Share With Friends', 'streamline-core' ) ?></h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <div class="row">
                                <label for="fnames" class="col-sm-3 control-label"><?php _e('Friend(s) Name(s)', 'streamline-core'); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" ng-model="share.fnames" name="fnames" id="share-with-friends-fnames" placeholder="<?php _e('Separated by comma', 'streamline-core'); ?>" ng-required="true" />
                                    <div ng-show="frmShare.$submitted">
                                        <span class="error" ng-show="frmShare.fnames.$error.required" ng-bind="'<?php _e('Friends(s) name(s) is required.', 'streamline-core'); ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="email" class="col-sm-3 control-label"><?php _e('Friend(s) Email(s)', 'streamline-core'); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" ng-model="share.femails" name="emails" id="share-with-friends-femails" placeholder="<?php _e('Separated by comma', 'streamline-core'); ?>" ng-required="true" />
                                    <div ng-show="frmShare.$submitted">
                                        <span class="error" ng-show="frmShare.emails.$error.required" ng-bind="'<?php _e('Friends(s) Email(s) is required.', 'streamline-core'); ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="name" class="col-sm-3 control-label"><?php _e('Your Name', 'streamline-core'); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" ng-model="share.name" name="name" id="share-with-friends-name" placeholder="" ng-required="true" />
                                    <div ng-show="frmShare.$submitted">
                                        <span class="error" ng-show="frmShare.name.$error.required" ng-bind="'<?php _e('Name is required.', 'streamline-core'); ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="email" class="col-sm-3 control-label"><?php _e('Your Email', 'streamline-core'); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" ng-model="share.email" name="email" id="share-with-friends-email" placeholder="" ng-required="true" />
                                    <div ng-show="frmShare.$submitted">
                                        <span class="error" ng-show="frmShare.email.$error.required" ng-bind="'<?php _e('Email is required.', 'streamline-core'); ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="msg" class="col-sm-3 control-label"><?php _e('Message', 'streamline-core'); ?></label>
                                <div class="col-sm-9">
                                    <textarea name="msg" id="share-with-friends-msg" ng-model="share.message" class="form-control" ng-required="true"></textarea>
                                    <div ng-show="frmShare.$submitted">
                                        <span class="error" ng-show="frmShare.msg.$error.required" ng-bind="'<?php _e('Message is required.', 'streamline-core'); ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="alert alert-{[alert.type]} animate"
                            ng-repeat="alert in alerts">
                            <div ng-bind-html="alert.message | trustedHtml"></div>
                        </div>
                    </div>

                </div> <!-- modal-body -->
                <div class="modal-footer">
                    <input type="hidden" name="hash" id="share-with-friends-hash" value="" />
                    <input type="hidden" name="slug" id="share-with-friends-slug" value="" />
                    <button class="btn btn-default" data-dismiss="modal"><?php _ex('Close', 'Closes modal window', 'streamline-core'); ?></button>

                    <button type="submit" ng-click="shareWithFriends()" class="btn btn-success" id="btn-share-with-friends-unit-submit"><?php _e( 'Send Now', 'streamline-core' ) ?></button>

                </div>
            </div>  <!-- modal-content -->
        </div>  <!-- modal dialog -->
    </div> <!-- modal -->

</form>
