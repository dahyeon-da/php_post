<?php
require_once('../../config.php');

$sql = 'SELECT * FROM post_table;';

try {
    $result = $DB->selectAll($sql);
    if ($result) {
        $resultData = array(
            'result' => 'success',
            'data' => $result,
            'message' => 'Success Show Post'
        );

        echo json_encode($resultData);
    } else {
        $resultData = array(
            'result' => 'fail', 
            'message' => 'Failed Show Post'
        );
    }

} catch (Exception $e) {
    echo $e;
}
?>