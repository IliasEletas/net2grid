<?php
class config
{
    //const host = 'rabbitmq@candidatemq.n2g-dev.net';
    const host = '127.0.0.1';
    const port = '5672';
    const user = 'cand_e2ro';
    const password = 'awTAS6m1hjJzVRg4';
    const exchange = 'cand_e2ro';
    const queue = 'cand_e2ro_results';
    const apiUrl = 'https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results';
    const vhost = 'candidatemq.n2g-dev.net';


    function get_host()
    {
        return self::host;
    }
    function get_port()
    {
        return self::port;
    }
    function get_password()
    {
        return self::password;
    }
    function get_user()
    {
        return self::user;
    }
    function get_exchange()
    {
        return self::exchange;
    }
    function get_queue()
    {
        return self::queue;
    }
    function get_apiUrl()
    {
        return self::apiUrl;
    }
    function get_vhost()
    {
        return self::vhost;
    }
}

