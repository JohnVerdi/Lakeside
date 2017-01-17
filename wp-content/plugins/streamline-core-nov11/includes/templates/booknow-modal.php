<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria - labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="modal_form"
                  action="<?php echo get_permalink(get_page_by_slug('checkout')) ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data - dismiss="modal" aria - label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                            aria - hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $property['name'] ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce ?>"/>
                    <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>

                    <div class="row">
                        <div class="col-md-12 errorMsg">

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sd" class="col-sm-4 control-label"><?php _e( 'Check in:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <input type="text" value="" name="book_start_date" class="form-control datepicker"
                                           datepicker
                                           id="modal_checkin"/>
                                </div>
                            </div>
                            <div class="form-group" id="group-enddate" style="display:none">
                                <label for="ed" class="col-sm-4 control-label"><?php _e( 'Check out:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <input type="text" value="" name="book_end_date" id="modal_checkout"
                                           class="form-control datepicker"/>
                                </div>
                            </div>
                            <div class="form-group" id="group-days">
                                <label for="modal_days" class="col-sm-4 control-label"><?php _e( 'Nights:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <select id="modal_days" class="form-control">
                                        <option value=""><?php _e( 'Select # of nights', 'streamline-core' ) ?></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-adults" style="display:none">
                                <label for="modal_days" class="col-sm-4 control-label"><?php _e( 'Adults:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <select name="book_occupants" id="modal_adults" class="form-control">
                                        <option value="1" selected>1</option>
                                        <?php
                                        for($i = 2; $i<=$property['max_occupants']; $i++){
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-childs" style="display:none">
                                <label for="ch" class="col-sm-4 control-label"><?php _e( 'Children:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <select name="ch" id="modal_childs" class="form-control">
                                        <option value="1" selected>1</option>
                                        <?php
                                        for($i = 2; $i<=$property['max_occupants']; $i++){
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <table id="table-breakdown" cellspacing="0" cellpadding="0" border="0"
                                   class="table table-striped table-condensed table-bordered" style="display:none">
                                <tbody>
                                <tr>
                                    <td><?php _e( 'Rental Charges:', 'streamline-core' ) ?></td>
                                    <td id="td_modal_rent" class="text-right" ng-model="rental_charges"></td>
                                </tr>
                                <tr>
                                    <td><?php _e( 'Fees:', 'streamline-core' ) ?></td>
                                    <td id="td_modal_fees" class="text-right" ng-model="fees"></td>
                                </tr>
                                <tr>
                                    <td><?php _e( 'Taxes:', 'streamline-core' ) ?></td>
                                    <td id="td_modal_taxes" class="text-right" ng-model="taxes"></td>
                                </tr>
                                <tr>
                                    <td><?php _e( 'Other Deposits:', 'streamline-core' ) ?></td>
                                    <td id="td_modal_deposits" class="text-right" ng-model="deposits"></td>
                                </tr>
                                <tr style="border-top:solid 2px #ccc">
                                    <td ng-model="total"><?php _e( 'Total:', 'streamline-core' ) ?></td>
                                    <td id="td_modal_total" class="text-right"><strong></strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e( 'Cancel', 'streamline-core' ) ?></button>
                    <button type="button" id="btn-modal-book" class="btn btn-success" disabled="disabled">
                      <?php _e( 'Make Reservation', 'streamline-core' ) ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
