<?php

use BcMath\Number;

interface UsuarioRepository
{
  public function criar(Usuario $usuario);
  public function listar();
  public function obterPorId(String $id);
  public function obterPorEmail(String $email);
  public function editar(Usuario $usuario);
  public function remover(String $id);
}
