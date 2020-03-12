<?php

  include "connection.php";

  session_start();

  $output = '';

  if(isset($_POST["action"])){

    // Fetch user
    if($_POST["action"] == "user_fetch"){

      // Read value
      $draw = $_POST['draw'];
      $row = $_POST['start'];
      $rowperpage = $_POST['length'];
      $columnIndex = $_POST['order'][0]['column'];
      $columnName = $_POST['columns'][$columnIndex]['data'];
      $columnSortOrder = $_POST['order'][0]['dir'];
      $searchValue = $_POST['search']['value'];

      // Search
      $searchQuery = " ";
      if($searchValue != ''){
        $searchQuery = " and (id LIKE '%".$searchValue."%' OR
              username LIKE '%".$searchValue."%' OR
              first_name LIKE '%".$searchValue."%' OR
              last_name LIKE '%".$searchValue."%' OR
              address LIKE '%".$searchValue."%' OR
              email LIKE '%".$searchValue."%' OR
              phone LIKE '%".$searchValue."%' ) ";
      }

      // Total number of records without filtering
      $sel = mysqli_query($conn,"SELECT count(*) AS allcount FROM users");
      $records = mysqli_fetch_assoc($sel);
      $totalRecords = $records['allcount'];

      // Total number of records with filtering
      $sel = mysqli_query($conn,"SELECT count(*) AS allcount FROM users WHERE 1 ".$searchQuery);
      $records = mysqli_fetch_assoc($sel);
      $totalRecordwithFilter = $records['allcount'];

      // Fetch records
      $empQuery = "SELECT * FROM users WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ".$row.",".$rowperpage;
      $empRecords = mysqli_query($conn, $empQuery);
      $data = array();



      while ($row = mysqli_fetch_assoc($empRecords)) {

        $data[] = array(
          "id"              =>$row['id'],
          "username"     =>$row['username'],
          "first_name"     =>$row['first_name'],
          "last_name"          =>$row['last_name'],
          "address"            =>$row['address'],
          "email"            =>$row['email'],
          "phone"            =>$row['phone'],
          "action"       =>
          '<button type="button" class="btn btn-primary edit_user" data-toggle="modal" data-target="#editModal" id="'.$row['id'].'">Update</button>
          <button type="button" class="btn btn-danger delete_user" id="'.$row['id'].'">Delete</button>
          '
        );


      }

      $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
      );

      echo json_encode($response);

    }

    // Add user
    if($_POST["action"] == "Add"){

      $username = $_POST['username'];
      $first_name = $_POST['first_name'];
      $last_name = $_POST['last_name'];
      $address = $_POST['address'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];

      $sql = "INSERT INTO users (username, first_name, last_name, address, email, phone) VALUES('$username', '$first_name', '$last_name', '$address', '$email', '$phone')";

      if(mysqli_query($conn, $sql)){
        $output = array(
          'status'        => 'success',
          'alert'         => 'New user has been successfully added.'
        );
      }else{
        $output = array(
          'status'        => 'error'
        );
      }

      echo json_encode($output);

    }

    // Update user
    if($_POST["action"] == "Edit"){

      $user_id = $_POST['user_id'];
      $username = $_POST['username'];
      $first_name = $_POST['first_name'];
      $last_name = $_POST['last_name'];
      $address = $_POST['address'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];

      $sql = "UPDATE users SET username = '$username',
                                  first_name = '$first_name',
                                  last_name = '$last_name',
                                  address = '$address',
                                  email = '$email',
                                  phone = '$phone',
                                  WHERE id = '$user_id'";

      $result = mysqli_query($conn, $sql);

      $output = array(
        'status'        => 'success',
        'alert'         => 'user has been successfully updated.'
      );

        echo json_encode($output);
    }

    // Single edit fetch
    if($_POST["action"] == "edit_fetch"){

      $user_id = $_POST['user_id'];

      $sql = "SELECT id, username, first_name, last_name, address , email, phone FROM users WHERE id = '$user_id'";

      $result = mysqli_query($conn, $sql);

      $row = mysqli_fetch_row($result);

      $output = array(
        "id"		        =>	$row[0],
        "username"		      =>	$row[1],
        "first_name"		    => 	$row[2],
        "last_name"		      => 	$row[3],
        "address"		        => 	$row[4],
        "email"		          => 	$row[5],
        "phone"	            =>	$row[6],
      );

      echo json_encode($output);

    }

    // Delete user
    if($_POST["action"] == "delete"){

      $user_id = $_POST['user_id'];

      $sql = "DELETE FROM users WHERE id='$user_id'";

      $result = mysqli_query($conn, $sql);

      $output = array(
          'status'        => 'success'
      );

      echo json_encode($output);

    }

  }

?>