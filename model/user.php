<?php
class User
{
    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read users
    public function read($id)
    {
        $query = "SELECT * FROM " . $this->table_name;
        if ($id != null) {
            $query = $query . " WHERE id=" . $id;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // create product
    public function create($data)
    {
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                username=:username, password=:password, first_name=:first_name, last_name=:last_name";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($data->username));
        $this->password = htmlspecialchars(strip_tags($data->password));
        $this->first_name = htmlspecialchars(strip_tags($data->first_name));
        $this->last_name = htmlspecialchars(strip_tags($data->last_name));

        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", password_hash($this->password, PASSWORD_DEFAULT));
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update($data)
    {
        if ($data != null && !empty($data->id)) {
            $query = "UPDATE
            " . $this->table_name . "
            SET
                username = :username,
                password = :password,
                first_name = :first_name,
                last_name = :last_name
            WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($data->username));
            $this->password = htmlspecialchars(strip_tags($data->password));
            $this->first_name = htmlspecialchars(strip_tags($data->first_name));
            $this->last_name = htmlspecialchars(strip_tags($data->last_name));
            $this->id = htmlspecialchars(strip_tags($data->id));

            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            }
        }

        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
