<?php
return array(
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date{Y-m-d H:i:s.u} [%pid] From:%server{REMOTE_ADDR}:%server{REMOTE_PORT} Request:[%request] Message: %msg%n'
                )
            ),
            'params' => array(
                'file' => 'logs/logs_%s.log',
                'append' => true
            )
        )
    )
);