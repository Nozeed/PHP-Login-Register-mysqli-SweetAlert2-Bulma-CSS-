<?php
require_once 'server/connect.php';
checkNotLoggedIn();

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT id, username, email, credit, role, created_at FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แดชบอร์ด</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<body>
    <nav class="navbar is-dark">
        <div class="container">
            <div class="navbar-brand"><a class="navbar-item">MY SYSTEM</a></div>
            <div class="navbar-end">
                <div class="navbar-item has-text-white">
                    ยินดีต้อนรับ: <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>
                    <a href="logout.php" class="button is-danger is-small ml-3">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </nav>
    <section class="section">
        <div class="container">
            <h1 class="title">ข้อมูลสมาชิกที่ล็อกอิน</h1>
            <div class="table-container">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>อีเมล</th>
                            <th>เครดิต</th>
                            <th>สถานะ</th>
                            <th>สมัครเมื่อ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo (int) $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><b class="has-text-success"><?php echo number_format((float) $user['credit']); ?></b></td>
                            <td>
                                <span class="tag <?php echo $user['role'] === 'admin' ? 'is-danger' : 'is-info'; ?>">
                                    <?php echo htmlspecialchars(strtoupper($user['role']), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>