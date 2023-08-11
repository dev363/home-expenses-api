<?php
include('db.php');

class Expenses extends DbConnection
{
    private $table = 'expenses';
    private $QueryName = 'Expense';

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

        $response = [];
        $response['status'] = 0;

        if (!isset($_GET['userId'])) {
            $response['status_message'] = "userId is required";
            $this->ApiResponse($response);
        }

        $userID = isset($id) ? $id : $_GET['userId'];
        $query = "SELECT * FROM " . $this->table . " WHERE userid = " . $userID;
        $result = mysqli_query($this->DB, $query);

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

    function getQueryByCategoryId($id = null)
    {

        $response = [];
        $response['status'] = 0;

        if (!isset($_GET['categoryId'])) {
            $response['status_message'] = "categoryId is required";
            $this->ApiResponse($response);
        }

        $categoryId = isset($id) ? $id : $_GET['categoryId'];
        $query = "SELECT * FROM " . $this->table . " WHERE category = " . $categoryId;
        $result = mysqli_query($this->DB, $query);

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
        $response = [];
        $response['status'] = 0;

        if (!isset($input["category"])) {
            $response['status_message'] = "category is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["details"])) {
            $response['status_message'] = "details is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["pay"])) {
            $response['status_message'] = "pay is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["userid"])) {
            $response['status_message'] = "userid is required";
            $this->ApiResponse($response);
        }

        $category = $input["category"];
        $details = $input["details"];
        $pay = $input["pay"];
        $userid = $input["userid"];

        $query = "INSERT INTO " . $this->table . "(category,details, pay,userid) VALUES('" . $category . "','" . $details . "','" . $pay . "','" . $userid . "')";
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
        if (!isset($input["category"])) {
            $response['status_message'] = "category is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["details"])) {
            $response['status_message'] = "details is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["pay"])) {
            $response['status_message'] = "pay is required";
            $this->ApiResponse($response);
        }
        if (!isset($input["userid"])) {
            $response['status_message'] = "userid is required";
            $this->ApiResponse($response);
        }

        $category = $input["category"];
        $details = $input["details"];
        $pay = $input["pay"];
        $userid = $input["userid"];
        $id = $input["id"];

        $query = "UPDATE " . $this->table . " SET `category`='" . $category . "', `details`='" . $details . "', `pay`='" . $pay . "', `userid`='" . $userid . "' WHERE `id`='" . $id . "'";

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
