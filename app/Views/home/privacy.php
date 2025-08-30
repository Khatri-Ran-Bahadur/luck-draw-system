<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl shadow-lg p-8 text-white mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Privacy Policy</h1>
                    <p class="text-blue-100 text-lg mt-1">How we collect, use, and protect your information</p>
                </div>
            </div>
        </div>

        <!-- Privacy Policy Content -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Last updated:</strong> <?= date('F j, Y') ?>
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Information We Collect</h2>
                <p class="text-gray-700 mb-4">
                    We collect information you provide directly to us when you:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Create an account and complete your profile</li>
                    <li>Participate in lucky draws and competitions</li>
                    <li>Make wallet transactions (top-ups, withdrawals)</li>
                    <li>Contact our support team</li>
                    <li>Subscribe to our newsletters or updates</li>
                </ul>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">Personal Information</h3>
                <p class="text-gray-700 mb-4">
                    This may include your name, email address, phone number, profile picture, wallet information, and payment details.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mb-3">Usage Information</h3>
                <p class="text-gray-700 mb-6">
                    We automatically collect information about how you use our services, including your IP address, browser type, device information, and interaction patterns.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. How We Use Your Information</h2>
                <p class="text-gray-700 mb-4">We use the collected information to:</p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Provide, maintain, and improve our services</li>
                    <li>Process transactions and manage your wallet</li>
                    <li>Send you important updates and notifications</li>
                    <li>Respond to your inquiries and provide customer support</li>
                    <li>Detect and prevent fraud or abuse</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Information Sharing and Disclosure</h2>
                <p class="text-gray-700 mb-4">
                    We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li><strong>Service Providers:</strong> With trusted third-party services that help us operate our platform</li>
                    <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                    <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                    <li><strong>Consent:</strong> When you explicitly give us permission to share your information</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Data Security</h2>
                <p class="text-gray-700 mb-4">
                    We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
                </p>
                <p class="text-gray-700 mb-6">
                    However, no method of transmission over the internet or electronic storage is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Retention</h2>
                <p class="text-gray-700 mb-6">
                    We retain your personal information for as long as necessary to provide our services, comply with legal obligations, resolve disputes, and enforce our agreements. When we no longer need your information, we will securely delete or anonymize it.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Your Rights and Choices</h2>
                <p class="text-gray-700 mb-4">You have the right to:</p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Access and review your personal information</li>
                    <li>Update or correct inaccurate information</li>
                    <li>Request deletion of your personal information</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Request data portability</li>
                    <li>Withdraw consent at any time</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Cookies and Tracking Technologies</h2>
                <p class="text-gray-700 mb-4">
                    We use cookies and similar tracking technologies to enhance your experience, analyze usage patterns, and personalize content. You can control cookie settings through your browser preferences.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Third-Party Links</h2>
                <p class="text-gray-700 mb-6">
                    Our services may contain links to third-party websites or services. We are not responsible for the privacy practices of these external sites. We encourage you to review their privacy policies.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Children's Privacy</h2>
                <p class="text-gray-700 mb-6">
                    Our services are not intended for children under 18 years of age. We do not knowingly collect personal information from children under 18. If you believe we have collected such information, please contact us immediately.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. International Data Transfers</h2>
                <p class="text-gray-700 mb-6">
                    Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Changes to This Policy</h2>
                <p class="text-gray-700 mb-6">
                    We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on this page and updating the "Last updated" date. Your continued use of our services after such changes constitutes acceptance of the updated policy.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Contact Us</h2>
                <p class="text-gray-700 mb-4">
                    If you have any questions about this privacy policy or our data practices, please contact us:
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-gray-700"><strong>Email:</strong> privacy@lucky-draw-system.com</p>
                    <p class="text-gray-700"><strong>Phone:</strong> +92 XXX XXX XXXX</p>
                    <p class="text-gray-700"><strong>Address:</strong> [Your Business Address]</p>
                </div>

                <div class="border-t border-gray-200 pt-6 mt-8">
                    <p class="text-sm text-gray-500 text-center">
                        By using our services, you acknowledge that you have read and understood this privacy policy and agree to the collection, use, and disclosure of your information as described herein.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
