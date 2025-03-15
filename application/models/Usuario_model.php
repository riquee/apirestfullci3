<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'entities/Usuario.php');
require_once(APPPATH . 'repositories/UsuarioRepository.php');

class Usuario_model extends CI_Model implements UsuarioRepository
{
    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function criar(Usuario $usuario)
    {
        $data = [
            'id' =>  $usuario->getId(),
            'name'  => $usuario->getNome(),
            'email' => $usuario->getEmail(),
            'password' => password_hash($usuario->getSenha(), PASSWORD_DEFAULT)
        ];

        $this->db->insert($this->table, $data);

        return $this->db->affected_rows();
    }

    public function listar()
    {
        $query = $this->db->get($this->table);
        $users = $query->result();
        return  array_map(fn($user) => new Usuario(
            $user->id,
            $user->name,
            $user->email,
            $user->password
        ), $users);
    }

    public function obterPorId($id)
    {
        $query = $this->db->get_where($this->table, ['id' => $id]);
        $row = $query->row();
        if (!$row) return;
        return new Usuario($row->id, $row->name, $row->email, $row->password);
    }

    public function obterPorEmail($email)
    {
        $query = $this->db->get_where($this->table, ['email' => $email]);
        $row = $query->row();
        if (!$row) return;
        return new Usuario($row->id, $row->name, $row->email, $row->password);
    }

    public function editar(Usuario $usuario)
    {
        $data = [
            'name' => $usuario->getNome()
        ];
        $this->db->where('id', $usuario->getId());
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function remover($id)
    {
        $this->db->delete($this->table, ['id' => $id]);
        return $this->db->affected_rows();
    }
}
