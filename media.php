<?php
$rs = array(
        from => "e7",
        to => "e5",
        turn => 0,
        promotion => 'q'
        );

header('Content-Type: application/json; charset=utf-8');
echo json_encode($rs);
