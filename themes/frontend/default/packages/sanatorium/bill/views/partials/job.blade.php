'<div class="row new-job-row" data-job-number="' + job_number + '" style="padding-bottom: 10px;">' +

    '<div class="form-group">' +

        '<input type="text" id="bill_id" name="jobs[' + job_number + '][bill_id]" hidden value="">' +

        '<div class="col-sm-1">' +

            '<input required class="form-control" type="number" id="jobs.quantity" name="jobs[' + job_number + '][quantity]" value="1">' +

        '</div>' +

        '<div class="col-sm-6">' +

            '<input required class="form-control" type="text" id="description" name="jobs[' + job_number + '][description]" placeholder="Description">' +

        '</div>' +

        '<div class="col-sm-2">' +

            '<input required class="form-control" type="number" id="price" name="jobs[' + job_number + '][price]" placeholder="Price">' +

        '</div>' +

        '<div class="col-sm-2">' +

            '<select class="form-control" id="currency" name="jobs[' + job_number + '][currency]">' +

                '<option value="CZK">Kč</option>' +

                '<option value="EUR">EUR</option>' +

                '<option value="CHF">CHF</option>' +

            '</select>' +

        '</div>' +

        '<div class="col-sm-1 buttons-col">' +

            '<span class="circle-button delete" id="delete_job">x</span>' +

        '</div>' +

    '</div>' +

'</div>'
