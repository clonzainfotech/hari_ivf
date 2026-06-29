<div class="modal fade collection" id="collection_report" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content collection-modal">
            <!-- header -->
            <div class="modal-header justify-content-center">
                <h4 class="title">Enter Password !</h4>
            </div>
            <!-- body -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {{Form::password('password',['class'=>'d-block form-control collection-password','placeholder'=>'Please enter password'])}}
                        {{Form::hidden('con_password',config('app.collection_password'),['class'=>'con-password'])}}
                        {{Form::hidden('collection_url',url('collection-report'),['class'=>'collectionurl'])}}
                        <span class="collection-error-message"></span>
                    </div>
                </div>
            <!-- footer -->
            <div class="modal-footer justify-content-center">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary waves-effect check-collection-report">SUBMIT</button>
                    <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
</div>