<?php

  include('include/header.php');

?>

<!-- Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->

  <hr>
  <div>
    <button type="button" id="add_button" class="btn btn-primary">
      <i class="fas fa-plus"></i> เพิ่มข้อมูล
    </button>
  </div>
  <br>
  <!-- users DataTable -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table font-weight-bold"></i> ตารางผู้ใช้งาน

    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>#</th>
              <th>username</th>
              <th>First_name</th>
              <th>Last_name</th>
              <th>address</th>
              <th>email</th>
              <th>phone</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
  <!-- End user DataTable -->

</div>
<!-- /.container -->

<!-- user Modal -->
<div class="modal fade" id="formModal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modal_title"></h5>
        <button class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="user_form">
          <p class="text-danger"><i>* จำเป็นต้องกรอก *</i></p>
          <div class="form-group">
            <label>ชื่อผู้ใช้งาน</label>
            <input type="text" id="username" name="username" class="form-control" autocomplete="off"></input>
            <div id="username_error_message" class="text-danger"></div>
          </div>
          <div class="form-group">
            <label for="title">ชื่อจริง</label>
            <input type="text" id="first_name" name="first_name" class="form-control"  autocomplete="off">
            <div id="first_name_error_message" class="text-danger"></div>
          </div>
          <div class="form-group">
            <label for="title">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" class="form-control" autocomplete="off">
            <div id="last_name_error_message" class="text-danger"></div>
          </div>
          <div class="form-group">
            <label for="title">ที่อยู่</label>
            <textarea type="text" id="address" name="address" class="form-control" autocomplete="off"></textarea>
            <div id="address_error_message" class="text-danger"></div>
          </div>
          <div class="form-group">
            <label for="title">อีเมลล์</label>
            <input type="email" id="email" name="email" class="form-control" autocomplete="off">
            <div id="email_error_message" class="text-danger"></div>
          </div>
          <div class="form-group">
            <label for="title">เบอร์โทรศัพท์</label>
            <input type="text" id="phone" name="phone" class="form-control" autocomplete="off" length=10>
            <div id="phone_error_message" class="text-danger"></div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="user_id" id="user_id" />
            <input type="hidden" name="action" id="action" value="" />
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-primary" name="button_action" id="button_action"><i
                class="fas fa-save"></i> บันทึก</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End user Modal -->

<!-- Footer -->
<?php

  include("include/footer.php");

?>

