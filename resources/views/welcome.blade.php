<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sanggar Seni Dharmo Yuwono Purwokerto - Pelestarian Seni Tari Nusantara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sanggar Seni Dharmo Yuwono Purwokerto - Berdiri sejak 29 Maret 1979, mengembangkan dan melestarikan seni tari tradisional dan kreasi Nusantara di Jawa Tengah dan Banyumasan.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo1.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0b3d2e;
            --primary-dark: #07291f;
            --primary-light: #135541;
            --primary-subtle: #e8f4f0;
            --accent-gold: #d4af37;
            --accent-gold-light: #fef8e7;
            --accent-gold-dark: #b89120;
            --dark: #0f172a;
            --gray-text: #475569;
            --gray-light: #f8faf9;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark);
            background-color: #ffffff;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background-color: rgba(11, 61, 46, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.3s ease;
            padding: 14px 0;
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .navbar-custom .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.2s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .navbar-custom .nav-link:hover {
            color: var(--accent-gold) !important;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Hero Section */
        .hero {
            position: relative;
            background: linear-gradient(135deg, rgba(7, 41, 31, 0.70) 0%, rgba(11, 61, 46, 0.72) 100%),
                        url("/images/landing-bg.jpeg") center/cover no-repeat;
            color: white;
            padding: 140px 0 110px;
            overflow: hidden;
        }


        .hero::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to top, #ffffff, transparent);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #ffe082;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 20px;
            backdrop-filter: blur(4px);
        }

        .hero-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
            margin-bottom: 24px;
            animation: floatLogo 4s ease-in-out infinite;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .hero-title {
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--accent-gold);
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero-desc {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.7;
            max-width: 720px;
            margin: 20px auto 0;
        }

        /* Buttons */
        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #b89120 100%);
            color: #0b3d2e;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 50px;
            border: none;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.35);
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #e5c158 0%, #c59b27 100%);
            color: #07291f;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(212, 175, 55, 0.45);
        }

        .btn-outline-custom {
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary);
            border-color: white;
            transform: translateY(-2px);
        }

        /* Section Header */
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-tag {
            display: inline-block;
            color: var(--primary);
            background: var(--primary-subtle);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent-gold));
            border-radius: 10px;
        }

        .section-subtitle {
            color: var(--gray-text);
            font-size: 1.05rem;
            max-width: 680px;
            margin: 15px auto 0;
        }

        /* Profil & Sejarah Cards */
        .history-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9fcfa 100%);
            border: 1px solid rgba(11, 61, 46, 0.12);
            border-radius: 24px;
            padding: 42px;
            box-shadow: 0 16px 40px rgba(11, 61, 46, 0.05);
            position: relative;
            overflow: hidden;
        }

        .history-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary), var(--accent-gold));
        }

        .history-stat-box {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 22px 18px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .history-stat-box:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
            box-shadow: 0 12px 24px rgba(11, 61, 46, 0.09);
        }

        .history-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--primary-subtle);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin: 0 auto 12px;
        }

        .history-stat-num {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .history-stat-label {
            font-size: 0.82rem;
            color: var(--gray-text);
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0;
        }

        .timeline-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-gold-light);
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: var(--accent-gold-dark);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        /* Visi Misi Cards */
        .vision-card {
            background: #ffffff;
            border: 1px solid rgba(11, 61, 46, 0.1);
            border-radius: 24px;
            padding: 40px 32px;
            height: 100%;
            box-shadow: 0 15px 35px rgba(11, 61, 46, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .vision-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(11, 61, 46, 0.12);
            border-color: var(--primary);
        }

        .card-icon-box {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 24px;
        }

        .icon-box-visi {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(11, 61, 46, 0.25);
        }

        .icon-box-misi {
            background: linear-gradient(135deg, #b89120 0%, #d4af37 100%);
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }

        .vision-quote {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--primary-dark);
            line-height: 1.7;
            padding: 20px;
            background: var(--primary-subtle);
            border-radius: 16px;
            border-left: 4px solid var(--primary);
        }

        .misi-list {
            list-style: none;
            padding-left: 0;
            margin-top: 20px;
        }

        .misi-list li {
            position: relative;
            padding-left: 38px;
            margin-bottom: 18px;
            color: var(--gray-text);
            font-size: 1rem;
            line-height: 1.6;
        }

        .misi-list li i {
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 1.25rem;
            color: var(--primary);
        }

        /* Program Kerja Cards */
        .program-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 36px;
            height: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .program-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 45px rgba(11, 61, 46, 0.1);
            border-color: var(--primary-light);
        }

        .program-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .badge-short {
            background: #e6f6ec;
            color: #0e8345;
            border: 1px solid #b7ebcb;
        }

        .badge-long {
            background: #fdf5e2;
            color: #9c6c06;
            border: 1px solid #f8e1a7;
        }

        /* Level Pills Grid */
        .level-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
            margin-top: 20px;
        }

        .level-pill {
            background: var(--gray-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 10px;
            text-align: center;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--primary-dark);
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .level-pill span.sub {
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--gray-text);
        }

        .level-pill:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(11, 61, 46, 0.2);
        }

        .level-pill:hover span.sub {
            color: #ffe082;
        }

        /* Long term list items */
        .long-term-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 14px 16px;
            background: var(--gray-light);
            border-radius: 14px;
            margin-bottom: 12px;
            border: 1px solid transparent;
            transition: all 0.25s ease;
        }

        .long-term-item:hover {
            background: #ffffff;
            border-color: var(--accent-gold);
            transform: translateX(4px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .long-term-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: white;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        }

        .long-term-text h6 {
            margin-bottom: 2px;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.95rem;
        }

        .long-term-text p {
            margin-bottom: 0;
            font-size: 0.84rem;
            color: var(--gray-text);
        }

        /* Gallery */
        .gallery-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            height: 270px;
            background: #000;
        }

        .gallery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-card:hover img {
            transform: scale(1.08);
            opacity: 0.9;
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 24px 20px;
            background: linear-gradient(to top, rgba(7, 41, 31, 0.9) 0%, transparent 100%);
            color: white;
        }

        .gallery-overlay h6 {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .gallery-overlay span {
            font-size: 0.8rem;
            color: var(--accent-gold);
        }

        /* CTA Banner */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 28px;
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(11, 61, 46, 0.25);
        }

        .cta-section::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, transparent 70%);
        }

        /* Footer */
        .footer-custom {
            background-color: var(--primary-dark);
            color: #cbd5e1;
            padding: 60px 0 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .footer-social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            text-decoration: none;
            margin-right: 8px;
            transition: all 0.2s ease;
        }

        .footer-social-link:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            .section-title {
                font-size: 1.75rem;
            }
            .history-card, .vision-card, .program-card {
                padding: 24px;
            }
            .level-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/images/logo1.png" alt="Logo Sanggar" width="42" height="42" class="me-2 rounded-circle bg-white p-1">
                <div>
                    <span class="d-block text-white fw-bold lh-1" style="font-size: 1.05rem;">Sanggar Seni Dharmo Yuwono</span>
                    <small class="text-white-50" style="font-size: 0.75rem; letter-spacing: 1px;">PURWOKERTO</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1 my-3 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#sejarah">Sejarah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#visimisi">Visi & Misi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#program">Program Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#galeri">Galeri</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item ms-lg-1">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-gold btn-sm px-3">
                            <i class="fa-solid fa-user-plus me-1"></i> Pendaftaran Siswa
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero text-center">
        <div class="container" data-aos="fade-up" data-aos-duration="900">
            <div class="hero-badge">
                <i class="fa-solid fa-sparkles text-warning"></i> Berdiri Sejak 1979 • Pelestarian Seni Tari & Karawitan
            </div>
            <br>
            <img src="/images/logo1.png" alt="Logo Sanggar Dharmo Yuwono" class="hero-logo">
            <h1 class="hero-title">Sanggar Seni Dharmo Yuwono</h1>
            <div class="hero-subtitle mt-2">Purwokerto</div>

            <p class="hero-desc">
                Mengembangkan dan melestarikan kekayaan seni tari Nusantara dengan metode pembelajaran terstruktur,
                berjenjang, dan dibimbing langsung oleh pelatih profesional dan berdedikasi.
            </p>

            <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-gold btn-lg shadow-lg">
                    <i class="fa-solid fa-file-signature me-2"></i> Daftar Siswa Baru
                </a>
                <a href="#sejarah" class="btn btn-outline-custom btn-lg">
                    <i class="fa-solid fa-book-open me-2"></i> Sejarah Singkat
                </a>
            </div>
        </div>
    </section>

    <!-- SEJARAH SINGKAT -->
    <section class="py-5 bg-white" id="sejarah">
        <div class="container py-4">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag"><i class="fa-solid fa-landmark-dome me-1"></i> Rekam Jejak Budaya</span>
                <h2 class="section-title">Sejarah Singkat</h2>
                <p class="section-subtitle">
                    Perjalanan dedikasi panjang dalam melestarikan seni pertunjukan Nusantara dan membina generasi muda bangsa.
                </p>
            </div>

            <div class="history-card" data-aos="fade-up">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="timeline-badge">
                            <i class="fa-solid fa-clock-rotate-left"></i> Berdiri Sejak 29 Maret 1979
                        </div>
                        <h3 class="fw-bold text-dark mb-3" style="font-size: 1.85rem; line-height: 1.3;">
                            Mewadahi Seni & Dedikasi Sosial di Purwokerto
                        </h3>
                        <p class="text-secondary mb-4" style="line-height: 1.85; font-size: 1.03rem;">
                            <strong>Sanggar Seni Dharmo Yuwono</strong> berdiri pada <strong>29 Maret 1979</strong> di Purwokerto, didirikan oleh <strong>Bapak Kamaru Samsi</strong> untuk memenuhi kebutuhan masyarakat akan seni pertunjukan, khususnya seni tari dan karawitan, sekaligus sebagai usaha ekonomis produktif <strong>Panti Asuhan Dharmo Yuwono</strong>.
                        </p>
                        <p class="text-secondary mb-4" style="line-height: 1.85; font-size: 1.03rem;">
                            Selama lebih dari empat dekade perjalanan dedikasinya, hingga tahun <strong>2026</strong> sanggar ini telah meluluskan <strong>±1.500 alumni</strong> dan terus aktif mendidik <strong>lebih dari 200 siswa aktif</strong> yang giat berlatih melestarikan seni tradisi Nusantara.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="fa-solid fa-location-dot text-success me-1"></i> Purwokerto</span>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="fa-solid fa-masks-theater text-warning me-1"></i> Seni Tari & Karawitan</span>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="fa-solid fa-hand-holding-heart text-danger me-1"></i> Panti Asuhan Dharmo Yuwono</span>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="history-stat-box">
                                    <div class="history-stat-icon">
                                        <i class="fa-solid fa-calendar-day"></i>
                                    </div>
                                    <div class="history-stat-num">1979</div>
                                    <p class="history-stat-label">Didirikan 29 Maret oleh Bpk. Kamaru Samsi</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="history-stat-box">
                                    <div class="history-stat-icon" style="background: #fef8e7; color: #b89120;">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                    <div class="history-stat-num" style="color: #9c6c06;">±1.500</div>
                                    <p class="history-stat-label">Alumni Penari & Penggerak Seni</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="history-stat-box">
                                    <div class="history-stat-icon" style="background: #e6f6ec; color: #0e8345;">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <div class="history-stat-num" style="color: #0e8345;">200+</div>
                                    <p class="history-stat-label">Siswa Aktif Terbina (Hingga 2026)</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="history-stat-box">
                                    <div class="history-stat-icon" style="background: #f1f5f9; color: #475569;">
                                        <i class="fa-solid fa-award"></i>
                                    </div>
                                    <div class="history-stat-num">47 Th</div>
                                    <p class="history-stat-label">Kiprah Pelestarian Seni Budaya</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI -->
    <section class="py-5" style="background-color: #f8faf8;" id="visimisi">
        <div class="container py-4">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag"><i class="fa-solid fa-compass me-1"></i> Arah & Tujuan</span>
                <h2 class="section-title">Visi & Misi</h2>
                <p class="section-subtitle">
                    Komitmen teguh Sanggar Seni Dharmo Yuwono dalam menjaga keluhuran seni budaya bangsa untuk generasi mendatang.
                </p>
            </div>

            <div class="row g-4 mt-2">
                <!-- VISI -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="vision-card">
                        <div class="card-icon-box icon-box-visi">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <h3 class="fw-bold mb-3 text-dark">Visi</h3>
                        <div class="vision-quote mb-4">
                            "Mengembangkan dan melestarikan budaya Nusantara, khususnya seni tari."
                        </div>
                        <p class="text-secondary mb-0" style="font-size: 0.95rem; line-height: 1.7;">
                            Menjadi episentrum pembinaan kesenian yang menumbuhkan rasa cinta tanah air, memperkuat jati diri budaya anak bangsa, dan memajukan karya seni tari Indonesia hingga kancah yang lebih luas.
                        </p>
                    </div>
                </div>

                <!-- MISI -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="vision-card">
                        <div class="card-icon-box icon-box-misi">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h3 class="fw-bold mb-3 text-dark">Misi</h3>
                        <ul class="misi-list">
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <strong>Menunjang Program Pemerintah:</strong> Berperan aktif mendukung kebijakan dan program pemerintah dalam pengembangan dan pelestarian seni tari.
                            </li>
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <strong>Pelatihan Seni Tari Beragam Daerah:</strong> Memberikan pelatihan seni tari berbagai daerah di Nusantara, khususnya kekayaan tari <strong>Jawa Tengah</strong> dan gaya <strong>Banyumasan</strong>.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM KERJA -->
    <section class="py-5 bg-white" id="program">
        <div class="container py-4">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag"><i class="fa-solid fa-calendar-check me-1"></i> Rencana & Realisasi</span>
                <h2 class="section-title">Program Kerja</h2>
                <p class="section-subtitle">
                    Struktur program kerja terencana untuk menjamin mutu pendidikan tari dari dasar hingga panggung profesional.
                </p>
            </div>

            <div class="row g-4">
                <!-- PROGRAM JANGKA PENDEK -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="program-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="program-badge badge-short">
                                <i class="fa-solid fa-stopwatch me-1"></i> Jangka Pendek
                            </span>
                            <div class="text-success fs-4">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-dark mt-2 mb-2">Pelatihan Tingkat Bertahap</h4>
                        <p class="text-secondary" style="font-size: 0.93rem; line-height: 1.6;">
                            Pelaksanaan kegiatan latihan rutin berjenjang yang fokus pada penanaman wiraga (raga/gerak), wirama (ketukan irama), dan wirasa (ekspresi/penghayatan) pada setiap jenjang:
                        </p>

                        <div class="level-grid">
                            <div class="level-pill">
                                <span>Pradasar</span>
                                <span class="sub">Tingkat Awal</span>
                            </div>
                            <div class="level-pill">
                                <span>Dasar 1.1</span>
                                <span class="sub">Fondasi Gerak</span>
                            </div>
                            <div class="level-pill">
                                <span>Dasar 1.2</span>
                                <span class="sub">Pengayaan Ragam</span>
                            </div>
                            <div class="level-pill">
                                <span>Dasar 2.1</span>
                                <span class="sub">Harmonisasi Irama</span>
                            </div>
                            <div class="level-pill">
                                <span>Dasar 2.2</span>
                                <span class="sub">Penguatan Wiraga</span>
                            </div>
                            <div class="level-pill">
                                <span>Terampil 1</span>
                                <span class="sub">Karakter Tari</span>
                            </div>
                            <div class="level-pill">
                                <span>Terampil 2</span>
                                <span class="sub">Eksplorasi Kreasi</span>
                            </div>
                            <div class="level-pill" style="background: var(--primary-subtle); border-color: var(--primary);">
                                <span style="color: var(--primary);">Lanjut</span>
                                <span class="sub">Mahir & Pentas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROGRAM JANGKA PANJANG -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="program-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="program-badge badge-long">
                                <i class="fa-solid fa-chart-line me-1"></i> Jangka Panjang
                            </span>
                            <div class="text-warning fs-4">
                                <i class="fa-solid fa-crown"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-dark mt-2 mb-2">Pengembangan & Eksistensi Budaya</h4>
                        <p class="text-secondary" style="font-size: 0.93rem; line-height: 1.6;">
                            Inisiatif strategis berkelanjutan untuk membangun ekosistem sanggar yang unggul, profesional, dan berdampak bagi masyarakat luas:
                        </p>

                        <div class="mt-3">
                            <div class="long-term-item">
                                <div class="long-term-icon" style="color: #b89120;">
                                    <i class="fa-solid fa-masks-theater"></i>
                                </div>
                                <div class="long-term-text">
                                    <h6>Pementasan & Pagelaran Seni</h6>
                                    <p>Penyelenggaraan pentas tahunan dan unjuk bakat siswa di ruang publik dan gedung kesenian.</p>
                                </div>
                            </div>

                            <div class="long-term-item">
                                <div class="long-term-icon" style="color: #0b3d2e;">
                                    <i class="fa-solid fa-heart-pulse"></i>
                                </div>
                                <div class="long-term-text">
                                    <h6>Peningkatan Pelayanan</h6>
                                    <p>Optimalisasi fasilitas sanggar, komunikasi siswa-orang tua, dan administrasi digital modern.</p>
                                </div>
                            </div>

                            <div class="long-term-item">
                                <div class="long-term-icon" style="color: #2563eb;">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <div class="long-term-text">
                                    <h6>Seminar & Workshop Seni</h6>
                                    <p>Menyelenggarakan forum edukasi seni budaya bersama praktisi tari daerah dan akademisi.</p>
                                </div>
                            </div>

                            <div class="long-term-item">
                                <div class="long-term-icon" style="color: #dc2626;">
                                    <i class="fa-solid fa-scroll"></i>
                                </div>
                                <div class="long-term-text">
                                    <h6>Pelestarian Seni Tradisi</h6>
                                    <p>Eksplorasi, revitalisasi, dan dokumentasi ragam seni tradisi Nusantara dan Banyumasan.</p>
                                </div>
                            </div>

                            <div class="long-term-item">
                                <div class="long-term-icon" style="color: #7c3aed;">
                                    <i class="fa-solid fa-building-wheat"></i>
                                </div>
                                <div class="long-term-text">
                                    <h6>Pengembangan Sanggar</h6>
                                    <p>Perluasan jejaring kolaborasi budaya dan peningkatan sarana prasarana latihan sanggar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALERI KEGIATAN -->
    <section class="py-5" style="background-color: #f8faf8;" id="galeri">
        <div class="container py-4">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag"><i class="fa-solid fa-images me-1"></i> Dokumentasi</span>
                <h2 class="section-title">Galeri Kegiatan</h2>
                <p class="section-subtitle">
                    Momen-momen indah pementasan, proses latihan, dan keceriaan siswa dalam melestarikan seni tari.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="gallery-card">
                        <img src="/images/galeri_sipeket_1.jpeg" alt="Ujian Kenaikan Tingkat">
                        <div class="gallery-overlay">
                            <h6>Ujian Kenaikan Tingkat</h6>
                            <span>Evaluasi Ujian Akhir Kelas Terampil Sanggar</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="gallery-card">
                        <img src="/images/galeri_sipeket_2.jpeg" alt="Pementasan Tari Tradisional">
                        <div class="gallery-overlay">
                            <h6>Pementasan Tari Tradisional</h6>
                            <span>Penampilan Memukau Tari Tradisi & Kreasi Banyumasan</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="gallery-card">
                        <img src="/images/galeri_sipeket_3.jpeg" alt="Pagelaran Kolosal Kebudayaan">
                        <div class="gallery-overlay">
                            <h6>Pagelaran Kolosal Kebudayaan</h6>
                            <span>Atraksi Tari Kolosal Bernuansa Kebangsaan</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="gallery-card">
                        <img src="/images/galeri_sipeket_4.jpeg" alt="Harmoni Wiraga & Wirama">
                        <div class="gallery-overlay">
                            <h6>Harmoni Wiraga & Wirama</h6>
                            <span>Penjiwaan Gerak Tari Diiringi Musik Gamelan Live</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="gallery-card">
                        <img src="/images/galeri_sipeket_5.jpeg" alt="Pembinaan Kelas Terampil">
                        <div class="gallery-overlay">
                            <h6>Pembinaan Kelas Terampil</h6>
                            <span>Latihan Rutin Intensif & Eksplorasi Ragam Gerak</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="gallery-card">
                        <img src="/images/galeri_sipeket_6.jpeg" alt="Latihan Rutin Tingkat Dasar">
                        <div class="gallery-overlay">
                            <h6>Latihan Rutin Tingkat Dasar</h6>
                            <span>Pembentukan Karakter & Teknik Dasar Siswa Pemula</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION BANNER -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="cta-section" data-aos="fade-up">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-8">
                        <h3 class="fw-bold text-white mb-2" style="font-size: 1.85rem;">
                            Ingin Bergabung & Belajar Seni Tari Bersama Kami?
                        </h3>
                        <p class="text-white-50 mb-0" style="font-size: 1.05rem;">
                            Pendaftaran siswa baru untuk seluruh tingkat dibuka secara online. Mari wujudkan generasi berbakat dan berbudaya!
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-gold btn-lg px-4 shadow">
                            <i class="fa-solid fa-pen-nib me-2"></i> Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row g-4 pb-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center mb-3">
                        <img src="/images/logo1.png" alt="Logo" class="footer-logo me-2 rounded-circle bg-white p-1">
                        <div>
                            <h5 class="text-white fw-bold mb-0">Sanggar Seni Dharmo Yuwono</h5>
                            <small class="text-white-50">Purwokerto, Jawa Tengah</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Lembaga pendidikan dan pelestarian seni tari Nusantara yang berdedikasi sejak 29 Maret 1979 membentuk penari yang santun, berkarakter, dan berdaya saing budaya.
                    </p>
                    <div>
                        <a href="#" class="footer-social-link"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="footer-social-link"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="footer-social-link"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="footer-social-link"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Navigasi Cepat</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="#sejarah" class="text-secondary text-decoration-none hover-white"><i class="fa-solid fa-angle-right me-1 text-warning"></i> Sejarah Singkat</a></li>
                        <li class="mb-2"><a href="#visimisi" class="text-secondary text-decoration-none"><i class="fa-solid fa-angle-right me-1 text-warning"></i> Visi & Misi</a></li>
                        <li class="mb-2"><a href="#program" class="text-secondary text-decoration-none"><i class="fa-solid fa-angle-right me-1 text-warning"></i> Program Kerja</a></li>
                        <li class="mb-2"><a href="#galeri" class="text-secondary text-decoration-none"><i class="fa-solid fa-angle-right me-1 text-warning"></i> Galeri Kegiatan</a></li>
                        <li class="mb-2"><a href="{{ route('pendaftaran.index') }}" class="text-secondary text-decoration-none"><i class="fa-solid fa-angle-right me-1 text-warning"></i> Form Pendaftaran</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Alamat & Kontak</h6>
                    <ul class="list-unstyled text-secondary small" style="line-height: 1.8;">
                        <li class="mb-2">
                            <i class="fa-solid fa-location-dot text-warning me-2"></i> Jl. Supriyadi No.I/2, Purwokerto Wetan, Kecamatan Purwokerto Timur, Kabupaten Banyumas, Jawa Tengah 53111
                        </li>
                        <li class="mb-2">
                            <i class="fa-solid fa-envelope text-warning me-2"></i> @sanggarsenidharmoyuwono
                        </li>
                        <li class="mb-2">
                            <i class="fa-solid fa-shield-halved text-warning me-2"></i> Sistem Informasi Penilaian & Pelatihan (SIPEKET)
                        </li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: rgba(255, 255, 255, 0.1);">

            <div class="text-center pt-2 text-secondary small">
                © {{ date('Y') }} <strong>Sanggar Seni Dharmo Yuwono Purwokerto</strong>. Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 80
        });
    </script>

</body>

</html>