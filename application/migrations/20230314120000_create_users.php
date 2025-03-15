<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Users extends CI_Migration
{

  public function up()
  {
    $this->db->query("
            CREATE TABLE IF NOT EXISTS `users` (
                `id` VARCHAR(36) NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL UNIQUE,
                `password` VARCHAR(100) NOT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
            ENGINE = InnoDB DEFAULT CHARSET = utf8;
        ");
  }

  public function down()
  {
    $this->db->query("DROP TABLE IF EXISTS `users`");
  }
}
