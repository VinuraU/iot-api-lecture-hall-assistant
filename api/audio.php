<?php

    // Reset Audio Variables
    $app->get('/audio/reset', function ($request, $response, $args) {
        
        require_once 'config.php';

        $responseArray = array("status" => 500, "data"=> "", "errors" => "");

        $response = $response->withHeader('Content-type', 'application/json');

        if ($conn->connect_error){
            $responseArray["status"] = 503;
            $responseArray["errors"] = "database server not reachable";
            $response = $response->withStatus(503);
        }else{
            $sql = "UPDATE tblinput SET inp_value=0";
            $conn->query($sql);
            $responseArray["status"] = 200;
            $responseArray["data"] = "success";
            $response = $response->withStatus(200);
        }
        return $response->withJson($responseArray);
    });
    
    // Set Current Audio Level
    $app->get('/audio/set/{id}/{level}', function ($request, $response, $args) {

        require_once 'config.php';

        $responseArray = array("status" => 500, "data"=> "", "errors" => "");

        $response = $response->withHeader('Content-type', 'application/json');

        if ($conn->connect_error){
            $responseArray["status"] = 503;
            $responseArray["errors"] = "database server not reachable";
            $response = $response->withStatus(503);
        }else{
            $level = $args['level'];
            $id = $args['id'];
            if (is_numeric($level) and $level >= 0 and $level <= 255 and is_numeric($id)){
                $sql = "UPDATE tblinput SET inp_value='$level' WHERE inp_id='$id'";
                $conn->query($sql);
                if ($conn->affected_rows > 0){
                    // Response
                    $responseArray["status"] = 200;
                    $responseArray["data"] = "success";
                    $response = $response->withStatus(200);
                }else{
                    $responseArray["status"] = 400;
                    $responseArray["errors"] = "invalid device id";
                    $response = $response->withStatus(400);
                }
            }else{
                $responseArray["status"] = 400;
                $responseArray["errors"] = "invalid audio level";
                $response = $response->withStatus(400);
            }
        }

        return $response->withJson($responseArray);
    });
    
    // Get Relative Audio Level
    $app->get('/audio/get', function ($request, $response, $args) {

        require_once 'config.php';

        $responseArray = array("status" => 500, "data"=> "", "errors" => "");

        $response = $response->withHeader('Content-type', 'application/json');

        if ($conn->connect_error){
            $responseArray["status"] = 503;
            $responseArray["errors"] = "database server not reachable";
            $response = $response->withStatus(503);
        }else{
            // Calculate Mean
            $sql = "SELECT * FROM tblinput WHERE inp_status=1";
            $results = $conn->query($sql);
            $total_weight = 0;
            $mean = 0;
            while($row = $results->fetch_assoc()) {
                $mean += $row['inp_value'] * $row['inp_weight'];
                $total_weight += $row['inp_weight'];
            }
            $mean = $mean / $total_weight;
            $sql = "SELECT * FROM tblreference WHERE ref_id=1";
            $results = $conn->query($sql);
            $row = $results->fetch_assoc();
            $reference = $row['ref_value'];
            $mean = $mean - $reference;
            $responseArray["status"] = 200;
            $responseArray["data"] = $mean;
            $response = $response->withStatus(200);
        }

        return $response->withJson($responseArray);
    });

    // Save Reference Audio Level
    $app->get('/audio/reference/set', function ($request, $response, $args) {

        require_once 'config.php';

        $responseArray = array("status" => 500, "data"=> "", "errors" => "");

        $response = $response->withHeader('Content-type', 'application/json');

        if ($conn->connect_error){
            $responseArray["status"] = 503;
            $responseArray["errors"] = "database server not reachable";
            $response = $response->withStatus(503);
        }else{
            // Calculate Mean
            $sql = "SELECT * FROM tblinput WHERE inp_status=1";
            $results = $conn->query($sql);
            $total_weight = 0;
            $mean = 0;
            while($row = $results->fetch_assoc()) {
                $mean += $row['inp_value'] * $row['inp_weight'];
                $total_weight += $row['inp_weight'];
            }
            $mean = $mean / $total_weight;
            date_default_timezone_set('Asia/Colombo');
            $date = date("Y-m-d h:i:s");
            $sql = "UPDATE tblreference SET ref_value='$mean', ref_time='$date' WHERE ref_id=1";
            $conn->query($sql);
            // Response
            $responseArray["status"] = 200;
            $responseArray["data"] = "success";
            $response = $response->withStatus(200);
        }

        return $response->withJson($responseArray);
    });
?>