<?php
include("koneksi.php");
class Admin
{
    private $conn;
    public $username;
    private $password;
    #disini juga ada konsep encapsulation yang di mana private password itu di bungkus dan hanya bisa di akses di satu kelas saja yaitu class admin
    #dan tidak bisa di akses dari luar kelas selain admin sekalipun kelas turunan nya
    public function __construct($conn, $username, $password)
    {
        $this->conn = $conn;
        $this->username = $username;
        $this->password = $password;
    }
    public function adminLogin()
    {
        $sql = mysqli_query($this->conn, "SELECT * FROM `admin` WHERE username = '$this->username' ");
        $adminData = mysqli_fetch_assoc($sql);
        if ($adminData) {
            if ($this->password == $adminData['password']) {
                session_start();
                $_SESSION["admin"] = $adminData['username'];
                header("location:index.php");
            } else {
                header("location:login.php?pesan=password salah");
            }
        } else {
            header("location:login.php?pesan=username salah");
        }
    }
}
if (isset($_POST['signin'])) {
    $yourname = $_POST['your_name'];
    $yourpass = $_POST['your_pass'];
    $login = new Admin($conn,$yourname,$yourpass);
    $login -> adminLogin();
}
