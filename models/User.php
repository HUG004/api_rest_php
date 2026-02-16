<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $n_control;
    public $name;
    public $entry_date;
    public $email;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create()
    {
        $query = "INSERT INTO {$this->table_name}
                  SET n_control = :n_control,
                      name = :name,
                      email = :email";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":n_control", $this->n_control);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // READ ALL
    public function read()
    {
        $query = "SELECT id, n_control, name, email, created_at
                  FROM {$this->table_name}
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE
    public function readOne()
    {
        $query = "SELECT id, n_control, name, email, created_at
                  FROM {$this->table_name}
                  WHERE id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->n_control = $row['n_control'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // UPDATE
    public function update()
    {
        $query = "UPDATE {$this->table_name}
                  SET n_control = :n_control,
                      name = :name,
                      email = :email
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":n_control", $this->n_control);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // DELETE
    public function delete()
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
