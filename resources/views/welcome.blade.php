<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>School Management System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        </style>

        <style>
            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Nunito', sans-serif;
                background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.24), transparent 30%),
                            linear-gradient(180deg, #0F172A 0%, #111827 100%);
                color: #E2E8F0;
            }

            .container {
                max-width: 1120px;
                margin: 0 auto;
                padding: 32px 24px;
            }

            .header {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                padding-bottom: 24px;
            }

            .brand {
                font-size: 1.5rem;
                font-weight: 700;
                letter-spacing: 0.05em;
            }

            .brand span {
                color: #38BDF8;
            }

            .btn-primary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 12px 24px;
                border-radius: 9999px;
                background: #38BDF8;
                color: #0F172A;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 20px 45px rgba(56, 189, 248, 0.25);
            }

            .hero {
                display: grid;
                gap: 32px;
                padding: 60px 0;
                align-items: center;
            }

            .hero-title {
                font-size: clamp(2.8rem, 5vw, 4.4rem);
                line-height: 1.02;
                margin: 0;
                color: #F8FAFC;
            }

            .hero-text {
                max-width: 680px;
                margin-top: 24px;
                color: #CBD5E1;
                font-size: 1.05rem;
                line-height: 1.8;
            }

            .hero-panel {
                display: grid;
                gap: 16px;
                padding: 28px;
                border-radius: 24px;
                background: rgba(15, 23, 42, 0.92);
                border: 1px solid rgba(148, 163, 184, 0.14);
                box-shadow: 0 30px 80px rgba(15, 23, 42, 0.35);
            }

            .hero-panel h2 {
                margin: 0;
                font-size: 1.2rem;
                color: #E2E8F0;
            }

            .hero-panel p,
            .hero-panel li {
                color: #94A3B8;
                line-height: 1.85;
            }

            .features {
                display: grid;
                gap: 20px;
                margin-top: 40px;
            }

            .feature-card {
                padding: 26px;
                border-radius: 24px;
                background: rgba(15, 23, 42, 0.88);
                border: 1px solid rgba(148, 163, 184, 0.12);
                transition: transform 0.2s ease, border-color 0.2s ease;
            }

            .feature-card:hover {
                transform: translateY(-4px);
                border-color: rgba(56, 189, 248, 0.5);
            }

            .feature-card h3 {
                margin-top: 0;
                margin-bottom: 12px;
                font-size: 1.15rem;
            }

            .feature-card p {
                margin: 0;
                color: #94A3B8;
                line-height: 1.75;
            }

            .footer {
                margin-top: 56px;
                padding-top: 24px;
                border-top: 1px solid rgba(148, 163, 184, 0.12);
                text-align: center;
                color: #94A3B8;
                font-size: 0.95rem;
            }

            @media (min-width: 768px) {
                .hero {
                    grid-template-columns: 1.15fr 0.85fr;
                    text-align: left;
                }

                .features {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="header">
                <div class="brand"><span>SMS</span> School Management</div>
                <a href="/login" class="btn-primary">Login</a>
            </header>

            <section class="hero">
                <div>
                    <h1 class="hero-title">Modern school operations, simplified.</h1>
                    <p class="hero-text">A powerful school management platform for attendance, fee tracking, student records and academic reporting. Built to help administrators, teachers and parents stay aligned in one secure system.</p>
                    <div style="margin-top: 32px;">
                        <a href="/login" class="btn-primary">Access the Dashboard</a>
                    </div>
                </div>
                <div class="hero-panel">
                    <h2>Why this platform?</h2>
                    <p>Securely manage daily school workflows, reduce manual tasks, and deliver reliable student and fee records across your institution.</p>
                    <ul style="margin: 0; padding-left: 1.2rem; color: #94A3B8; line-height: 1.9;">
                        <li>Real-time fee and invoice tracking</li>
                        <li>Student profiles and academic records</li>
                        <li>Attendance, promotion and exam reports</li>
                        <li>Fast login for staff and parents</li>
                    </ul>
                </div>
            </section>

            <section class="features">
                <div class="feature-card">
                    <h3>Student Information</h3>
                    <p>Centralize admissions, profiles, class assignments and progress tracking for every learner.</p>
                </div>
                <div class="feature-card">
                    <h3>Financial Management</h3>
                    <p>Manage tuition, invoices, receipts and online payments with clear, audit-ready records.</p>
                </div>
                <div class="feature-card">
                    <h3>Attendance & Reporting</h3>
                    <p>Capture attendance, review classroom performance, and generate reports that help educators act quickly.</p>
                </div>
            </section>

            <footer class="footer">
                &copy; {{ date('Y') }} School Management System. Crafted for efficient school administration.
            </footer>
        </div>
    </body>
</html>
