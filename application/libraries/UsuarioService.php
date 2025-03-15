<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsuarioService
{

  private $usuarioRepository;

  public function __construct($usuarioRepository)
  {
    $this->usuarioRepository = $usuarioRepository;
  }

  public function criarUsuario($nome, $email, $senha)
  {
    $uuid = self::generate_uuid_v4();
    $usuario = new Usuario($uuid, $nome, $email, $senha);

    if (!$usuario->validarSenha()['status']) return $usuario->validarSenha();

    $usuarioEmail = $this->usuarioRepository->obterPorEmail($email);

    if ($usuarioEmail) return ['status' => false, 'error' => 'Este e-mail já está associado a um usuário.'];

    $result =  $this->usuarioRepository->criar($usuario);

    if (!$result)  return ['status' => false, 'error' => 'Não foi possível criar o usuário.'];

    return ['status' => true, 'data' => $usuario];
  }

  public function listarUsuarios()
  {
    return $this->usuarioRepository->listar();
  }

  public function obterUsuario($id)
  {
    $usuario = $this->usuarioRepository->obterPorId($id);
    return $usuario;
  }

  public function editarUsuario($id, $nome)
  {
    $usuario = $this->usuarioRepository->obterPorId($id);

    if (!$usuario) return ['status' => false, 'error' => 'Usuário inexistente.'];

    $usuario->setNome($nome);

    $usuarioUpdate = $this->usuarioRepository->editar($usuario);

    if (!$usuarioUpdate)  return ['status' => false, 'error' => 'Não foi possível editar o usuário.'];

    return ['status' => true, 'data' => $usuario];
  }

  public function removerUsuario($id)
  {
    $usuario = $this->usuarioRepository->obterPorId($id);

    if (!$usuario) return ['status' => false, 'error' => 'Usuário inexistente.'];

    $result = $this->usuarioRepository->remover($id);

    if (!$result)  return ['status' => false, 'error' => 'Não foi possível deletar o usuário.'];

    return ['status' => true, 'data' => ['id' => $id]];
  }

  function generate_uuid_v4()
  {
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }
}
