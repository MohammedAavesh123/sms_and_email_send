<!DOCTYPE html>

<html>

<head>

    <title>Laravel Ajax CRUD Tutorial Example - ItSolutionStuff.com</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

</head>

<body>



    <div class="container">

        <h1>Ajax CRUD</h1>

        <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a>
        <a class="btn btn-primary" href="{{ url('send-email') }}">Send Mail</a>
        <a class="btn btn-primary" href="{{ url('send-sms') }}">Send SMS</a>

        <table class="table table-bordered data-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th width="280px">Action</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

        </table>

    </div>
    <div class="modal fade" id="ajaxModel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="modelHeading"></h4>

                </div>

                <div class="modal-body">

                    <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">

                        <input type="hidden" name="product_id" id="product_id">

                        <div class="form-group">

                            <label for="name" class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-12">

                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">

                            </div>

                        </div>



                        <div class="form-group">

                            <label class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-12">

                                <input type="email" id="email" name="email" required="" placeholder="Enter Email" class="form-control">

                            </div>

                        </div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">Image</label>

                            <div class="col-sm-12">

                                <input type="file" id="image" name="image" required="" class="form-control">
                            </div>

                        </div>
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100" height="100">



                        <div class="col-sm-offset-2 col-sm-10">

                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



</body>



<script type="text/javascript">
    $(function() {



        /*------------------------------------------

         --------------------------------------------

         Pass Header Token

         --------------------------------------------

         --------------------------------------------*/

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });



        /*------------------------------------------

        --------------------------------------------

        Render DataTable

        --------------------------------------------

        --------------------------------------------*/

        var table = $('.data-table').DataTable({

            processing: true,

            serverSide: true,

            ajax: "{{ route('home') }}",

            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },

                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'email',
                    name: 'email'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ]

        });



        /*------------------------------------------

        --------------------------------------------

        Click to Button

        --------------------------------------------

        --------------------------------------------*/

        $('#createNewProduct').click(function() {

            $('#saveBtn').val("create-product");

            $('#product_id').val('');

            $('#productForm').trigger("reset");

            $('#modelHeading').html("Create New Product");

            $('#ajaxModel').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

        });

        /*------------------------------------------

        --------------------------------------------

        Create Product Code

        --------------------------------------------

        --------------------------------------------*/

        $('#saveBtn').click(function(e) {

            e.preventDefault();

            var actionType = $('#saveBtn').val();
            $('#saveBtn').html('Sending..');

            // var formData = new FormData(this);



            $.ajax({

                data: $('#productForm').serialize(),
                // data: formData,

                url: "{{ route('store') }}",

                type: "POST",

                dataType: 'json',

                success: function(data) {



                    $('#productForm').trigger("reset");

                    $('#ajaxModel').modal('hide');

                    table.draw();



                },

                error: function(data) {

                    console.log('Error:', data);

                    $('#saveBtn').html('Save Changes');

                }

            });

        });
    });

    function readURL(input, id) {
        id = id || '#modal-preview';
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(id).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
            $('#modal-preview').removeClass('hidden');
            $('#start').hide();
        }
    }
</script>

</html>