<script>

  $(document).ready(function () {

    var datatable = $('#userTable').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
        url: 'user_action.php',
        type: 'POST',
        data: { action: 'user_fetch' }
      },
      drawCallback: function (settings) {
        $('#total_user').html(settings.json.total);
      },
      'columns': [
        { data: 'id' },
        { data: 'username' },
        { data: 'first_name' },
        { data: 'last_name' },
        { data: 'address' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'action', "orderable": false }
      ]
    });


    $('#add_button').click(function () {
      $('#modal_title').text('เพิ่มผู้ใช้งาน');
      $('#button_action').val('Add');
      $('#action').val('Add');
      $('#formModal').modal('show');
      clear_field();
    });

    function clear_field() {
      $('#user_form')[0].reset();

      $("#username_error_message").hide();
      $("#username").removeClass("is-invalid");

      $("#first_name_error_message").hide();
      $("#first_name").removeClass("is-invalid");

      $("#last_name_error_message").hide();
      $("#last_name").removeClass("is-invalid");

      $("#address_error_message").hide();
      $("#address").removeClass("is-invalid");

      $("#email_error_message").hide();
      $("#email").removeClass("is-invalid");

      $("#phone_error_message").hide();
      $("#phone").removeClass("is-invalid");
    }

    $('#user_form').on('submit', function (event) {
      event.preventDefault();
      adduser();
    });

    var error_username = false;
    var error_first_name = false;
    var error_last_name = false;
    var error_address = false;
    var error_email = false;
    var error_phone = false;

    $("#username").focusout(function () {
      check_username();
    });

    $("#first_name").focusout(function () {
      check_first_name();
    });

    $("#last_name").focusout(function () {
      check_last_name();
    });

    $("#address").focusout(function () {
      check_address();
    });

    $("#email").focusout(function () {
      check_email();
    });

    $("#phone").focusout(function () {
      check_phone();
    });

    function check_username() {

      if ($.trim($('#username').val()) == '') {
        $("#username_error_message").html("กรุณากรอกชื่อผู้ใช้งาน.");
        $("#username_error_message").show();
        $("#username").addClass("is-invalid");
        error_username = true;
      }
      else {
        $("#username_error_message").hide();
        $("#username").removeClass("is-invalid");
      }
    }

    function check_first_name() {

      if ($.trim($('#first_name').val()) == '') {
        $("#first_name_error_message").html("กรุณากรอกชื่อจริง.");
        $("#first_name_error_message").show();
        $("#first_name").addClass("is-invalid");
        error_first_name = true;
      }
      else {
        $("#first_name_error_message").hide();
        $("#first_name").removeClass("is-invalid");
      }
    }

    function check_last_name() {

      if ($.trim($('#last_name').val()) == '') {
        $("#last_name_error_message").html("กรุณากรอกนามสกุล.");
        $("#last_name_error_message").show();
        $("#last_name").addClass("is-invalid");
        error_last_name = true;
      }
      else {
        $("#last_name_error_message").hide();
        $("#last_name").removeClass("is-invalid");
      }
    }

    function check_address() {

      if ($.trim($('#address').val()) == '') {
        $("#address_error_message").html("กรุณากรอกที่อยู่.");
        $("#address_error_message").show();
        $("#address").addClass("is-invalid");
        error_address = true;
      }
      else {
        $("#address_error_message").hide();
        $("#address").removeClass("is-invalid");
      }
    }

    function check_email() {

      if ($.trim($('#email').val()) == '') {
        $("#email_error_message").html("กรุณากรอกอีเมลล์.");
        $("#email_error_message").show();
        $("#email").addClass("is-invalid");
        error_email = true;
      }
      else {
        $("#email_error_message").hide();
        $("#email").removeClass("is-invalid");
      }
    }

    function check_phone() {

      if ($.trim($('#phone').val()) == '') {
        $("#phone_error_message").html("กรุณากรอกเบอร์มือถือ.");
        $("#phone_error_message").show();
        $("#phone").addClass("is-invalid");
        error_phone = true;
      }
      else {
        $("#phone_error_message").hide();
        $("#phone").removeClass("is-invalid");
      }
    }

    function adduser() {
      swal("Ready to go!", "", "success");

      error_username = false;
      error_first_name = false;
      error_last_name = false;
      error_address = false;
      error_email = false;
      error_phone = false;

      check_username();
      check_first_name();
      check_last_name();
      check_address();
      check_email();
      check_phone();

      if (error_username == false && error_first_name == false && error_last_name == false && error_address == false&& error_email == false&& error_phone == false) {

        data = $('#user_form').serialize();

        $.ajax({
          type: "POST",
          data: data,
          url: "user_action.php",
          dataType: "json",
          success: function (data) {
            if (data.status == 'success') {
              clear_field();
              datatable.ajax.reload();
              $('#formModal').modal('hide');
              swal("Successfully!", data.alert, "success");
            } else if (data.status == 'error') {
              swal("มีบางอย่างผิดพลาด.", "", "error");
            }
          },
          error: function () {
            swal("มีบางอย่างผิดพลาด.", "", "error");
          }
        });
        return false;
      } else {
        swal("", "Please make sure all required fields are filled out correctly.", "error");
        return false;
      }
    }

    var user_id = '';
    $(document).on('click', '.view_user', function () {
      user_id = $(this).attr('id');
      $.ajax({
        url: "user_action.php",
        type: "POST",
        data: { action: 'single_fetch', user_id:user_id },
        success:function(data){
          var data = JSON.parse(data);
          $('#view_id').text(data['id']);
          $('#view_username').text(data['username']);
          $('#view_first_name').text(data['first_name']);
          $('#view_last_name').text(data['last_name']);
          $('#view_address').text(data['address']);
          $('#view_email').text(data['email']);
          $('#view_phone').text(data['phone']);
        }
      });
    });

    $(document).on('click', '.edit_user', function () {
      user_id = $(this).attr('id');
      clear_field();
      $.ajax({
        url: "user_action.php",
        type: "POST",
        data:{ action: 'edit_fetch', user_id:user_id },
        success:function(data){
          var data = JSON.parse(data);
          $('#user_id').val(data['id']);
          $('#username').val(data.username);
          $('#first_name').val(data.first_name);
          $('#last_name').val(data.last_name);
          $('#address').val(data.address);
          $('#email').val(data.email);
          $('#phone').val(data.phone);
          $('#modal_title').text('Edit user');
          $('#button_action').val('Edit');
          $('#action').val('Edit');
          $('#formModal').modal('show');
        }
      });
    });

    $(document).on('click', '.delete_user', function () {
      user_id = $(this).attr('id');
      swal({
        title: "Are you sure?",
        text: "You want to delete this user!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              url: "user_action.php",
              method: "POST",
              data: { action: 'delete', user_id: user_id },
              success: function (data) {
                datatable.ajax.reload();
                swal("user has been successfully deleted!", {
                  icon: "success",
                });
              },
              error: function () {
                swal("Oops! Something went wrong.", "", "error");
              }
            })
          }
        });
    });
  });

</script>