<?php
    if(isset($_SERVER['HTTP_REFERER'])){
?>
    <script>
        $(document).ready(function() {
            $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 2000);
            $('#myModal').modal('show');                           
        });
    </script>
    <div align="center" class="row">
            <img class="img-responsive" src="images/block.jpg">
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">                
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Permission Denied</h4>
                </div>
                <div class="modal-body">
                    <p>Admin Privileges Required For This Section !</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div> 
        </div>
    </div>
<?php
    unset($_SERVER['HTTP_REFERER']);
    }
    else{
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Refresh:0;url='.$redirect);
    }
?>