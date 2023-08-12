<?php
include('db.php');

class User extends DbConnection
{
    private $table = 'users';
    private $QueryName = 'User';

    private function checkUser($email)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = '" . $email . "'";
        $result = mysqli_query($this->DB, $query);
        if ($result->num_rows === 0) {
            return false;
        }
        return true;
    }

    function getAllQuery()
    {
        $result = mysqli_query($this->DB, 'SELECT * FROM ' . $this->table);
        $response = [];
        if (!$result) {
            $response['status'] = 0;
            $response['status_message'] = $this->QueryName . " List not get.";
            $response['error'] = mysqli_error($this->DB);
        }
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $response['status'] = 1;
        $response['status_message'] = $this->QueryName . " list get Successfully.";
        $response['data'] = $data;
        $this->ApiResponse($response);
    }

    function getQueryByUserId($id = null)
    {
        $userID = $id || $_GET['userId'];
        $query = "SELECT * FROM " . $this->table . " WHERE id = " . $userID;
        $result = mysqli_query($this->DB, $query);
        $response = [];
        if (!$result) {
            $response['status'] = 0;
            $response['status_message'] = $this->QueryName . " List not get.";
            $response['error'] = mysqli_error($this->DB);
            $response['query'] = $query;
            $this->ApiResponse($response);
        }
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $response['status'] = 1;
        $response['status_message'] = $this->QueryName . " list get Successfully.";
        $response['data'] = $data;
        $this->ApiResponse($response);
    }

    function InsertQuery()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        print_r($_REQUEST);
        $response = [];
        $response['status'] = 0;


        if (!isset($input["email"])) {
            $response['status_message'] = "email is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["password"])) {
            $response['status_message'] = "password is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["fullname"])) {
            $response['status_message'] = "fullname is required";
            $this->ApiResponse($response);
        }

        $email = $input["email"];
        $password = $input["password"];
        $fullname = $input["fullname"];

        $isExist = $this->checkUser($email);
        if ($isExist) {
            $response['status_message'] = "Already exist";
        } else {
            $query = "INSERT INTO " . $this->table . "(email,password, fullname) VALUES('" . $email . "','" . $password . "','" . $fullname . "')";
            if (mysqli_query($this->DB, $query)) {
                $response['status'] = 1;
                $response['status_message'] = $this->QueryName . " created Successfully.";
                $input['id'] = mysqli_insert_id($this->DB);
                $response['data'] = $input;
            } else {
                $response['status'] = 0;
                $response['status_message'] = $this->QueryName . " creation failed.";
                $response['error'] = mysqli_error($this->DB);
            }
        }
        $this->ApiResponse($response);
    }

    function UpdateQuery()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $response = [];
        $response['status'] = 0;

        if (!isset($input["id"])) {
            $response['status_message'] = "id is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["fullname"])) {
            $response['status_message'] = "fullname is required";
            $this->ApiResponse($response);
        }

        $fullname = $input["fullname"];
        $id = $input["id"];

        $query = "UPDATE " . $this->table . " SET `fullname`='" . $fullname . "' WHERE `id`='" . $id . "'";
        if (mysqli_query($this->DB, $query)) {
            $response['status'] = 1;
            $response['status_message'] = $this->QueryName . " updated Successfully.";
            $response['data'] = $input;
        } else {
            $response['status'] = 0;
            $response['status_message'] = $this->QueryName . " updation failed.";
            $response['error'] = mysqli_error($this->DB);
        }

        $this->ApiResponse($response);
    }

    function DeleteQuery($categoryId = null)
    {
        $id = $categoryId;

        if (!isset($_GET["id"])) {
            $response['status_message'] = "id is required";
            $this->ApiResponse($response);
        } else {
            $id = $_GET['id'];
        }

        $response = [];
        $query = "DELETE FROM " . $this->table . " WHERE `id`='" . $id . "'";
        if (mysqli_query($this->DB, $query)) {
            $response['status'] = 1;
            $response['status_message'] = $this->QueryName . " deleted Successfully.";
            $response['data'] = $_GET;
        } else {
            $response['status'] = 0;
            $response['status_message'] = $this->QueryName . " delete failed.";
            $response['error'] = mysqli_error($this->DB);
        }

        $this->ApiResponse($response);
    }


    private function ApiResponse($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
}
