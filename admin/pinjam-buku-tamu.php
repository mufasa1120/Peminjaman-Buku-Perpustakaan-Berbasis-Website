<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pinjam Buku - Tamu</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --primary-color: #2c3968;
                --secondary-color: #3d4b7a;
                --accent-color: #f4c430;
                --light-bg: #f8f9fc;
                --card-shadow: 0 8px 32px rgba(44, 57, 104, 0.12);
                --text-primary: #2c3968;
                --text-secondary: #6c757d;
                --gradient-primary: linear-gradient(135deg, #2c3968 0%, #3d4b7a 100%);
                --gradient-accent: linear-gradient(135deg, #f4c430 0%, #f39c12 100%);
                --success-color: #28a745;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background: var(--gradient-primary);
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
                color: var(--text-primary);
            }

            /* Animated Background */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: 
                    radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
                z-index: -1;
                animation: backgroundShift 20s ease-in-out infinite;
            }

            @keyframes backgroundShift {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.8; }
            }

            /* Floating Elements */
            .floating-element {
                position: absolute;
                opacity: 0.1;
                animation: float 6s ease-in-out infinite;
            }

            .floating-element:nth-child(1) {
                top: 10%;
                left: 10%;
                animation-delay: 0s;
            }

            .floating-element:nth-child(2) {
                top: 20%;
                right: 10%;
                animation-delay: 2s;
            }

            .floating-element:nth-child(3) {
                bottom: 10%;
                left: 20%;
                animation-delay: 4s;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }

            .container {
                position: relative;
                z-index: 10;
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            /* Back Button */
            .back-btn {
                position: absolute;
                top: 1rem;
                left: 1rem;
                background: var(--gradient-primary);
                border: none;
                color: white;
                padding: 10px 20px;
                border-radius: 12px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                text-decoration: none;
                box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
                z-index: 20;
            }

            .back-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 25px rgba(44, 57, 104, 0.4);
                color: white;
            }

            /* Main Card */
            .main-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: none;
                border-radius: 16px;
                box-shadow: var(--card-shadow);
                padding: 3rem;
                margin: 2rem auto;
                max-width: 600px;
                position: relative;
                overflow: visible; /* Changed from hidden to visible */
            }

            .main-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: var(--gradient-primary);
                border-radius: 16px 16px 0 0;
            }

            /* Header */
            .form-header {
                text-align: center;
                margin-bottom: 2.5rem;
            }

            .form-title {
                color: var(--primary-color);
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .form-subtitle {
                color: var(--text-secondary);
                font-size: 1rem;
                font-weight: 400;
            }

            /* Form Groups */
            .form-group {
                margin-bottom: 1.5rem;
                position: relative;
            }

            /* Special styling for book search form group */
            .form-group.book-search-group {
                margin-bottom: 2.5rem; /* Increased bottom margin */
                z-index: 1000; /* High z-index for the entire group */
            }

            .form-label {
                display: block;
                color: var(--text-primary);
                font-size: 0.875rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .form-label i {
                color: var(--primary-color);
                font-size: 1rem;
            }

            /* Input Styles */
            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                background: white;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--accent-color);
                box-shadow: 0 0 0 0.25rem rgba(244, 196, 48, 0.25);
                transform: translateY(-1px);
            }

            .form-control::placeholder {
                color: var(--text-secondary);
                opacity: 0.7;
            }

            /* Textarea */
            textarea.form-control {
                min-height: 100px;
                resize: vertical;
            }

            /* Date Input */
            input[type="date"] {
                appearance: none;
                -webkit-appearance: none;
            }

            /* Search Results - FIXED Z-INDEX */
            #bookResults {
                position: absolute;
                z-index: 99999 !important; /* Extremely high z-index */
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
                background: white !important;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 10px 20px rgba(0, 0, 0, 0.1);
                margin-top: 4px;
                top: 100%; /* Position below the input */
                left: 0;
            }

            .list-group {
                background: white !important;
                border-radius: 12px;
                overflow: hidden;
                margin: 0;
                padding: 0;
                position: relative;
                z-index: 99999 !important; /* Extremely high z-index */
            }

            .list-group-item {
                border: none !important;
                padding: 1rem 1.25rem;
                cursor: pointer;
                transition: all 0.2s ease;
                background: white !important;
                color: var(--text-primary) !important;
                border-bottom: 1px solid #e5e7eb !important;
                font-weight: 500;
                font-size: 0.9rem;
                position: relative;
                z-index: 99999 !important; /* Extremely high z-index */
            }

            .list-group-item:hover {
                background: var(--primary-color) !important;
                color: white !important;
                transform: translateX(4px);
            }

            .list-group-item:last-child {
                border-bottom: none !important;
            }

            .list-group-item:first-child {
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
            }

            .list-group-item:last-child {
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            /* Submit Button */
            .submit-button {
                background: var(--gradient-primary);
                border: none;
                color: white;
                padding: 1rem 2rem;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-left: auto;
                box-shadow: 0 4px 15px rgba(44, 57, 104, 0.2);
            }

            .submit-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(44, 57, 104, 0.3);
                background: var(--gradient-accent);
            }

            .submit-button:active {
                transform: translateY(0);
            }

            /* Success Alert */
            .alert {
                border: none;
                border-radius: 12px;
                padding: 1rem 1.5rem;
                margin-bottom: 2rem;
                background: linear-gradient(135deg, var(--success-color), #218838);
                color: white;
                font-weight: 500;
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
                display: flex;
                align-items: center;
                gap: 0.75rem;
                animation: slideIn 0.5s ease-out;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .alert i {
                font-size: 1.25rem;
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .main-card {
                    margin: 1rem;
                    padding: 2rem 1.5rem;
                }

                .form-title {
                    font-size: 1.5rem;
                }

                .form-subtitle {
                    font-size: 0.9rem;
                }

                .back-btn {
                    top: 0.5rem;
                    left: 0.5rem;
                    padding: 8px 16px;
                    font-size: 0.9rem;
                }
            }

            /* Loading Animation */
            .loading {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top-color: white;
                animation: spin 1s ease-in-out infinite;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            /* Form Animation */
            .form-group {
                animation: fadeInUp 0.6s ease-out;
                animation-fill-mode: both;
            }

            .form-group:nth-child(1) { animation-delay: 0.1s; }
            .form-group:nth-child(2) { animation-delay: 0.2s; }
            .form-group:nth-child(3) { animation-delay: 0.3s; }
            .form-group:nth-child(4) { animation-delay: 0.4s; }
            .form-group:nth-child(5) { animation-delay: 0.5s; }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body>
        <!-- Floating Background Elements -->
        <div class="floating-element">
            <i class="bi bi-book" style="font-size: 3rem; color: rgba(255, 255, 255, 0.3);"></i>
        </div>
        <div class="floating-element">
            <i class="bi bi-journal-bookmark" style="font-size: 2.5rem; color: rgba(255, 255, 255, 0.3);"></i>
        </div>
        <div class="floating-element">
            <i class="bi bi-person-check" style="font-size: 2rem; color: rgba(255, 255, 255, 0.3);"></i>
        </div>

        <!-- Back Button -->
        <a href="dashboard-admin.php" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>

        <div class="container">
            <div class="main-card">
                <!-- Header -->
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="bi bi-book-half"></i>
                        Peminjaman Buku Tamu
                    </h1>
                    <p class="form-subtitle">Formulir khusus untuk peminjaman buku tamu</p>
                </div>

                <!-- Alert Sukses -->
                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Data berhasil disimpan! Terima kasih telah menggunakan layanan kami.</span>
                    </div>
                <?php endif; ?>

                <!-- Form Input -->
                <form id="borrowForm" action="process-borrow.php" method="POST">
                    <div class="form-group">
                        <label for="nama" class="form-label">
                            <i class="bi bi-person"></i>
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="nama" 
                            name="nama" 
                            placeholder="Masukkan nama lengkap Tamu" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="notelpon" class="form-label">
                            <i class="bi bi-telephone"></i>
                            Nomor Telepon
                        </label>
                        <input 
                            type="tel" 
                            class="form-control" 
                            id="notelpon" 
                            name="notelpon" 
                            placeholder="Contoh: 08123456789" 
                            required
                        >
                    </div>

                    <div class="form-group book-search-group">
                        <label for="bookSearch" class="form-label">
                            <i class="bi bi-search"></i>
                            Pilih Buku
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="bookSearch" 
                            placeholder="Ketik untuk mencari judul buku..." 
                            autocomplete="off" 
                            required
                        >
                        <input type="hidden" id="selectedBookId" name="id_buku" required>
                        <div id="bookResults" class="list-group"></div>
                    </div>

                    <div class="form-group">
                        <label for="keperluan" class="form-label">
                            <i class="bi bi-chat-left-text"></i>
                            Keperluan Peminjaman
                        </label>
                        <textarea 
                            class="form-control" 
                            id="keperluan" 
                            name="keperluan" 
                            rows="4" 
                            placeholder="Jelaskan keperluan Mereka meminjam buku ini, contoh: Untuk penelitian sejarah Indonesia..." 
                            required
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_daftar" class="form-label">
                            <i class="bi bi-calendar-event"></i>
                            Tanggal Meminjam
                        </label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="tanggal_daftar" 
                            name="tanggal_daftar" 
                            required
                        >
                    </div>

                    <button type="submit" class="submit-button">
                        <i class="bi bi-check-circle"></i>
                        <span>Pinjam Buku</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Bootstrap Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom JavaScript -->
        <script src="../assets/js/style-pinjam-buku-tamu.js"></script>

        <!-- JavaScript untuk Alert Otomatis Hilang -->
        <script>
            // Jika ada GET ?success=1, maka tampilkan alert dan hilangkan setelah 5 detik
            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('success')) {
                    setTimeout(function () {
                        const alert = document.querySelector('.alert');
                        if (alert) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-20px)';
                            setTimeout(() => {
                                alert.style.display = 'none';
                            }, 300);
                        }
                    }, 5000); // Hilangkan alert setelah 5 detik
                }
            };

            // Set tanggal hari ini sebagai default
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('tanggal_daftar').value = today;
            });

            // Loading animation untuk submit button
            document.getElementById('borrowForm').addEventListener('submit', function() {
                const submitBtn = document.querySelector('.submit-button');
                const btnText = submitBtn.querySelector('span');
                const btnIcon = submitBtn.querySelector('i');
                
                btnIcon.className = 'loading';
                btnText.textContent = 'Memproses...';
                submitBtn.disabled = true;
            });
        </script>
    </body>
</html>