<?php
// Hàm tự định nghĩa xử lý nghiệp vụ: Xác định quy mô sự kiện
function xacDinhQuyMo($soNguoi) {
    if ($soNguoi >= 500) {
        return "Quy mô Lớn (Sân vận động/Hội trường lớn)";
    } elseif ($soNguoi >= 100) {
        return "Quy mô Vừa (Phòng hội thảo)";
    } else {
        return "Quy mô Nhỏ (Phòng họp nội bộ)";
    }
}

// Mảng danh sách sự kiện ban đầu
$danhSachSuKien = [
    [
        'ten' => 'Hội thảo Công nghệ AI 2026',
        'so_nguoi' => 300,
        'loai' => 'Hội thảo'
    ],
    [
        'ten' => 'Tiệc tất niên Công ty',
        'so_nguoi' => 80,
        'loai' => 'Sự kiện nội bộ'
    ],
    [
        'ten' => 'Đại nhạc hội Chào tân sinh viên',
        'so_nguoi' => 1500,
        'loai' => 'Giải trí'
    ]
];

// Khởi tạo các biến chứa giá trị form, thông báo lỗi và thông báo thành công
$tenSuKien = '';
$soNguoi = '';
$loaiSuKien = 'Hội thảo';

$errors = [];
$successMessage = '';

// Xử lý khi Form được Submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Chuẩn hóa dữ liệu đầu vào (Trim loại bỏ khoảng trắng dư thừa)
    $tenSuKien = trim($_POST['ten_su_kien'] ?? '');
    $soNguoiInput = trim($_POST['so_nguoi'] ?? '');
    $loaiSuKien = $_POST['loai_su_kien'] ?? 'Hội thảo';

    // 2. Kiểm tra dữ liệu (Validation phía Server)
    
    // Validate Tên sự kiện: Bắt buộc, độ dài từ 5 đến 100 ký tự
    if (empty($tenSuKien)) {
        $errors['ten_su_kien'] = 'Tên sự kiện không được để trống!';
    } elseif (mb_strlen($tenSuKien) < 5 || mb_strlen($tenSuKien) > 100) {
        $errors['ten_su_kien'] = 'Tên sự kiện phải từ 5 đến 100 ký tự!';
    }

    // Validate Số người tham dự: Bắt buộc, phải là số nguyên dương (từ 1 đến 100,000)
    if ($soNguoiInput === '') {
        $errors['so_nguoi'] = 'Vui lòng nhập số người tham dự!';
    } elseif (!filter_var($soNguoiInput, FILTER_VALIDATE_INT) || (int)$soNguoiInput <= 0) {
        $errors['so_nguoi'] = 'Số người phải là một số nguyên dương hợp lệ!';
    } elseif ((int)$soNguoiInput > 100000) {
        $errors['so_nguoi'] = 'Số người tham dự quá lớn (tối đa 100,000 người)!';
    } else {
        $soNguoi = (int)$soNguoiInput;
    }

    // Validate Loại sự kiện
    $dsLoaiHopLe = ['Hội thảo', 'Sự kiện nội bộ', 'Giải trí', 'Khác'];
    if (!in_array($loaiSuKien, $dsLoaiHopLe)) {
        $errors['loai_su_kien'] = 'Loại sự kiện không hợp lệ!';
    }

    // 3. Nếu dữ liệu hợp lệ: Thêm vào danh sách và báo thành công
    if (empty($errors)) {
        $danhSachSuKien[] = [
            'ten' => $tenSuKien,
            'so_nguoi' => $soNguoi,
            'loai' => $loaiSuKien
        ];

        $successMessage = "Thêm sự kiện thành công!";

        // Reset lại dữ liệu form sau khi thêm thành công
        $tenSuKien = '';
        $soNguoi = '';
        $loaiSuKien = 'Hội thảo';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sự Kiện - Bài Tập Lớn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; display: block; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        button { background: #3498db; color: #fff; border: none; padding: 11px 20px; font-size: 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #2980b9; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container">

    <!-- Form nhập thông tin sự kiện -->
    <div class="card">
        <h2>Thêm Sự Kiện Mới</h2>

        <?php if (!empty($successMessage)): ?>
            <div class="alert-success">
                <!-- Mã hóa XSS trước khi in -->
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <!-- Tên sự kiện -->
            <div class="form-group">
                <label for="ten_su_kien">Tên sự kiện (*):</label>
                <!-- Giữ lại dữ liệu hợp lệ và chống XSS bằng htmlspecialchars -->
                <input type="text" id="ten_su_kien" name="ten_su_kien" value="<?php echo htmlspecialchars($tenSuKien); ?>" placeholder="Nhập tên sự kiện...">
                <?php if (isset($errors['ten_su_kien'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['ten_su_kien']); ?></span>
                <?php endif; ?>
            </div>

            <!-- Số người tham dự -->
            <div class="form-group">
                <label for="so_nguoi">Số người tham dự (*):</label>
                <input type="text" id="so_nguoi" name="so_nguoi" value="<?php echo htmlspecialchars($soNguoi !== '' ? $soNguoi : ($_POST['so_nguoi'] ?? '')); ?>" placeholder="Nhập số người...">
                <?php if (isset($errors['so_nguoi'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['so_nguoi']); ?></span>
                <?php endif; ?>
            </div>

            <!-- Loại sự kiện -->
            <div class="form-group">
                <label for="loai_su_kien">Loại sự kiện:</label>
                <select id="loai_su_kien" name="loai_su_kien">
                    <option value="Hội thảo" <?php echo ($loaiSuKien == 'Hội thảo') ? 'selected' : ''; ?>>Hội thảo</option>
                    <option value="Sự kiện nội bộ" <?php echo ($loaiSuKien == 'Sự kiện nội bộ') ? 'selected' : ''; ?>>Sự kiện nội bộ</option>
                    <option value="Giải trí" <?php echo ($loaiSuKien == 'Giải trí') ? 'selected' : ''; ?>>Giải trí</option>
                    <option value="Khác" <?php echo ($loaiSuKien == 'Khác') ? 'selected' : ''; ?>>Khác</option>
                </select>
                <?php if (isset($errors['loai_su_kien'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['loai_su_kien']); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit">Thêm Sự Kiện</button>
        </form>
    </div>

    <!-- Danh sách sự kiện -->
    <div class="card">
        <h2>Danh Sách Sự Kiện</h2>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Sự Kiện</th>
                    <th>Số Người Tham Dự</th>
                    <th>Loại Sự Kiện</th>
                    <th>Phân Loại Quy Mô</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danhSachSuKien as $index => $sk): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <!-- Chống XSS bằng htmlspecialchars cho tất cả dữ liệu xuất ra bảng -->
                        <td><?php echo htmlspecialchars($sk['ten']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($sk['so_nguoi'])); ?> người</td>
                        <td><?php echo htmlspecialchars($sk['loai']); ?></td>
                        <td><strong><?php echo htmlspecialchars(xacDinhQuyMo($sk['so_nguoi'])); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>