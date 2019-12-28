<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/bootstrap/favicon.ico') }}">

    <title>Fixed Top Navbar Example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- dataTables --}}
    <link href="{{ asset('assets/dataTables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

      {{-- SweetAlert2 --}}
      <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
      <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('assets/bootstrap/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/bootstrap/css/navbar-fixed-top.css') }}" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="{{ asset('assets/bootstrap/js/ie-emulation-modes-warning.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <div class="row">
        <div class="col-md-14">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Contact list
                        <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Add Contact</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="contact-table" class="table table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="30">No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Photo</th>
                                <th class="text-danger">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-justify"></tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      @include('form')

      @include('detail')

    </container>
    </div>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('assets/jquery/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    {{-- dataTables --}}
    <script src="{{ asset('assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{ asset('assets/bootstrap/js/ie10-viewport-bug-workaround.js') }}"></script>

    <script type="text/javascript">
      var table = $('#contact-table').DataTable({
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.contact') }}",
                      columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'show_photo', name: 'show_photo'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Add Contact');
      }

      function showData(id) {
        save_method = 'show';
        $.ajax({
          url: "{{ url('contact') }}" + '/' + id + "/show",
          type: "GET",
          dataType: "JSON",

          success: function(data) {
            $('#detail').modal('show');
            $('.modal-title').text('Detail Contact');

            let labNama = 'Nama : ';
            let labEmail = 'Email : ';
            let foto = data.show_photo;
            $('#id').val(data.id);
            $('#poto').text(data.photo);
            $('#detailName').html(labNama + data.name);
            $('#detailEmail').html(labEmail + data.email);
          },
          error : function() {
              alert("Nothing Data");
          }
        });
      }

      function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PUT');
        $('#modal-form form')[0].reset();
        $.ajax({
          url: "{{ url('contact') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            if(data.message == 'Contact Updated') {
                $('#modal-form').each(function(){
                $(this).modal('hide');
            });
            }
            table.ajax.reload();
            $('#modal-form').modal('show');
            $('.modal-title').text('Edit Contact');

            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#namePhoto').html('Foto anda saat ini : ' + data.photo);
                // table.ajax.reload();
          },
          error : function() {
              alert("Nothing Data");
          }
        });
      }

      function deleteData(id){
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
          swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              cancelButtonColor: '#d33',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then(function () {
              $.ajax({
                  url : "{{ url('contact') }}" + '/' + id,
                  type : "POST",
                  data : {'_method' : 'DELETE', '_token' : csrf_token},
                  success : function(data) {
                      table.ajax.reload();
                      swal({
                          title: 'Success!',
                          text: data.message,
                          type: 'success',
                          timer: '1500'
                      })
                  },
                  error : function () {
                      swal({
                          title: 'Oops...',
                          text: data.message,
                          type: 'error',
                          timer: '1500'
                      })
                  }
              });
          });
        }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('contact') }}";
                    else url = "{{ url('contact') . '/' }}" + id;
                    table.ajax.reload();
                    $.ajax({
                        url : url,
                        type : "POST",
//                        data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            if(data.message == 'Contact Created' ||
                               data.message == 'Contact Updated') {
                                $('#modal-form').each(function(){
                                $(this).modal('hide');
                            });
                            }
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>
  </body>
</html>
