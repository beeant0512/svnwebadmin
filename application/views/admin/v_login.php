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
    <link rel="stylesheet" href="<?php echo base_url('_static/sui/semantic.min.css') ?>">
    <style type="text/css">
        body {
            background-color: #DADADA;
        }
        body > .grid {
            height: 100%;
        }
        .image {
            margin-top: -100px;
        }
        .column {
            max-width: 450px;
        }
    </style>
    <script src="<?php echo base_url('_static/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('_static/sui/semantic.min.js'); ?>"></script>
</head>
<body>

    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <h2 class="ui teal image header">
                <div class="content">登录</div>
            </h2>
            <form class="ui large form" method="post">
                <div class="ui stacked segment">
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input type="text" name="usr" placeholder="E-mail address">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="lock icon"></i>
                            <input type="password" name="pwd" placeholder="Password">
                        </div>
                    </div>
                    <div class="ui fluid large teal submit button">登录</div>
                </div>

                <div class="ui error message"></div>

            </form>
        </div>
    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong>
        seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
    </p>
    <script>
        (function () {
            $(document)
                .ready(function () {
                    $('.ui.form')
                        .form({
                            fields: {
                                usr: {
                                    identifier: 'usr',
                                    rules: [
                                        {
                                            type: 'empty',
                                            prompt: '请输入用户名'
                                        }
                                    ]
                                },
                                pwd: {
                                    identifier: 'pwd',
                                    rules: [
                                        {
                                            type: 'empty',
                                            prompt: '请输入密码'
                                        },
//                                        {
//                                            type: 'length[6]',
//                                            prompt: '密码长度不能少于6位'
//                                        }
                                    ]
                                }
                            }
                        })
                    ;
                })
            ;
        })()
    </script>
</body>
</html>