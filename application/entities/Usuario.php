<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario implements JsonSerializable
{
  private String $id;
  private String $nome;
  private String $email;
  private String $senha;

  public function __construct($id, $nome, $email, $senha)
  {
    $this->id = $id;
    $this->nome  = $nome;
    $this->email = $email;
    $this->senha = $senha;
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'nome' => $this->nome,
      'email' => $this->email,
    ];
  }

  public function getId()
  {
    return $this->id;
  }
  public function setId(String $id)
  {
    $this->id = $id;
  }
  public function getNome()
  {
    return $this->nome;
  }
  public function setNome(String $nome)
  {
    $this->nome = $nome;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function setEmail(String $email)
  {
    $this->email = $email;
  }
  public function getSenha()
  {
    return $this->senha;
  }
  public function validarSenha()
  {
    if (strlen($this->senha) < 8) {
      return [
        'status' => false,
        'error' => 'Senha deve conter no mínimo 8 caracteres.'
      ];
    }

    if (!preg_match('/[A-Z]/', $this->senha)) {
      return [
        'status' => false,
        'error' => 'Senha deve pelo menos uma letra maiúscula.'
      ];
    }

    if (!preg_match('/[\W]/', $this->senha)) {
      return [
        'status' => false,
        'error' => 'Senha deve pelo menos um caractere especial.'
      ];
    }

    return [
      'status' => true,
    ];
  }
}
