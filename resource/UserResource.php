<?php

require_once '../../config/database.php';
require_once '../../models/User.php';

class UserResource
{
    private $user;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->user = new User($db);
    }

    // GET /api/v1/users
    public function index()
    {
        header("Content-Type: application/json");

        $stmt = $this->user->read();
        $records = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $records[] = $row;
        }

        http_response_code(200);
        echo json_encode(["records" => $records]);
    }

    // GET /api/v1/users/{id}
    public function show($id)
    {
        header("Content-Type: application/json");

        $this->user->id = $id;

        if ($this->user->readOne()) {
            echo json_encode([
                "id" => $this->user->id,
                "name" => $this->user->name,
                "email" => $this->user->email,
                "created_at" => $this->user->created_at
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Usuario no encontrado"]);
        }
    }

    // POST /api/v1/users
    public function store()
    {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name) && !empty($data->email)) {

            $this->user->name = $data->name;
            $this->user->email = $data->email;

            if ($this->user->create()) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Usuario creado exitosamente",
                    "id" => $this->user->id
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Error al crear usuario"]);
            }

        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }

    // PUT /api/v1/users/{id}
    public function update($id)
    {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name) && !empty($data->email)) {

            $this->user->id = $id;
            $this->user->name = $data->name;
            $this->user->email = $data->email;

            if ($this->user->update()) {
                http_response_code(200);
                echo json_encode(["message" => "Usuario actualizado"]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Error al actualizar"]);
            }

        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }

    // DELETE /api/v1/users/{id}
    public function destroy($id)
    {
        header("Content-Type: application/json");

        $this->user->id = $id;

        if ($this->user->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario eliminado"]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Error al eliminar"]);
        }
    }
}
?>
