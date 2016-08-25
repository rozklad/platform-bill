<div class="paid-modal modal-overlay">

    <div class="modal-content">

        <i id="closeButton" class="fa fa-times" aria-hidden="true"></i>

        <div class="modal-header">

            <h4 class="title text-center" id="paidModalTitle">

            </h4>

        </div>

        <div class="modal-body">

            <form method="POST">

                <div class="form-group">

                    <label for="">When was paid?</label>

                    <input type="date" class="form-control" name="paid" value="{{ date('Y-m-d') }}">

                </div>

                <input type="hidden" name="id" id="modalBillId">

        </div>

        <div class="modal-footer">

            <button class="btn btn-block btn-dollar-green">Save</button>

        </div>

        </form>

    </div>

</div>