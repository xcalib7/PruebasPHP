 <?php
        header("Content-Type: application/json");

        require_once('Conexion.php');
        require_once('Contactos.php');

        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $contactos = getContactos();
                echo json_encode($contactos);
                break;

            case 'POST':

                $data=json_decode(file_get_contents('php://input'), true);

                $resultado=insertarContacto($data['nombre'], $data['apellido'], $data['dni']);

                echo json_encode($resultado);

                break;

            case 'PUT':

                $data=json_decode(file_get_contents('php://input'), true);

                $resultado=actualizarContacto($data['nombre'], $data['apellido'], $data['dni'], $data['id']);

                echo json_encode($resultado);

                break;

            case 'DELETE':

                $data=json_decode(file_get_contents('php://input'), true);

                $resultado=borrarContacto($data['id']);

                echo json_encode($resultado);

                break;

            default:
                echo 'Error malo, malisimo';
                break;
        }




        ?> 
