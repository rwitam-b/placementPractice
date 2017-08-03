<?php
    require 'sessionize.php';
    require_once 'DB.php';
    require 'adminPrivilege.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Student Reports</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <script src="includes/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="includes/DataTables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="includes/DataTables/Buttons-1.3.1/css/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="includes/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="includes/DataTables/Buttons-1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="includes/DataTables/Buttons-1.3.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="includes/DataTables/jszip.min.js"></script>
    <script type="text/javascript" src="includes/DataTables/Buttons-1.3.1/js/buttons.html5.min.js"></script>
    <style>
        @font-face {
            font-family: "Mohave-Bold";
            src: url("includes/bootstrap/3.3.7/fonts/Mohave-Bold.otf") format("opentype");
        }

        th {
          text-align: center;
        }
        
        h1, h2, h3{
            font-family: "Mohave-Bold";
        }

        h4, h5, h6{
            font-family: "Mohave-SemiBold";
        } 
        
        .banner{
            display:block;
            margin:auto;
            max-width:80vw;            
        }

        .banner-small{
            max-width: 70vw;
        }

        .jumbotron{
            padding-bottom:0;
            background-color:rgba(255,203,6,0.5);       
            margin-bottom:0px;     
        }

        .jumbotron h1{
            margin: 0px;
        }
    </style>
    <script>
      function createTable(cols,data){
        $("#data thead").empty();
        $("#data tbody").empty();
        $("#data thead").append("<tr></tr>");
        for(var i in cols){
          $("#data thead tr").append("<th>"+cols[i].toUpperCase().replace("_"," ")+"</th>");
        }        
        for(var i in data){
          $("#data tbody").append("<tr></tr>");
          for(var j in cols){
            if(cols[j].localeCompare("name")==0){
              $("#data tbody tr:last-child").append("<td>"+data[i][cols[j]]+"</td>");
            }else{
              $("#data tbody tr:last-child").append("<td>"+data[i][cols[j]]+"</td>");
            }
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
                          table=$("#data").DataTable({
                              dom: 'Bfrtip',
                              buttons: ['excel']
                          });
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

<body><br>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner banner-small">
        <h1 align="center"><small>Admin Panel</small></h1>
    </div><br>
    <div class="container-fluid">
        <?php
            include("header.php");
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
            include("footer.php");
        ?>
    </div>
</body>
</html>
