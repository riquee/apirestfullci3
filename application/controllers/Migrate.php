<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Garante que somente a CLI possa acessar este controller
        if (!$this->input->is_cli_request()) {
            exit('Acesso somente via CLI');
        }
        $this->load->library('migration');
    }

    public function index()
    {

        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrações executadas com sucesso!";
        }
    }
}
