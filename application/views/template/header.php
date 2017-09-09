<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>SVN Web Admin</title>
<!--    <link rel="stylesheet" href="--><?php //echo base_url('_static/select2/css/select2.min.css') ?><!--">-->
    <link rel="stylesheet" href="<?php echo base_url('_static/sui/semantic.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('_static/style.css') ?>">
    <script src="<?php echo base_url('_static/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('_static/sui/semantic.min.js'); ?>"></script>
<!--    <script src="--><?php //echo base_url('_static/select2/js/select2.full.min.js'); ?><!--"></script>-->
<!--    <script src="--><?php //echo base_url('_static/select2/js/i18n/zh-CN.js'); ?><!--"></script>-->
</head>
<body>
<div class="ui fixed inverted menu">
    <div class="ui container">
        <a href="#" class="header item">
           SVN在线管理
        </a>
        <a href="<?php echo site_url('') ?>" class="item">权限查看</a>
        <a href="<?php echo site_url('admin') ?>" class="item">库管理</a>
        <a href="<?php echo site_url('admin/users') ?>" class="item">用户管理</a>
    </div>
</div>
<div id="container" class="ui main text container">