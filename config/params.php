<?php

return [
    'adminEmail' => 'admin@example.com',

    'pageSize' => [
        'manage' => 10,
        'user'   => 10,
        'product' => 6,
    ],
    'defaultValue' => [
        'avatar' => 'assets/admin/img/contact-img.png',
    ],
     "dir_path" =>"/tmp/vr/",
    "gen_xml_path" => "/tmp/vr/vtour/tour.xml",
//    "source_path" => "/web/vtour/panos/*",
//    "dest_path"=> "vtour/panos/",
    "source_path" => "/tmp/vr/vtour/panos/*",
    "dest_path"=> "vtour/panos/",

];
