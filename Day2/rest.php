<?php
require('MySQLHandler.php');
//  CONST DBUSERS = [
//     ['id'=>1, 'name'=>'mohamed','email'=> 'mohamed_1@gmail.com'],
//     ['id'=>2, 'name'=>'sara','email'=> 'sara@gmail.com'],
//     ['id'=>3, 'name'=>'sondos','email'=> 'sondos@gmail.com'],
//     ['id'=>4, 'name'=>'salma','email'=> 'salma@gmail.com'], 
// ];
$handler =new MySQLHandler('products');
// $handler->connect();
// if($conn){
//     echo 'connection true';
// }
// else if($conn=false){
//     echo 'connection false';
// }
// $products=$handler->get_data();
$method=$_SERVER['REQUEST_METHOD'];

// parse url http://localhost/day2-web-service/rest.php/users/1
$url= $_SERVER['REQUEST_URI'];
$parts=explode('/',$url);

//resource
$resource=isset($parts[3])? $parts[3]:null;
//resourceid
$resourceId=isset($parts[4])? $parts[4]:null;
// var_dump($resourceId);
// var_dump($parts);
// var_dump($getItemById[0]);
if($handler->connect()){
    if($resource == 'items'){
        switch($method){
            case 'GET':
                $getItemById=$handler->get_record_by_id($resourceId);
                 if(!$getItemById){
                        $getItemById= ['error'=>"Resource dosn't exist"];
                        http_response_code(404);
                    }
                else{
                        $getItemById= $getItemById[0];
                    }
                echo json_encode($getItemById);
                break;
            case 'POST':
                $newdata=json_decode(file_get_contents("php://input"),true);
                $handler->save($newdata);
                echo json_encode(["success"=>"item added successfully"]);
                break;
            case 'PUT':
                $updated_Id=$handler->get_record_by_id($resourceId);
                $handler->connect();
                if(!$updated_Id){
                    echo json_encode(['error'=>"Resource dosn't exist"]);
                        http_response_code(404);
                }
                else{
                $newdata=json_decode(file_get_contents("php://input"),true);
                // echo json_encode($newdata['name']);
                // $key=Key($newdata);
                // $newVal=$newdata[$key];
                // $oldVal=$handler->search($key,$newVal);
                // if($oldVal==false){
                //     $handler->connect();
                $handler->update($newdata,$resourceId);
                echo json_encode(["success"=>"item updated successfully"]);
            // }
            //     else{
            //         echo json_encode(["no updates"=>"item has the same  $key"]);  
            //     }
        }
                break;

            case 'DELETE':
                $Deleted_id=$handler->get_record_by_id($resourceId);
                $handler->connect();
                if($Deleted_id){
                    $handler->delete($resourceId);
                    echo json_encode(["success"=>"item deleted successfully"]);
                }
                else{
                    echo json_encode(["warning"=>"no such item"]);
                    http_response_code(404);
                }
                    break;
            default:
                echo json_encode(["error"=>"method not allowed!"]);
                http_response_code(405);
                break;
        }
    
    }
    else{
        $error= ['error'=>"Resource dosn't exist"];
        echo json_encode($error);
        http_response_code(404);
    }
    
}
 else{
    $error= ['error'=>"internal server error!"];
        echo json_encode($error);
        http_response_code(500);
 }


// function getUsers($id=null){
// if(!$id){
//     return returnResponse(DBUSERS,200);
// }

//     $users=array_filter(DBUSERS,function($user) use ($id){
//         return $user['id']==$id;
    
//     });
// if(empty($users)){
//    return returnResponse(['message'=>'user not found'],404);
// }
// return returnResponse(array_values($users)[0],200);
// }


// function returnResponse(array $data,$statusCode){
//     header('Content-Type:application/json');
//     http_response_code($statusCode);
//     echo json_encode($data);
// }







?>