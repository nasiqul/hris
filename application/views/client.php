<!DOCTYPE html>
<html>
<?php require_once(APPPATH.'views/header/head.php'); ?>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">

    <!-- NAVBAR -->
    <?php require_once(APPPATH.'views/header/navbar-top.php'); ?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
      <div class="container-fluid">
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-sm-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url()?>app/img/user1-128x128.jpg" alt="User profile picture">

                  <h3 class="profile-username text-center">John Doe</h3>

                  <p class="text-muted text-center">Software Engineer</p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Personal Leave</b> <a class="pull-right"><small class="label pull-right bg-yellow">1/10</small></a>
                    </li>
                  </ul>
                </div>
                <!-- /.box-body -->
              </div>

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">About Me</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <strong><i class="fa fa-map-marker margin-r-5"></i> Departemen</strong>

                  <p class="text-muted">
                    B.S. in Computer Science from the University of Tennessee at Knoxville
                  </p>

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Section</strong>

                  <p class="text-muted">Malibu, California</p>

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Sub - Section</strong>

                  <p class="text-muted">Malibu, California</p>

                  <strong><i class="fa fa-calendar margin-r-5"></i> Join Date</strong>

                  <p class="text-muted">01 January 2019</p>

                  <strong><i class="fa fa-user margin-r-5"></i> Employee Status</strong>

                  <p class="text-muted">Permanent</p>

                  <strong><i class="fa fa-bullhorn margin-r-5"></i> Position</strong>

                  <p class="text-muted">Operator / Sub Leader</p>                  

                  <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

                  <p>
                    <span class="label label-danger">UI Design</span>
                    <span class="label label-success">Coding</span>
                    <span class="label label-info">Javascript</span>
                    <span class="label label-warning">PHP</span>
                    <span class="label label-primary">Node.js</span>
                  </p>

                  <hr>

                  <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.widget-user -->
            </div>
            <div class="col-sm-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-user"></i>&nbsp&nbsp<span>Attendance</span></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                 <table class="table table-responsive table-striped" id="table1">
                   <thead>
                     <th>Period</th>
                     <th>Absent</th>
                     <th>Permit</th>
                     <th>Sick</th>
                     <th>Come Late</th>
                     <th>Home Early</th>
                     <th>Personal Leave</th>
                     <th>Diciplinary Allowance</th>
                     <th>Overtime (Hour)</th>
                   </thead>
                   <tbody>
                   </tbody>
                 </table>
               </div>
               <!-- /.box-body -->
             </div>
           </div>
         </div>
         <!-- /.box -->
       </section>
       <!-- /.content -->
     </div>
     <!-- /.container -->
   </div>
   <!-- /.content-wrapper -->
   <?php require_once(APPPATH.'views/footer/foot2.php'); ?>
 </div>
 <!-- ./wrapper -->

 <script>
  var table;
  $(document).ready(function() {
    table = $('#table1').DataTable({
      "processing": true,
      "serverSide": true,
      "searching": false
      "bLengthChange": false,
      "bInfo": false,
      "order": [],
      // "ajax": {
      //       "url": "<?php echo base_url('home/ajax')?>",            
      //       "type": "POST"
      //   },
      "columnDefs": [
      {
          "targets": [ 0 ], //first column / numbering column
          "orderable": false, //set not orderable
        }]
      });

  })
</script>
</body>
</html>
