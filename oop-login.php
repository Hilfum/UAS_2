<?php
include("koneksi.php");

class User
{
    protected $password;
    #sedangkan untuk protected password ini hanya bisa di akses oleh kelas user dan turunan dari user
    #tetapi selain dari kelas induk dan turunannya tidak ada yang bisa akses
    public $username;
    public $fullname;
    public function __construct($username,$password, $fullname)
    {
        $this->username = $username;
        $this->password = $password;
        $this->fullname = $fullname;
    }
}

class UserManager extends User
{
    protected $conn;
    public function __construct($conn, $password, $username, $fullname)
    {
        parent::__construct($password, $username, $fullname);
        $this->conn = $conn;
    }

    public function createUser()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $queri = mysqli_query($this->conn, "INSERT INTO tb_user (fullname, username, password) VALUES ('$this->fullname','$this->username','$hashedPassword')");
        return $queri;
    }
}
class Login extends User
#class di atas ini merupakan konsep dari inheritage, yang dimana class login
#di turunkan dari class user begitupun dengan class user manager dengan atribut tambahan berupa protected conn
{
    protected $conn;
    public function __construct($conn, $username, $password)
    {
        parent::__construct($username, $password, '');
        $this->conn = $conn;
    }

    public function loginUser()
    {
        $query = mysqli_query($this->conn,"SELECT * FROM tb_user WHERE username = '$this->username'");
        $userData = mysqli_fetch_assoc($query);
        if ($userData) {
            if (password_verify($this->password, $userData["password"])) {
                session_start();
                $_SESSION["username"] = $this->username;
                header("location:tiket/index.php");
            } else {
                header("location:login.php?pesan=password salah");
            }
        } else {
            header("location:login.php?pesan=username salah");
        }
    }
}
