<?php
require_once __DIR__ . '/../config/database.php';
class Cliente {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll($search='') {
        if (!empty($search)) {
            $like = '%' . $search . '%';
            $s = $this->db->prepare(
                "SELECT * FROM clientes WHERE nombre LIKE ? OR nit LIKE ? OR telefono LIKE ? OR email LIKE ? ORDER BY nombre"
            );
            $s->bind_param("ssss", $like, $like, $like, $like);
            $s->execute();
            $r = $s->get_result();
        } else {
            $r = $this->db->query("SELECT * FROM clientes ORDER BY nombre");
        }
        $a = []; while($row = $r->fetch_assoc()) $a[] = $row; return $a;
    }
    public function getById($id) {
        $s=$this->db->prepare("SELECT * FROM clientes WHERE id=?");
        $s->bind_param("i",$id);$s->execute();return $s->get_result()->fetch_assoc();
    }
    public function create($n,$nit='',$t='',$e='',$d='') {
        $s=$this->db->prepare("INSERT INTO clientes(nombre,nit,telefono,email,direccion)VALUES(?,?,?,?,?)");
        $s->bind_param("sssss",$n,$nit,$t,$e,$d);return $s->execute();
    }
    public function update($id,$n,$nit='',$t='',$e='',$d='') {
        $s=$this->db->prepare("UPDATE clientes SET nombre=?,nit=?,telefono=?,email=?,direccion=? WHERE id=?");
        $s->bind_param("sssssi",$n,$nit,$t,$e,$d,$id);return $s->execute();
    }
    public function delete($id) {
        $s=$this->db->prepare("DELETE FROM clientes WHERE id=?");
        $s->bind_param("i",$id);return $s->execute();
    }
    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as t FROM clientes")->fetch_assoc()['t'];
    }
}
?>
