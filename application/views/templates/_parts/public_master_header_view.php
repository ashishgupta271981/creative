<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Theme Made By www.w3schools.com - No Copyright -->
  <title>CFI login </title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css');?>">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="//db.onlinewebfonts.com/c/41a60e6bfb9805dc44f0d18ef36c9319?family=Sophia+Nubian" rel="stylesheet" type="text/css"/>
  <link href="<?php echo site_url('assets/css/style.css');?>" rel="stylesheet" type="text/css">
  <link href="<?php echo site_url('assets/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
<script type="text/javascript"> var base_url = '<?php echo base_url();?>';</script>
<script type="text/javascript"> var current_url = '<?php echo current_url();?>';</script>

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="main-container">
    <div class="top-bar">
        <div class="container">
          <div class="row">
            <div class="col-sm-4 col-xs-12">
              <div class="bar-style"><a href="<?php echo site_url('/');?>"><i class="fa fa-bars fa-2x"></i></a></div>
            </div>
            <div class="col-sm-4 col-xs-6 text-center"> <img src="<?php echo site_url('assets/img/logo.png');?>"> </div>
            <div class="col-sm-4 col-xs-6 text-right"> <a href="<?php echo site_url('user');?>">Login &nbsp;&nbsp;&nbsp;</a>
                <a href="<?php echo site_url('user/register');?>" class="btn btn-danger">Sign Up</a> </div>
          </div>
        </div>
    </div>
