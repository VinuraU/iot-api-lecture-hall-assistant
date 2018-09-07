<?php

    // Reset Audio Variables
    $app->get('/controllers/reset', function ($request, $response, $args) {
        
        require_once 'config.php';

        $responseArray = array("status" => 500, "data"=> "", "errors" => "");

        $response = $response->withHeader('Content-type', 'application/json');

        if ($conn->connect_error){
            $responseArray["status"] = 503;
            $responseArray["errors"] = "database server not reachable";
            $response = $response->withStatus(503);
        }else{
            $sql = "UPDATE tblcontrollers SET ctr_status=0";
            $conn->query($sql);
            $responseArray["status"] = 200;
            $responseArray["data"] = "success";
            $response = $response->withStatus(200);
        }
        return $response->withJson($responseArray);
    });

    // Set Current Audio Level
    $app->get('/controllers/get/{id}', function ($request, $response, $args) {

        require_once 'config.php';

        $responseArray = array("status" => 500, "data"=> "", "errors" => "");

        $response = $response->withHeader('Content-type', 'application/json');

        if ($conn->connect_error){
            $responseArray["status"] = 503;
            $responseArray["errors"] = "database server not reachable";
            $response = $response->withStatus(503);
        }else{
            $id = $args['id'];
            if (is_numeric($id)){
                $sql = "SELECT * FROM tblcontrollers WHERE ctr_id='$id'";
                $results = $conn->query($sql);
                $row = $results->fetch_assoc();
                $status = $row['ctr_status'];
                if ($conn->affected_rows > 0){
                    // Response
                    $responseArray["status"] = 200;
                    $responseArray["data"] = (int)$status;
                    $response = $response->withStatus(200);
                }else{
                    $responseArray["status"] = 400;
                    $responseArray["errors"] = "invalid controller id";
                    $response = $response->withStatus(400);
                }
            }else{
                $responseArray["status"] = 400;
                $responseArray["errors"] = "invalid controller format";
                $response = $response->withStatus(400);
            }
        }

        return $response->withJson($responseArray);
    });

?>