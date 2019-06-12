<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>READINGS</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo URL::asset('assets/style.css'); ?>"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    </head>

    <body id="page-top">

        <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

            <a class="navbar-brand mr-1" href="/<?php echo env('SUB_FOLDER'); ?>">READINGS</a>
            <!-- Navbar Search -->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Navbar -->
            <ul class="navbar-nav ml-auto ml-md-0">

                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Login</a>
                    </div>
                </li>
            </ul>

        </nav>

        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="sidebar navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Pages</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                        <a class="dropdown-item" href="/<?php echo env('SUB_FOLDER'); ?>/login">Login</a>
                        <a class="dropdown-item" href="/<?php echo env('SUB_FOLDER'); ?>/upload">Upload from backup</a>
                    
                    </div>
                    
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="mapsDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Map</span></a>
                    
                    <div class="dropdown-menu" aria-labelledby="mapsDropdown">
                        <h6 class="dropdown-header">MAPS:</h6>
                        <a class="dropdown-item" href="/<?php echo env('SUB_FOLDER'); ?>/map/overview">OVERVIEW</a>
                    </div>
                </li>
                
            </ul>

            <div id="content-wrapper">

                <div class="container-fluid">
                    <div class="alert alert-secondary" role="alert">
                        <?php echo $date->format('F') . ' ' . $date->format('Y'); ?>
                    </div>
                    <!-- Icon Cards-->
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <div class="mr-5">READINGS COLLECTED</div>
                                    <h1><?php echo $read; ?></h1>
                                </div>
                                <a class="card-footer text-white clearfix small z-1" href="#">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <div class="mr-5">TOTAL CLIENTS</div>
                                    <h1><?php echo $clients; ?></h1>
                                </div>
                                <a class="card-footer text-white clearfix small z-1" href="#">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <div class="mr-5">TOTAL UNREAD</div>
                                    <h1><?php echo $unread; ?></h1>
                                </div>
                                <a class="card-footer text-white clearfix small z-1" href="/<?php echo env('SUB_FOLDER'); ?>/unread/<?php echo $date->format('Y') . '/' . $date->format('m') ?>">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Area Chart Example-->
                    <div class="card my-5">
                        <div class="card-header">
                            <i class="fas fa-chart-area"></i>
                            DATA COLLECTORS AREA</div>
                        <div class="card-body">
                            <div class="row count my-5">
                                <?php foreach ($stats as $key => $value) { ?>
                                    <div class="card mx-3" style="width: 18rem;">
                                        <div class="card-body">
                                            <h3 class="card-title"><?php echo $value->name; ?></h3>
                                            <p class="display-4"><?php echo $value->count; ?></p>
                                            <a href="/<?php echo env('SUB_FOLDER'); ?>/details/stats/<?php echo $value->name; ?>">More Info</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- DataTables Example -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Data Table Example</div>
                        <div id="accordion">
                            <div class="card-body">
                                <?php
                                foreach ($daily as $key => $value) {
                                    echo $key;
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>COUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
    <?php foreach ($value as $val) { ?>
                                                    <tr>
                                                        <td><?php echo $val->date; ?> </td>
                                                        <td><?php echo $val->count; ?></td>
                                                    </tr>
    <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
<?php } ?>
                            </div>
                            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

                <!-- Sticky Footer -->
                <footer class="sticky-footer">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright © MUTALL 2019</span>
                        </div>
                    </div>
                </footer>

            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /#wrapper -->
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

    </body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>
