<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampüs Portal - Öğrenci Destek Platformu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --ink: #0f1419;
            --ink-light: #536471;
            --ink-faint: #8899a6;
            --surface: #ffffff;
            --surface-dim: #f7f9fa;
            --surface-alt: #eff3f4;
            --teal: #0d9488;
            --teal-dark: #0f766e;
            --amber: #f59e0b;
            --coral: #f43f5e;
            --border: #e1e8ed;
        }
        
        html { scroll-behavior: smooth; }
        
        body {
            font-family: 'Outfit', sans-serif;
            color: var(--ink);
            line-height: 1.6;
            background: var(--surface);
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 32px;
        }
        
        /* ===== HEADER ===== */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            transition: all 0.3s;
        }
        
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }
        
        .logo-mark {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .logo-mark::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }
        
        .logo-mark span {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 20px;
            color: white;
            position: relative;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 22px;
            color: var(--ink);
            letter-spacing: -0.5px;
        }
        
        nav {
            display: flex;
            align-items: center;
            gap: 40px;
        }
        
        nav a {
            color: var(--ink-light);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
        }
        
        nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--teal);
            transition: width 0.2s;
        }
        
        nav a:hover { color: var(--ink); }
        nav a:hover::after { width: 100%; }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        
        .btn-ghost {
            background: transparent;
            color: var(--ink);
        }
        
        .btn-ghost:hover {
            background: var(--surface-alt);
        }
        
        .btn-primary {
            background: var(--ink);
            color: white;
        }
        
        .btn-primary:hover {
            background: #2a3842;
            transform: translateY(-1px);
        }
        
        .btn-teal {
            background: var(--teal);
            color: white;
        }
        
        .btn-teal:hover {
            background: var(--teal-dark);
            transform: translateY(-1px);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--ink);
        }
        
        .btn-outline:hover {
            border-color: var(--ink);
        }
        
        .btn-lg {
            padding: 16px 32px;
            font-size: 16px;
            border-radius: 12px;
        }
        
        .btn-icon {
            margin-left: 8px;
            transition: transform 0.2s;
        }
        
        .btn:hover .btn-icon {
            transform: translateX(4px);
        }
        
        /* ===== HERO ===== */
        .hero {
            padding: 160px 0 100px;
            background: var(--surface);
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(13,148,136,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245,158,11,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 80px;
            align-items: center;
        }
        
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--surface-alt);
            border-radius: 100px;
            font-size: 14px;
            font-weight: 500;
            color: var(--ink-light);
            margin-bottom: 24px;
        }
        
        .hero-tag-dot {
            width: 8px;
            height: 8px;
            background: var(--teal);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 64px;
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 24px;
            color: var(--ink);
        }
        
        .hero h1 .accent {
            color: var(--teal);
        }
        
        .hero-desc {
            font-size: 19px;
            color: var(--ink-light);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 520px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 48px;
        }
        
        .hero-stats {
            display: flex;
            gap: 48px;
            padding-top: 40px;
            border-top: 1px solid var(--border);
        }
        
        .hero-stat {
            text-align: left;
        }
        
        .hero-stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -1px;
        }
        
        .hero-stat-label {
            font-size: 14px;
            color: var(--ink-faint);
            margin-top: 4px;
        }
        
        /* Hero Visual */
        .hero-visual {
            position: relative;
        }
        
        .hero-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.06);
            position: relative;
            z-index: 2;
        }
        
        .hero-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }
        
        .hero-card-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--ink-faint);
            font-weight: 600;
        }
        
        .hero-card-badge {
            padding: 6px 12px;
            background: rgba(13,148,136,0.1);
            color: var(--teal);
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
        }
        
        .meal-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--surface-alt);
        }
        
        .meal-item:last-child {
            border-bottom: none;
        }
        
        .meal-icon {
            width: 48px;
            height: 48px;
            background: var(--surface-alt);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .meal-info h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .meal-info p {
            font-size: 13px;
            color: var(--ink-faint);
        }
        
        .meal-status {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--teal);
        }
        
        .meal-status svg {
            width: 16px;
            height: 16px;
        }
        
        .hero-float-1 {
            position: absolute;
            top: -20px;
            right: -20px;
            background: var(--amber);
            color: white;
            padding: 16px 24px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 12px 40px rgba(245,158,11,0.3);
            animation: float 3s ease-in-out infinite;
            z-index: 3;
        }
        
        .hero-float-2 {
            position: absolute;
            bottom: 40px;
            left: -30px;
            background: var(--ink);
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 13px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
            animation: float 3s ease-in-out infinite 1s;
            z-index: 3;
        }
        
        .hero-float-2 strong {
            display: block;
            font-size: 20px;
            margin-bottom: 2px;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* ===== HOW IT WORKS ===== */
        .section {
            padding: 120px 0;
        }
        
        .section-dark {
            background: var(--ink);
            color: white;
        }
        
        .section-alt {
            background: var(--surface-dim);
        }
        
        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }
        
        .section-label {
            display: inline-block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            color: var(--teal);
            margin-bottom: 16px;
        }
        
        .section-dark .section-label {
            color: var(--amber);
        }
        
        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 44px;
            font-weight: 600;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        
        .section-header p {
            font-size: 18px;
            color: var(--ink-light);
            line-height: 1.7;
        }
        
        .section-dark .section-header p {
            color: rgba(255,255,255,0.7);
        }
        
        /* Steps */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }
        
        .step-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 32px;
            position: relative;
            transition: all 0.3s;
        }
        
        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        }
        
        .step-number {
            position: absolute;
            top: -16px;
            left: 32px;
            width: 44px;
            height: 44px;
            background: var(--ink);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
        }
        
        .step-icon {
            width: 64px;
            height: 64px;
            background: var(--surface-alt);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
            margin-top: 16px;
        }
        
        .step-card h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .step-card p {
            font-size: 15px;
            color: var(--ink-light);
            line-height: 1.7;
        }
        
        /* ===== FEATURES ===== */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .feature-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 32px;
            display: flex;
            gap: 20px;
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.2);
        }
        
        .feature-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .feature-content h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .feature-content p {
            font-size: 15px;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
        }
        
        /* ===== PILOT ===== */
        .pilot-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }
        
        .pilot-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 44px;
            font-weight: 600;
            letter-spacing: -1px;
            margin-bottom: 24px;
        }
        
        .pilot-text p {
            font-size: 17px;
            color: var(--ink-light);
            line-height: 1.8;
            margin-bottom: 32px;
        }
        
        .pilot-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            background: var(--surface-alt);
            border-radius: 12px;
        }
        
        .pilot-badge-icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        
        .pilot-badge-text {
            font-weight: 600;
            font-size: 15px;
        }
        
        .pilot-badge-text span {
            display: block;
            font-weight: 400;
            font-size: 13px;
            color: var(--ink-light);
        }
        
        .pilot-visual {
            position: relative;
        }
        
        .pilot-map {
            background: var(--surface-alt);
            border-radius: 24px;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }
        
        .pilot-map::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.5;
        }
        
        .pilot-location {
            position: relative;
            text-align: center;
        }
        
        .pilot-pin {
            width: 80px;
            height: 80px;
            background: var(--coral);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 40px rgba(244,63,94,0.3);
            animation: bounce 2s ease-in-out infinite;
        }
        
        .pilot-pin span {
            transform: rotate(45deg);
            font-size: 32px;
        }
        
        @keyframes bounce {
            0%, 100% { transform: rotate(-45deg) translateY(0); }
            50% { transform: rotate(-45deg) translateY(-8px); }
        }
        
        .pilot-city {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .pilot-uni {
            font-size: 15px;
            color: var(--ink-light);
        }
        
        /* ===== CTA ===== */
        .cta {
            padding: 120px 0;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        
        .cta-content {
            position: relative;
            text-align: center;
            color: white;
        }
        
        .cta h2 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .cta p {
            font-size: 19px;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta .btn {
            background: white;
            color: var(--teal-dark);
            padding: 18px 40px;
            font-size: 17px;
        }
        
        .cta .btn:hover {
            background: var(--surface-dim);
            transform: translateY(-2px);
        }
        
        /* ===== FOOTER ===== */
        footer {
            background: var(--ink);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }
        
        .footer-main {
            padding: 80px 0 48px;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 48px;
        }
        
        .footer-brand {
            max-width: 280px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .footer-logo .logo-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
        }
        
        .footer-logo .logo-mark span {
            font-size: 18px;
        }
        
        .footer-logo-text {
            font-weight: 700;
            font-size: 20px;
        }
        
        .footer-brand p {
            font-size: 15px;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
            margin-bottom: 24px;
        }
        
        .footer-social {
            display: flex;
            gap: 12px;
        }
        
        .footer-social a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.7);
            transition: all 0.2s;
        }
        
        .footer-social a:hover {
            background: var(--teal);
            color: white;
        }
        
        .footer-social svg {
            width: 18px;
            height: 18px;
        }
        
        .footer-column h4 {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 24px;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column li {
            margin-bottom: 14px;
        }
        
        .footer-column a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 15px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .footer-column a:hover {
            color: white;
            transform: translateX(4px);
        }
        
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
        }
        
        .footer-contact-item svg {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            flex-shrink: 0;
            color: var(--teal);
        }
        
        .footer-bottom {
            padding: 24px 0;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-copy {
            font-size: 14px;
            color: rgba(255,255,255,0.4);
        }
        
        .footer-legal {
            display: flex;
            gap: 24px;
        }
        
        .footer-legal a {
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }
        
        .footer-legal a:hover {
            color: rgba(255,255,255,0.7);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 60px;
            }
            
            .hero h1 {
                font-size: 48px;
            }
            
            .hero-visual {
                max-width: 500px;
                margin: 0 auto;
            }
            
            .pilot-content {
                grid-template-columns: 1fr;
                gap: 60px;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
            
            .header-inner {
                height: 64px;
            }
            
            nav {
                display: none;
            }
            
            .hero {
                padding: 120px 0 80px;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .hero-desc {
                font-size: 17px;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .hero-stats {
                flex-wrap: wrap;
                gap: 32px;
            }
            
            .hero-float-1,
            .hero-float-2 {
                display: none;
            }
            
            .section {
                padding: 80px 0;
            }
            
            .section-header h2,
            .pilot-text h2 {
                font-size: 32px;
            }
            
            .steps-grid,
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .cta h2 {
                font-size: 32px;
            }
            
            .footer-main {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 60px 0 40px;
            }
            
            .footer-brand {
                max-width: 100%;
                text-align: center;
            }
            
            .footer-logo {
                justify-content: center;
            }
            
            .footer-social {
                justify-content: center;
            }
            
            .footer-column {
                text-align: center;
            }
            
            .footer-column a:hover {
                transform: none;
            }
            
            .footer-contact-item {
                justify-content: center;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
            
            .footer-legal {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-inner">
                <a href="/" class="logo">
                    <div class="logo-mark"><span>K</span></div>
                    <span class="logo-text">Kampüs Portal</span>
                </a>
                
                <nav>
                    <a href="#nasil-calisir">Nasıl Çalışır?</a>
                    <a href="#ozellikler">Özellikler</a>
                    <a href="#pilot">Pilot Proje</a>
                </nav>
                
                <div class="header-actions">
                    <a href="/giris" class="btn btn-ghost">Giriş Yap</a>
                    <a href="/kayit" class="btn btn-primary">Kayıt Ol</a>
                </div>
            </div>
        </div>
    </header>
    
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <div class="hero-tag">
                        <span class="hero-tag-dot"></span>
                        Selçuk Üniversitesi Vakfı ile başladık
                    </div>
                    
                    <h1>Bir öğün, <span class="accent">bir umut</span> demek.</h1>
                    
                    <p class="hero-desc">
                        Bağışlarınız doğrudan ihtiyaç sahibi üniversite öğrencilerine ulaşır. 
                        Şeffaf, güvenli ve anonim destek platformu.
                    </p>
                    
                    <div class="hero-buttons">
                        <a href="/kayit?rol=bagisci" class="btn btn-teal btn-lg">
                            Bağış Yap
                            <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                        <a href="/kayit" class="btn btn-outline btn-lg">Öğrenci Kaydı</a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-value">207</div>
                            <div class="hero-stat-label">Üniversite</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value">%100</div>
                            <div class="hero-stat-label">Şeffaflık</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value">0₺</div>
                            <div class="hero-stat-label">Komisyon</div>
                        </div>
                    </div>
                </div>
                
                <div class="hero-visual">
                    <div class="hero-card">
                        <div class="hero-card-header">
                            <span class="hero-card-title">Bugünün Menüsü</span>
                            <span class="hero-card-badge">Aktif</span>
                        </div>
                        
                        <div class="meal-item">
                            <div class="meal-icon">🍲</div>
                            <div class="meal-info">
                                <h4>Öğle Yemeği</h4>
                                <p>Mercimek Çorbası, Tavuk Sote, Pilav</p>
                            </div>
                            <div class="meal-status">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Hazır
                            </div>
                        </div>
                        
                        <div class="meal-item">
                            <div class="meal-icon">🥗</div>
                            <div class="meal-info">
                                <h4>Akşam Yemeği</h4>
                                <p>Domates Çorbası, Kuru Fasulye, Bulgur</p>
                            </div>
                            <div class="meal-status">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Hazır
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-float-1">
                        ❤️ Yeni bağış geldi!
                    </div>
                    
                    <div class="hero-float-2">
                        <strong>+1.250</strong>
                        Dağıtılan öğün
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section" id="nasil-calisir">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Nasıl Çalışır?</span>
                <h2>Üç adımda destek olun</h2>
                <p>Bağışçılar ve öğrenciler için basit, güvenli ve şeffaf bir sistem tasarladık.</p>
            </div>
            
            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-number">1</span>
                    <div class="step-icon">✍️</div>
                    <h3>Kayıt Olun</h3>
                    <p>Bağışçı veya öğrenci olarak platforma kayıt olun. Öğrenciler üniversite bilgileri ile doğrulanır.</p>
                </div>
                
                <div class="step-card">
                    <span class="step-number">2</span>
                    <div class="step-icon">💳</div>
                    <h3>Bağış veya Rezervasyon</h3>
                    <p>Bağışçılar güvenli ödeme ile bağış yapar. Öğrenciler anlaşmalı restoranlardan yemek rezervasyonu oluşturur.</p>
                </div>
                
                <div class="step-card">
                    <span class="step-number">3</span>
                    <div class="step-icon">🎉</div>
                    <h3>Kullanım</h3>
                    <p>Öğrenci restoranda QR kodunu gösterir ve yemeğini alır. Bağışçıya anonim teşekkür bildirimi gider.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section section-dark" id="ozellikler">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Platform Özellikleri</span>
                <h2>Güvenli ve şeffaf altyapı</h2>
                <p>Tüm süreçler denetlenebilir ve takip edilebilir şekilde tasarlandı.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <div class="feature-content">
                        <h3>Güvenli Ödeme</h3>
                        <p>Tüm bağışlar banka altyapısı üzerinden alınır. Restoran kasasından para geçmez.</p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <div class="feature-content">
                        <h3>Tek Kullanımlık QR</h3>
                        <p>Her öğün için dinamik QR kod üretilir. Kopyalanamaz, paylaşılamaz.</p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <div class="feature-content">
                        <h3>Şeffaf Takip</h3>
                        <p>Bağışçılar bağışlarının kullanımını anonim olarak takip edebilir.</p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🏛️</div>
                    <div class="feature-content">
                        <h3>Vakıf Denetimi</h3>
                        <p>Her üniversite vakfı kendi bütçesini ve politikasını bağımsız yönetir.</p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">✅</div>
                    <div class="feature-content">
                        <h3>Doğrulanmış Öğrenciler</h3>
                        <p>Sadece aktif öğrenciler sistemden faydalanabilir. Kimlik doğrulaması yapılır.</p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💵</div>
                    <div class="feature-content">
                        <h3>Hak Ediş Sistemi</h3>
                        <p>Restoranlar yalnızca doğrulanmış kullanımlar için ödeme alır.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section" id="pilot">
        <div class="container">
            <div class="pilot-content">
                <div class="pilot-text">
                    <h2>Pilot Proje:<br>Konya'da Başladık</h2>
                    <p>
                        Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı işbirliğiyle 
                        Konya'da pilot uygulama olarak başlattık. Başarılı sonuçların 
                        ardından Türkiye genelinde 207 üniversiteye yaygınlaştırılacak.
                    </p>
                    
                    <div class="pilot-badge">
                        <div class="pilot-badge-icon">🏫</div>
                        <div class="pilot-badge-text">
                            Selçuk Üniversitesi Vakfı
                            <span>Resmi İş Ortağı</span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 32px;">
                        <a href="/kayit" class="btn btn-teal btn-lg">Pilot Projeye Katıl</a>
                    </div>
                </div>
                
                <div class="pilot-visual">
                    <div class="pilot-map">
                        <div class="pilot-location">
                            <div class="pilot-pin"><span>📍</span></div>
                            <div class="pilot-city">Konya</div>
                            <div class="pilot-uni">Selçuk Üniversitesi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Bir öğrencinin gününe ışık olun</h2>
                <p>Yapacağınız küçük bir bağış, bir öğrencinin karnını doyuracak ve umudunu artıracak.</p>
                <a href="/kayit?rol=bagisci" class="btn">Hemen Bağış Yap</a>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <div class="footer-main">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo-mark"><span>K</span></div>
                        <span class="footer-logo-text">Kampüs Portal</span>
                    </div>
                    <p>Bağışçılar ve öğrencileri bir araya getiren güvenli platform. Bağışlarınız doğrudan ihtiyaç sahibi öğrencilere ulaşır.</p>
                    <div class="footer-social">
                        <a href="#" aria-label="Twitter">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="#nasil-calisir">Nasıl Çalışır?</a></li>
                        <li><a href="#ozellikler">Özellikler</a></li>
                        <li><a href="#pilot">Pilot Proje</a></li>
                        <li><a href="/kayit">Kayıt Ol</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Destek</h4>
                    <ul>
                        <li><a href="#">Sıkça Sorulan Sorular</a></li>
                        <li><a href="#">Yardım Merkezi</a></li>
                        <li><a href="#">Bize Ulaşın</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>İletişim</h4>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>info@kampusportal.com.tr</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Konya, Türkiye<br>Selçuk Üniversitesi Kampüsü</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-copy">
                    © <?= date('Y') ?> Kampüs Portal. Tüm hakları saklıdır.
                </div>
                <div class="footer-legal">
                    <a href="#">Gizlilik Politikası</a>
                    <a href="#">Kullanım Şartları</a>
                    <a href="#">KVKK</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
