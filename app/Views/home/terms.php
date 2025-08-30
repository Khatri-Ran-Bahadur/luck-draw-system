<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-green-600 to-blue-700 rounded-2xl shadow-lg p-8 text-white mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-file-contract text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Terms & Conditions</h1>
                    <p class="text-green-100 text-lg mt-1">Rules and guidelines for using our platform</p>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions Content -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Last updated:</strong> <?= date('F j, Y') ?>
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-700 mb-6">
                    By accessing and using the Lucky Draw System platform, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Description of Service</h2>
                <p class="text-gray-700 mb-4">
                    The Lucky Draw System is an online platform that provides:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Lucky draw competitions and contests</li>
                    <li>Digital wallet services for managing funds</li>
                    <li>Product and cash prize distributions</li>
                    <li>User referral and commission systems</li>
                    <li>Special user management features</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. User Registration and Accounts</h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">3.1 Account Creation</h3>
                <p class="text-gray-700 mb-4">
                    To use our services, you must create an account by providing accurate, current, and complete information. You are responsible for maintaining the confidentiality of your account credentials.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">3.2 Account Security</h3>
                <p class="text-gray-700 mb-4">
                    You are responsible for all activities that occur under your account. Notify us immediately of any unauthorized use of your account or any other breach of security.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">3.3 Age Requirement</h3>
                <p class="text-gray-700 mb-6">
                    You must be at least 18 years old to create an account and use our services. By using our services, you represent and warrant that you meet this age requirement.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Lucky Draw Participation</h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">4.1 Entry Requirements</h3>
                <p class="text-gray-700 mb-4">
                    Participation in lucky draws is subject to specific entry requirements, including but not limited to:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                    <li>Valid account registration</li>
                    <li>Compliance with draw-specific rules</li>
                    <li>Payment of entry fees (if applicable)</li>
                    <li>Geographic eligibility restrictions</li>
                </ul>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">4.2 Fair Play</h3>
                <p class="text-gray-700 mb-4">
                    All participants must engage in fair play. Any attempt to manipulate the system, use multiple accounts, or engage in fraudulent activities will result in immediate disqualification and account suspension.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">4.3 Prize Distribution</h3>
                <p class="text-gray-700 mb-6">
                    Winners will be selected randomly using our secure algorithm. Prizes will be distributed according to the terms specified for each draw. We reserve the right to modify prize structures with prior notice.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Wallet Services</h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">5.1 Wallet Management</h3>
                <p class="text-gray-700 mb-4">
                    Our digital wallet service allows you to store, send, and receive funds. All transactions are processed securely and may be subject to processing fees.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">5.2 Top-up and Withdrawals</h3>
                <p class="text-gray-700 mb-4">
                    Wallet top-ups and withdrawals are processed through approved payment methods. Processing times may vary depending on the payment method and amount.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">5.3 Transaction Limits</h3>
                <p class="text-gray-700 mb-6">
                    We may impose daily, weekly, or monthly transaction limits for security purposes. These limits may vary based on account verification status and user type.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Special User Program</h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">6.1 Special User Benefits</h3>
                <p class="text-gray-700 mb-4">
                    Special users enjoy additional privileges including commission earnings, priority support, and enhanced wallet features. Special user status is granted at our discretion and may be revoked for violations.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">6.2 Commission Structure</h3>
                <p class="text-gray-700 mb-4">
                    Commission rates and payment schedules are subject to change with prior notice. Special users must maintain active status to continue earning commissions.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">6.3 Responsibilities</h3>
                <p class="text-gray-700 mb-6">
                    Special users are responsible for maintaining accurate wallet information, processing user requests promptly, and maintaining high service standards.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Prohibited Activities</h2>
                <p class="text-gray-700 mb-4">You agree not to:</p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Use the service for any illegal or unauthorized purpose</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                    <li>Interfere with or disrupt the service or servers</li>
                    <li>Engage in money laundering or fraudulent activities</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Violate any applicable laws or regulations</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Intellectual Property</h2>
                <p class="text-gray-700 mb-6">
                    All content, features, and functionality of our platform are owned by us and are protected by international copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, or create derivative works without our explicit permission.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Privacy and Data Protection</h2>
                <p class="text-gray-700 mb-6">
                    Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these terms by reference.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Disclaimers and Limitations</h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">10.1 Service Availability</h3>
                <p class="text-gray-700 mb-4">
                    We strive to provide reliable service but cannot guarantee uninterrupted access. We may temporarily suspend the service for maintenance or technical issues.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">10.2 Limitation of Liability</h3>
                <p class="text-gray-700 mb-6">
                    To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of our services.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Termination</h2>
                <p class="text-gray-700 mb-4">
                    We may terminate or suspend your account at any time for violations of these terms or for any other reason at our sole discretion. Upon termination:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Your access to the service will cease immediately</li>
                    <li>You may lose access to your wallet and funds</li>
                    <li>We may retain certain information as required by law</li>
                    <li>Outstanding obligations must be fulfilled</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Governing Law and Disputes</h2>
                <p class="text-gray-700 mb-4">
                    These terms are governed by the laws of Pakistan. Any disputes arising from these terms or your use of our services will be resolved through binding arbitration in accordance with our dispute resolution procedures.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Changes to Terms</h2>
                <p class="text-gray-700 mb-6">
                    We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of our services after changes constitutes acceptance of the new terms.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">14. Contact Information</h2>
                <p class="text-gray-700 mb-4">
                    For questions about these terms or our services, please contact us:
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-gray-700"><strong>Email:</strong> legal@lucky-draw-system.com</p>
                    <p class="text-gray-700"><strong>Phone:</strong> +92 XXX XXX XXXX</p>
                    <p class="text-gray-700"><strong>Address:</strong> [Your Business Address]</p>
                </div>

                <div class="border-t border-gray-200 pt-6 mt-8">
                    <p class="text-sm text-gray-500 text-center">
                        By using our services, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions. If you do not agree to these terms, please do not use our services.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
