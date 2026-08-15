<?php
// 1. Hàm tự định nghĩa xử lý nghiệp vụ: 
// Xác định quy mô sự kiện dựa trên số lượng người tham dự dự kiến
function xacDinhQuyMo($soNguoi) {
    if ($soNguoi >= 500) {
        return "Quy mô Lớn (Cần hội trường/Sân vận động)";
    } elseif ($soNguoi >= 100) {
        return "Quy mô Vừa (Cần phòng hội thảo)";
    } else {
        return "Quy mô Nhỏ (Phòng họp/Nội bộ)";
    }
}

// 2. Tổ chức dữ liệu ban đầu bằng mảng danh sách sự kiện
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

// 3. Tiếp nhận và xử lý dữ liệu nhập từ Form (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenSuKien = trim($_POST['ten_su_kien']);
    $soNguoi = (int)$_POST['so_nguoi'];
    $loaiSuKien = $_POST['loai_su_kien'];

    // Kiểm tra tính hợp lệ dữ liệu nhập
    if (!empty($tenSuKien) && $soNguoi > 0) {
        // Thêm sự kiện mới vào mảng
        $danhSachSuKien[] = [
            'ten' => $tenSuKien,
            'so_nguoi' => $soNguoi,
            'loai' => $loaiSuKien
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sự kiện - Buổi 2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .form-container { background: #f8f9fa; padding: 20px; border: 1px solid #ddd; border-radius: 8px; width: 450px; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #0d6efd; color: #fff; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #0b5ed7; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #dee2e6; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #212529; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>NHẬP THÔNG TIN SỰ KIỆN MỚI</h2>
    
    <!-- Form nhập thông tin đối tượng sự kiện (3 trường dữ liệu) -->
    <div class="form-container">
        <form action="" method="POST">
            <div class="form-group">
                <label for="ten_su_kien">Tên sự kiện:</label>
                <input type="text" id="ten_su_kien" name="ten_su_kien" required placeholder="Nhập tên sự kiện...">
            </div>

            <div class="form-group">
                <label for="so_nguoi">Số lượng khách mời dự kiến:</label>
                <input type="number" id="so_nguoi" name="so_nguoi" min="1" required placeholder="Nhập số người...">
            </div>

            <div class="form-group">
                <label for="loai_su_kien">Loại sự kiện:</label>
                <select id="loai_su_kien" name="loai_su_kien">
                    <option value="Hội thảo">Hội thảo / Conference</option>
                    <option value="Giải trí">Giải trí / Bán vé</option>
                    <option value="Sự kiện nội bộ">Sự kiện nội bộ / Tiệc</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>

            <button type="submit">Thêm sự kiện</button>
        </form>
    </div>

    <h2>DANH SÁCH SỰ KIỆN ĐÃ ĐĂNG KÝ</h2>
    
    <!-- Hiển thị dữ liệu dưới dạng Bảng -->
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sự kiện</th>
                <th>Số người dự kiến</th>
                <th>Loại sự kiện</th>
                <th>Phân loại quy mô (Nghiệp vụ)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // 4. Dùng vòng lặp foreach duyệt mảng và hiển thị
            $stt = 1;
            foreach ($danhSachSuKien as $suKien): 
                // Gọi hàm tự định nghĩa kiểm tra quy mô sự kiện
                $quyMo = xacDinhQuyMo($suKien['so_nguoi']);
            ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo htmlspecialchars($suKien['ten']); ?></td>
                    <td><?php echo number_format($suKien['so_nguoi']); ?> người</td>
                    <td><?php echo $suKien['loai']; ?></td>
                    <td><strong><?php echo $quyMo; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>