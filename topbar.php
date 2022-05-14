<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-primary navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <?php if(isset($_SESSION['login_id'])): ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="" role="button"><i class="fas fa-bars"></i></a>
      </li>
    <?php endif; ?>
      <li class="hide">
        <a class="nav-link text-white"  href="./" role="button"> <large><b><?php echo $_SESSION['system']['name'] ?></b></large></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
     
      <li class="nav-item hide">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown mx-0 px-0">
        <a class="nav-link mx-0 px-0 notif-toggle"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
          <span>
            <div class="d-felx badge-pill mt-0">
              <span class="fa fa-bell mr-0 mb-1 mt-0 pt-0" style="font-size:25px;padding:0;"></span><span class="badge badge-danger notif-count"></span>
            </div>
          </span>
        </a>
        <div class="dropdown-menu notif-menu y-scroll" style="height:400px;y-overflow:auto"></div>
        <!-- <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
          <a class="dropdown-item" href="javascript:void(0)" id="">No New Notifications</a>
        </div> -->
      </li>
      <li class="nav-item dropdown mx-0 px-0">
        <a class="nav-link mx-0 px-0"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
          <span>
            <div class="d-felx badge-pill">
              <span class="fa fa-bell mr-2 hide"></span>
              <span><b><?php echo ucwords($_SESSION['login_firstname']) ?></b></span>
              <span class="fa fa-angle-down ml-2"></span>
            </div>
          </span>
        </a>
        <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
          <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a>
          <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <script>
    $(document).ready(function () {
      $('#manage_account').click(function(){
        uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
      })
      function load_unseen_notification(view = '')
      {
          $.ajax({
          url:"fetch.php",
          method:"POST",
          data:{view:view},
          dataType:"json",
          success:function(data)
          {
              $('.notif-menu').html(data.notification);
              if(data.unseen_notification > 0)
              {
              $('.notif-count').html(data.unseen_notification);
              }
              else {

              }
          }
          });
      }
      
      load_unseen_notification();

      $(document).on('click', '.notif-toggle', function(){
        $('.notif-count').html('');
        load_unseen_notification('yes');
      });

      // $(document).on('click', '.notifMeAccept', function(){
      //   alert("Are you sure you want to 'Accept' this task?");
      // });

      // $(document).on('click', '.notifMeDecline', function(){
      //   alert("Are you sure you want to 'Decline' this task?");
      // });
      
      setInterval(function(){ 
        load_unseen_notification();
      }, 5000);
    });
  </script>
