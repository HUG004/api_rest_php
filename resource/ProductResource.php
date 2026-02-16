<?php

require_once '../../config/database.php';
require_once '../../models/Product.php';

class ProductResource
{
    private $product;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->product = new Product($db);
    }

    public function index()
    {
        header("Content-Type: application/json");

        $stmt = $this->product->read();
        $records = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $records[] = $row;
        }

        echo json_encode(["records" => $records]);
    }

    public function show($id)
    {
        header("Content-Type: application/json");

        $this->product->id = $id;

        if ($this->product->readOne()) {
            echo json_encode($this->product);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Producto no encontrado"]);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"));

        $this->product->sku = $data->sku;
        $this->product->name = $data->name;
        $this->product->description = $data->description;
        $this->product->price = $data->price;
        $this->product->stock = $data->stock;

        if ($this->product->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Producto creado"]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Error al crear producto"]);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"));

        $this->product->id = $id;
        $this->product->sku = $data->sku;
        $this->product->name = $data->name;
        $this->product->description = $data->description;
        $this->product->price = $data->price;
        $this->product->stock = $data->stock;

        if ($this->product->update()) {
            echo json_encode(["message" => "Producto actualizado"]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Error al actualizar"]);
        }
    }

    public function destroy($id)
    {
        $this->product->id = $id;

        if ($this->product->delete()) {
            echo json_encode(["message" => "Producto eliminado"]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Error al eliminar"]);
        }
    }
}
?>
