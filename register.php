<?php
require_once 'server/connect.php';
checkLoggedIn();
$alert = "";

if (isset($_POST['register'])) {
    $user = clean($_POST['username'], $conn);
    $email = clean($_POST['email'], $conn);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // เช็คค่าว่าง
    if (empty($user) || empty($email) || empty($pass)) {
        $alert = "Swal.fire('ผิดพลาด', 'กรุณากรอกข้อมูลให้ครบ', 'error');";
    }
    // เช็คความยาว
    elseif (!checkLength($user, 6, 20) || !checkLength($pass, 6, 20)) {
        $alert = "Swal.fire('คำเตือน', 'Username/Password ต้องมี 6-20 ตัวอักษร', 'warning');";
    }
    // เช็ครูปแบบอีเมล
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = "Swal.fire('ผิดพลาด', 'รูปแบบอีเมลไม่ถูกต้อง', 'error');";
    }
    // เช็ครหัสผ่านตรงกัน
    elseif ($pass !== $confirm_pass) {
        $alert = "Swal.fire('ผิดพลาด', 'รหัสผ่านไม่ตรงกัน', 'error');";
    } else {
        // เช็ค Username/Email ซ้ำ
        $check_query = "SELECT * FROM users WHERE username = '$user' OR email = '$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);
        $existing_user = mysqli_fetch_assoc($check_result);

        if ($existing_user) {
            if ($existing_user['username'] === $user) {
                $alert = "Swal.fire('ผิดพลาด', 'Username นี้ถูกใช้ไปแล้ว', 'error');";
            } else {
                $alert = "Swal.fire('ผิดพลาด', 'Email นี้ถูกใช้ไปแล้ว', 'error');";
            }
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$hashed_pass')";
            if (mysqli_query($conn, $sql)) {
                $alert = "Swal.fire('สำเร็จ', 'สมัครสมาชิกเรียบร้อยแล้ว', 'success').then(()=> {window.location='index.php';});";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="has-background-light" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-4">
                <form method="POST" class="box">
                    <h3 class="title is-4 has-text-centered">สมัครสมาชิก</h3>
                    <div class="field">
                        <label class="label">ชื่อผู้ใช้ (6-20 ตัวอักษร)</label>
                        <input class="input" type="text" name="username" required>
                    </div>
                    <div class="field">
                        <label class="label">อีเมล</label>
                        <input class="input" type="email" name="email" required>
                    </div>
                    <div class="field">
                        <label class="label">รหัสผ่าน (6-20 ตัวอักษร)</label>
                        <input class="input" type="password" name="password" required>
                    </div>
                    <div class="field">
                        <label class="label">ยืนยันรหัสผ่าน</label>
                        <input class="input" type="password" name="confirm_password" required>
                    </div>
                    <button name="register" class="button is-primary is-fullwidth mt-4">ลงทะเบียน</button>
                    <p class="has-text-centered mt-3"><a href="index.php">กลับไปหน้าเข้าสู่ระบบ</a></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        <?php echo $alert; ?>
    </script>
</body>

</html>