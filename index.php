<?php
require_once 'server/connect.php';
checkLoggedIn();
$alert = "";

if (isset($_POST['login'])) {
    $user = clean($_POST['username'], $conn);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $alert = "Swal.fire('สำเร็จ', 'เข้าสู่ระบบแล้ว', 'success').then(() => { window.location='dashboard.php'; });";
    } else {
        $alert = "Swal.fire('ผิดพลาด', 'Username หรือ Password ไม่ถูกต้อง', 'error');";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="has-background-light" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-4">
                <form method="POST" class="box">
                    <h3 class="title is-4 has-text-centered">เข้าสู่ระบบ</h3>
                    <div class="field">
                        <label class="label">ชื่อผู้ใช้</label>
                        <div class="control">
                            <input class="input" type="text" name="username" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">รหัสผ่าน</label>
                        <div class="control">
                            <input class="input" type="password" name="password" required>
                        </div>
                    </div>
                    <button name="login" class="button is-link is-fullwidth mt-4">เข้าสู่ระบบ</button>
                    <p class="has-text-centered mt-3">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        <?php echo $alert; ?>
    </script>
</body>

</html>