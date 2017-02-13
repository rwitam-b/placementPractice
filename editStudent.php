<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Admin Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.13/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.13/datatables.min.js"></script>
    <style>
     th {
      text-align: center;
    }
    </style>
    <!-- Bootstrap imports disabled for page since it interferes with Bootstrap inclusion of DataTables -->
    <!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
    <script>
      function createTable(cols,data){
        $("#data thead").empty();
        $("#data tbody").empty();
        $("#data thead").append("<tr></tr>");
        for(var i in cols){
          $("#data thead tr").append("<th>"+cols[i].toUpperCase()+"</th>");
        }
        for(var i in data){
          $("#data tbody").append("<tr></tr>");
          for(var j in cols){
            $("#data tbody tr:last-child").append("<td>"+data[i][cols[j]]+"</td>");
          }
        }
      }
      $(document).ready(function(){
          $("#error").hide();
          var table;
          $("#refresh").click(function(){
              $("#error").hide();
              var year=$("#year").val();
              var stream=$("#stream").val();
              var test=$("#test").val();
              $.post("ajaxStudentInfo.php",{year: year,stream: stream, test: test},
                  function (response){
                      try{
                          if(table)
                            table.destroy();
                          if(response.localeCompare("[]")==0){
                            createTable([""],[]);
                            $("#error").html("No Data For The Selected Filter");
                            $("#error").show();
                          }else{
                            obj=JSON.parse(response);
                            col=[];
                            for (var key in obj[0]){
                              col.push(key);
                            }
                            createTable(col,obj);
                          }
                          table=$("#data").DataTable();
                      }
                      catch(err){
                          console.log(err);
                          $("#error").html("Looks Like There Was A Problem !");
                          $("#error").show();
                      }
                  }
              );
          });
      });
    </script>
</head>

<body>
     <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container-fluid">
        <?php
            include("header.php");
            if(isset($_SESSION["aotemail_username"])){
                if (isset($_SESSION['aotemail_student'])){
                    $_SERVER['HTTP_REFERER']="test";
                    include("noAccess.php");
                }
                if(isset($_SESSION['aotemail_admin'])){
        ?>
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Registered Student Details</h2><br><br>
            </div>
            <div class="row">
              <div class="form-group col-xs-3">
                <label for="year">Select Year:</label>
                <select class="form-control" id="year">
                  <option value="*" selected>All</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>
              <div class="form-group col-xs-3">
                <label for="year">Select Stream:</label>
                <select class="form-control" id="stream">
                  <option value="*" selected>All</option>
                  <option value="CSE">Computer Science &amp; Engineering</option>
                  <option value="EE">Electrical Engineering</option>
                  <option value="ECE">Electronics &amp; Communications Engineering</option>
                  <option value="EIE">Electronics &amp; Instrumentation Engineering</option>
                  <option value="IT">Information Technology</option>
                  <option value="ME">Mechanical Engineering</option>
                </select>
              </div>
              <div class="form-group col-xs-3">
                <label for="year">Test Information:</label>
                <select class="form-control" id="test">
                  <option value="*" selected>Both</option>
                  <option value="Email">Email Writing</option>
                  <option value="Essay">Essay Writing</option>
                </select>
              </div>
              <div class="form-group col-xs-3">
                <label for="refresh">&nbsp;</label>
                <button id="refresh" type="button" class="btn btn-primary btn-block">Show !</button>
              </div>
            </div>
            <br><br>
            <div class="row">
              <div class="alert alert-info text-center">
                Select the desired filters and click on <strong>Show !</strong> to display the data.
              </div>
            </div>
            <div class="row">
              <div id="error" class="alert alert-danger text-center">
              </div>
            </div>
            <div class="row">
              <table class="table table-hover table-bordered" id="data">
                <thead>
                  <tr>
                    <th class="text-center">Nothing To Show !</th>
                  </tr>
                </thead>
                <tbody>
                  <tr></tr>
                </tbody>
              </table>
            </div>
        </div>
        <?php
                }
            }else{
                $_SERVER['HTTP_REFERER']="test";
                include("loginAccess.php");
            }
            include("footer.php");
        ?>
    </div>
</body>
</html>