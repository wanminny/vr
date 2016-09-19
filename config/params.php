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
     "dir_path" =>"upload/",
    "xml_path" => "/web/vtour/tour.xml",
    'gen_xml_path' => "/web/upload/vtour/tour.xml",
    "edit_xml_path" => "/web/vtour/tour_editor.xml",
    "source_path" => "upload/vtour/panos/*",
    "dest_path"=> "vtour/panos/",

];
