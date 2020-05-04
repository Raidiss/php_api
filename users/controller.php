<?php

include_once '../config/database.php';
include_once '../model/user.php';

class Controller
{
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function read($user_id)
    {
        $user = new User($this->database->getConnection());
        $stmt = $user->read($user_id);
        $users_arr = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                "id" => $id,
                "username" => $username,
                "password" => $password,
                "first_name" => $first_name,
                "last_name" => $last_name,
            );

            array_push($users_arr, $user_item);
        }

        //200 OK
        http_response_code(200);
        echo json_encode($users_arr);
    }

    public function create()
    {
        $user = new User($this->database->getConnection());
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->username) && !empty($data->password) &&
            !empty($data->first_name) && !empty($data->last_name)) {

            if ($user->create($data)) {
                //201 created
                http_response_code(200);
                echo json_encode(array("message" => "User was created."));
            } else {
                //500 Internal Server Error
                http_response_code(500);
                echo json_encode(array("message" => "Unable to create user."));
            }
        } else {
            //400 bad request
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
    }

    public function update()
    {
        $user = new User($this->database->getConnection());
        $data = json_decode(file_get_contents("php://input"));

        if ($user->update($data)) {
            //200 ok
            http_response_code(200);
            echo json_encode(array("message" => "User was updated."));
        } else {
            //404 not found
            http_response_code(404);
            echo json_encode(array("message" => "Unable to update user."));
        }
    }

    public function delete($user_id)
    {
        $user = new User($this->database->getConnection());
        if (!empty($user_id) && $user->delete($user_id)) {
            //200 ok
            http_response_code(200);
            echo json_encode(array("message" => "User was deleted."));
        } else {
            //404 not found
            http_response_code(404);
            echo json_encode(array("message" => "Unable to delete user."));
        }
    }
}
