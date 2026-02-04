<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terms and Conditions - Core Mining</title>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/register.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .terms-content {
            max-height: 60vh;
            overflow-y: auto;
            padding: 1.5rem;
            line-height: 1.8;
            color: var(--text-secondary, #6b7280);
        }
        .terms-content h2 {
            color: var(--text-primary, #1f2937);
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .terms-content h2:first-child {
            margin-top: 0;
        }
        .terms-content h3 {
            color: var(--text-primary, #1f2937);
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .terms-content p {
            margin-bottom: 1rem;
        }
        .terms-content ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .terms-content li {
            margin-bottom: 0.5rem;
        }
        .back-button {
            width: 100%;
            padding: 0.875rem 1rem;
            background: linear-gradient(135deg, var(--primary-gradient-start, #9333ea) 0%, var(--primary-gradient-end, #7c3aed) 100%);
            border: none;
            border-radius: 12px;
            color: var(--text-primary, #ffffff);
            font-size: 0.9rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(147, 51, 234, 0.3);
            margin-top: 1.5rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(147, 51, 234, 0.4);
        }
        .terms-card {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Background gradient overlay -->
        <div class="background-gradient"></div>

        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <img class="logo-icon" src="{{ asset('assets/dashboard/images/meta/logo-2.png') }}" alt="logo" style="object-fit: contain;">
                <span class="logo-text">Core Mining</span>
            </div>
        </div>

        <!-- Terms and Conditions Card -->
        <div class="register-card terms-card">
            <div class="card-content">
                <!-- Page Header -->
                <div class="register-header" style="margin-bottom: 1rem">
                    <h1 class="welcome-title">Terms and Conditions</h1>
                    <p class="welcome-subtitle">Please read these terms carefully</p>
                </div>

                <!-- Terms Content -->
                <div class="terms-content">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing and using the Core Mining platform, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

                    <h2>2. Description of Service</h2>
                    <p>Core Mining provides an investment platform that allows users to participate in mining plans and investment opportunities. The platform facilitates transactions, manages user accounts, and provides related services.</p>

                    <h2>3. User Account</h2>
                    <p>To access certain features of the service, you must register for an account. You agree to:</p>
                    <ul>
                        <li>Provide accurate, current, and complete information during registration</li>
                        <li>Maintain and promptly update your account information</li>
                        <li>Maintain the security of your password and identification</li>
                        <li>Accept all responsibility for activities that occur under your account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                    </ul>

                    <h2>4. Investment Risks</h2>
                    <p>You acknowledge and agree that:</p>
                    <ul>
                        <li>All investments carry inherent risks, including the potential loss of principal</li>
                        <li>Past performance does not guarantee future results</li>
                        <li>You should only invest funds that you can afford to lose</li>
                        <li>You are solely responsible for your investment decisions</li>
                    </ul>

                    <h2>5. Referral Program</h2>
                    <p>Our referral program allows you to earn commissions by referring new users. You agree to:</p>
                    <ul>
                        <li>Use only legitimate referral methods</li>
                        <li>Not engage in fraudulent or deceptive practices</li>
                        <li>Comply with all applicable laws and regulations</li>
                    </ul>

                    <h2>6. Deposits and Withdrawals</h2>
                    <p>All deposits and withdrawals are subject to:</p>
                    <ul>
                        <li>Verification procedures for security purposes</li>
                        <li>Processing times as specified in our policies</li>
                        <li>Applicable fees as disclosed at the time of transaction</li>
                        <li>Compliance with anti-money laundering regulations</li>
                    </ul>

                    <h2>7. Prohibited Activities</h2>
                    <p>You agree not to:</p>
                    <ul>
                        <li>Use the service for any illegal purpose</li>
                        <li>Attempt to gain unauthorized access to the platform</li>
                        <li>Interfere with or disrupt the service or servers</li>
                        <li>Create multiple accounts to circumvent system limitations</li>
                        <li>Engage in any form of fraud or manipulation</li>
                    </ul>

                    <h2>8. Intellectual Property</h2>
                    <p>All content, features, and functionality of the Core Mining platform, including but not limited to text, graphics, logos, and software, are the exclusive property of Core Mining and are protected by international copyright, trademark, and other intellectual property laws.</p>

                    <h2>9. Limitation of Liability</h2>
                    <p>To the maximum extent permitted by law, Core Mining shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses.</p>

                    <h2>10. Modifications to Terms</h2>
                    <p>We reserve the right to modify these terms at any time. We will notify users of any material changes by posting the new terms on this page. Your continued use of the service after such modifications constitutes acceptance of the updated terms.</p>

                    <h2>11. Termination</h2>
                    <p>We may terminate or suspend your account and access to the service immediately, without prior notice, for any breach of these terms. Upon termination, your right to use the service will cease immediately.</p>

                    <h2>12. Governing Law</h2>
                    <p>These terms shall be governed by and construed in accordance with applicable laws. Any disputes arising from these terms or your use of the service shall be resolved through appropriate legal channels.</p>

                    <h2>13. Contact Information</h2>
                    <p>If you have any questions about these Terms and Conditions, please contact us through our support system or the contact information provided on the platform.</p>

                    <p style="margin-top: 2rem; font-weight: 600; color: var(--text-primary, #1f2937);">
                        Last Updated: {{ date('F j, Y') }}
                    </p>
                </div>

                <!-- Back to Registration Button -->
                <a href="{{ route('register') }}" class="back-button">
                    Back to Registration
                </a>
            </div>
        </div>
    </div>
</body>
</html>

