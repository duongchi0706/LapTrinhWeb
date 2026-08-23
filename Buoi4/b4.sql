-- 1. Tạo bảng Câu lạc bộ
CREATE TABLE cau_lac_bo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_clb VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tạo bảng Sự kiện
CREATE TABLE su_kien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_su_kien VARCHAR(150) NOT NULL,
    anh_bia VARCHAR(255),
    clb_id INT,
    dia_diem VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    tg_bat_dau DATETIME NOT NULL,
    tg_ket_thuc DATETIME NOT NULL,
    han_dang_ky DATETIME NOT NULL,
    so_luong_toi_da INT NOT NULL,
    trang_thai VARCHAR(50) DEFAULT 'Sắp diễn ra',
    FOREIGN KEY (clb_id) REFERENCES cau_lac_bo(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Thêm dữ liệu mẫu tiếng Việt
INSERT INTO cau_lac_bo (ten_clb) VALUES 
('CLB Truyền thông'),
('CLB Âm nhạc'),
('CLB IT');

INSERT INTO su_kien (ten_su_kien, anh_bia, clb_id, dia_diem, mo_ta, tg_bat_dau, tg_ket_thuc, han_dang_ky, so_luong_toi_da, trang_thai) VALUES 
('Chào tân sinh viên 2026', 'avatar1.jpg', 1, 'Hội trường A', 'Sự kiện chào đón các bạn tân sinh viên khóa mới.', '2026-09-01 08:00:00', '2026-09-01 11:30:00', '2026-08-30 23:59:00', 500, 'Sắp diễn ra'),
('Cuộc thi Lập trình Hackathon', 'avatar2.jpg', 3, 'Phòng máy C5', 'Cuộc thi lập trình 24h dành cho sinh viên IT.', '2026-10-15 07:00:00', '2026-10-16 07:00:00', '2026-10-10 23:59:00', 100, 'Sắp diễn ra'),
('Đêm nhạc Mùa Thu', 'avatar3.jpg', 2, 'Sân trường', 'Chương trình giao lưu âm nhạc mùa thu.', '2026-05-20 19:00:00', '2026-05-20 22:00:00', '2026-05-18 23:59:00', 300, 'Đã kết thúc');



-- Câu 1: Lấy danh sách tất cả sự kiện kèm tên Câu lạc bộ (dùng JOIN)
SELECT 
    sk.id, 
    sk.ten_su_kien, 
    clb.ten_clb, 
    sk.dia_diem, 
    sk.so_luong_toi_da, 
    sk.trang_thai 
FROM su_kien sk
INNER JOIN cau_lac_bo clb ON sk.clb_id = clb.id;

-- Câu 2: Lọc các sự kiện có quy mô từ 200 người trở lên và đang 'Sắp diễn ra' (dùng WHERE)
SELECT ten_su_kien, dia_diem, so_luong_toi_da, trang_thai 
FROM su_kien 
WHERE so_luong_toi_da >= 200 AND trang_thai = 'Sắp diễn ra';

-- Câu 3: Tìm kiếm sự kiện do 'CLB IT' tổ chức (dùng JOIN kết hợp WHERE)
SELECT 
    sk.ten_su_kien, 
    clb.ten_clb, 
    sk.tg_bat_dau, 
    sk.dia_diem 
FROM su_kien sk
JOIN cau_lac_bo clb ON sk.clb_id = clb.id
WHERE clb.ten_clb = 'CLB IT';