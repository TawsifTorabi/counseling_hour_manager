<?php

$pattern = '/(\d+)\s+\((\d+)-(\d+)\)/';
$string = '330 (021182009-0212310077) 331 (0212310099-0212311342)';
$current_user_id = '0212310098';

preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $room_number = $match[1];
    $start_id = $match[2];
    $end_id = $match[3];

    if ($current_user_id >= $start_id && $current_user_id <= $end_id) {
        echo "Current user ID belongs to room number $room_number";
    }
}

?>