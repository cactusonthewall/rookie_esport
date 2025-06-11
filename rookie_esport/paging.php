<?php 
function generate_page_proposal($search, $total_data, $limit, $no_hal) {
    $hasil = "";
    $max_hal = ceil($total_data / $limit); 
    
    for ($hal = 1; $hal <= $max_hal; $hal++) {
        if ($no_hal == $hal) {
            $hasil .= "<b>$hal</b>";  
        } else {
            $hasil .= "<a href='?page=$hal&search=" . urlencode($search) . "'>$hal</a> "; 
        }
    }

    return $hasil;  
}

function generate_page($search, $total_data, $limit, $no_hal) {
    $hasil = "";
    $max_hal = ceil($total_data / $limit); 
    
    for ($hal = 1; $hal <= $max_hal; $hal++) {
        if ($no_hal == $hal) {
            $hasil .= "<b>$hal</b>";  
        } else {
            $hasil .= "<a href='?page=$hal&search=" . urlencode($search) . "'>$hal</a> "; 
        }
    }

    return $hasil;  
}

function generate_page_id($search, $total_data, $limit, $no_hal, $idteam) {
    $hasil = "";
    $max_hal = ceil($total_data / $limit); 
    $search_param = ($search) ? "&search=" . urlencode($search) : '';
    $idteam_param = "&idteam=" . urlencode($idteam);

    for ($hal = 1; $hal <= $max_hal; $hal++) {
        if ($no_hal == $hal) {
            $hasil .= "<b>$hal</b>"; 
        } else {
            $hasil .= "<a href='?page=$hal$search_param$idteam_param'>$hal</a> "; 
        }
    }

    return $hasil;  
}

function generate_page_member($search, $total_data, $limit, $no_hal, $teamId) {
    $hasil = "";
    $max_hal = ceil($total_data / $limit);
    
    $search_param = ($search) ? "&search=" . urlencode($search) : '';
    $idteam_param = "&team_id=" . urlencode($teamId);

    for ($hal = 1; $hal <= $max_hal; $hal++) {
        if ($no_hal == $hal) {
            $hasil .= "<b>$hal</b>"; 
        } else {
            $hasil .= "<a href='?page=$hal$search_param$idteam_param'>$hal</a> "; 
        }
    }

    return $hasil;
}
?>
