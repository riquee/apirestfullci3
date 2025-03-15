<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'entities/Usuario.php');
require_once(APPPATH . 'repositories/UsuarioRepository.php');
require_once(APPPATH . 'libraries/UsuarioService.php');

class Usuarios extends CI_Controller
{

    private $usuarioService;

    public function __construct()
    {
        parent::__construct();

        header("Content-Type: application/json");

        self::auth();

        $json = $this->input->raw_input_stream;
        $data = json_decode($json, true);

        if (!empty($data)) {
            $_POST = $data;
        }

        $this->load->model('Usuario_model');
        $this->load->library('form_validation');
        $this->usuarioService = new UsuarioService($this->Usuario_model);
    }

    private function auth()
    {

        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            $this->send_unauthorized();
        } else {
            $authHeader = $headers['Authorization'];
            if (strpos($authHeader, 'Basic ') !== 0) {
                $this->send_unauthorized();
            }

            $encodedCredentials = substr($authHeader, 6);
            $decodedCredentials = base64_decode($encodedCredentials);
            list($username, $password) = explode(':', $decodedCredentials, 2);

            if (!$this->check_credentials($username, $password)) {
                $this->send_unauthorized();
            }
        }
    }

    private function check_credentials($username, $password)
    {
        $validUser = 'usuario';
        $validPass = 'senha';

        return ($username === $validUser && $password === $validPass);
    }

    private function send_unauthorized()
    {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm="API"');
        echo json_encode(array('error' => 'Acesso não autorizado'));
        exit;
    }

    public function index()
    {

        switch ($this->input->method()) {
            case 'get':
                return self::list();
                break;
            case 'post':
                return self::create();
                break;

            default:
                echo json_encode(['status' => 'Method not allowed']);
                break;
        }
    }

    public function list()
    {
        $users = $this->usuarioService->listarUsuarios();
        echo json_encode($users);
    }

    public function create()
    {
        $allowed_fields = ['nome', 'email', 'senha'];
        $data = [];

        foreach ($allowed_fields as $field) {
            $data[$field] = $this->input->post($field, TRUE);
        }

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules(
            'nome',
            'nome',
            'required|min_length[3]|max_length[100]',
            array(
                'required' => 'O campo "%s" é obrigatório.',
                'min_length' => 'O campo "%s" deve conter no mínimo 3 caracteres',
                'max_length' => 'O campo "%s" deve conter no máximo 100 caracteres',
            )
        );
        $this->form_validation->set_rules(
            'email',
            'email',
            'required|valid_email',
            array(
                'required' => 'O campo "%s" é obrigatório.',
                'valid_email' => 'O campo "%s" é inválido.',
            )
        );
        $this->form_validation->set_rules(
            'senha',
            'senha',
            'required|min_length[8]|max_length[50]',
            array(
                'required' => 'O campo "%s" é obrigatório.',
                'min_length' => 'O campo "%s" deve conter no mínimo 8 caracteres',
                'max_length' => 'O campo "%s" deve conter no máximo 50 caracteres',
            )
        );

        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => false,
                'errors' => strip_tags(validation_errors()),
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode($response));
        }


        $result = $this->usuarioService->criarUsuario(
            $this->input->post('nome'),
            $this->input->post('email'),
            $this->input->post('senha')
        );

        if ($result['status'] === false) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }

        echo json_encode($result['data']);
    }

    public function show($id)
    {
        if ($this->input->method() === 'get') {
            $user = $this->usuarioService->obterUsuario($id);
            echo json_encode($user);
        } else {
            echo json_encode(['status' => 'Method not allowed']);
        }
    }


    public function update($id)
    {
        if ($this->input->method() === 'put') {
            $allowed_fields = ['nome'];
            $data = [];

            foreach ($allowed_fields as $field) {
                $data[$field] = $this->input->post($field, TRUE);
            }

            $this->form_validation->set_data($data);

            $this->form_validation->set_rules(
                'nome',
                'nome',
                'min_length[3]|max_length[100]',
                array(
                    'min_length' => 'O campo "%s" deve conter no mínimo 3 caracteres',
                    'max_length' => 'O campo "%s" deve conter no máximo 100 caracteres',
                )
            );

            if ($this->form_validation->run() == FALSE) {
                $response = array(
                    'status' => false,
                    'errors' => strip_tags(validation_errors()),
                );
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode($response));
            }

            $result = $this->usuarioService->editarUsuario(
                $id,
                $this->input->post('nome'),
            );

            if ($result['status'] === false) {
                return $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }

            echo json_encode($result['data']);
        } else {
            echo json_encode(['status' => 'Method not allowed']);
        }
    }

    public function delete($id)
    {
        if ($this->input->method() === 'delete') {
            $result = $this->usuarioService->removerUsuario($id);

            if ($result['status'] === false) {
                return $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }

            echo json_encode($result['data']);
        } else {
            echo json_encode(['status' => 'Method not allowed']);
        }
    }
}
