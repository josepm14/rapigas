<?php
require_once "../conexion.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Listar todos los usuarios
        $stmt = $pdo->query("SELECT * FROM usuarios");
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        // Insertar nuevo usuario
        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $pdo->prepare("INSERT INTO usuarios
            (nombre, apellido, dni, direccion, telefono, cargo, responsabilidad, usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['dni'],
            $data['direccion'],
            $data['telefono'],
            $data['cargo'],
            $data['responsabilidad'],
            $data['usuario']
        ]);
        echo json_encode(["status" => "success"]);
        break;

    case 'PUT':
        // Actualizar usuario
        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $pdo->prepare("UPDATE usuarios 
            SET nombre=?, apellido=?, dni=?, direccion=?, telefono=?, cargo=?, responsabilidad=?, usuario=? 
            WHERE id=?");
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['dni'],
            $data['direccion'],
            $data['telefono'],
            $data['cargo'],
            $data['responsabilidad'],
            $data['usuario'],
            $data['id']
        ]);
        echo json_encode(["status" => "updated"]);
        break;

    case 'DELETE':
        // Eliminar usuario
        parse_str(file_get_contents("php://input"), $data);
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->execute([$data['id']]);
        echo json_encode(["status" => "deleted"]);
        break;
}
