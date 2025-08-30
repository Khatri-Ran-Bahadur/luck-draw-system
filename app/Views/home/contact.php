<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 py-16">
        <div class="absolute inset-0">
            <img src="<?= base_url('images/contact-hero.jpg') ?>" alt="Contact Us" class="w-full h-full object-cover opacity-20">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Contact Us</h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">We're here to help! Send us your questions or feedback.</p>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Email</h3>
                                <p class="mt-1 text-gray-600"><?= $contactInfo['email'] ?? 'support@luckydraw.com' ?></p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-phone text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Phone</h3>
                                <p class="mt-1 text-gray-600"><?= $contactInfo['phone'] ?? '+92 300 1234567' ?></p>
                                <p class="mt-1 text-gray-600"><?= $contactInfo['working_hours'] ?? 'Monday to Friday, 9am to 6pm' ?></p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Location</h3>
                                <p class="mt-1 text-gray-600"><?= $contactInfo['address'] ?? '123 Main Street, Lahore, Pakistan' ?></p>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <?php if (!empty($contactInfo['facebook_url']) && $contactInfo['facebook_url'] !== '#'): ?>
                                    <a href="<?= $contactInfo['facebook_url'] ?>" target="_blank" class="text-gray-400 hover:text-blue-500 transition-colors">
                                        <i class="fab fa-facebook text-2xl"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($contactInfo['twitter_url']) && $contactInfo['twitter_url'] !== '#'): ?>
                                    <a href="<?= $contactInfo['twitter_url'] ?>" target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors">
                                        <i class="fab fa-twitter text-2xl"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($contactInfo['instagram_url']) && $contactInfo['instagram_url'] !== '#'): ?>
                                    <a href="<?= $contactInfo['instagram_url'] ?>" target="_blank" class="text-gray-400 hover:text-pink-500 transition-colors">
                                        <i class="fab fa-instagram text-2xl"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($contactInfo['linkedin_url']) && $contactInfo['linkedin_url'] !== '#'): ?>
                                    <a href="<?= $contactInfo['linkedin_url'] ?>" target="_blank" class="text-gray-400 hover:text-blue-700 transition-colors">
                                        <i class="fab fa-linkedin text-2xl"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>

                    <?php if (session()->has('success')): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <?= session()->get('success') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?= session()->get('error') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($validation)): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Please fix the following errors:
                            </div>
                            <ul class="mt-2 ml-6 list-disc">
                                <?php foreach ($validation->getErrors() as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('contact') ?>" method="POST" class="space-y-6">
                        <?= csrf_field() ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="name" name="name"
                                    value="<?= old('name') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Enter your full name" required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email"
                                    value="<?= old('email') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Enter your email address" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                    value="<?= old('phone') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Enter your phone number">
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <input type="text" id="subject" name="subject"
                                    value="<?= old('subject') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Enter message subject" required>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea id="message" name="message" rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                placeholder="Enter your message here..." required><?= old('message') ?></textarea>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600">Find quick answers to common questions</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">How do I participate in lucky draws?</h3>
                        <p class="text-gray-600">Simply register an account, add funds to your wallet, and enter any active draw by paying the entry fee.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">When are winners announced?</h3>
                        <p class="text-gray-600">Winners are announced on the scheduled draw date. You'll be notified via email and SMS if you win.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">How do I claim my prize?</h3>
                        <p class="text-gray-600">Winners can claim their prizes through their dashboard. Cash prizes are transferred to your wallet, while products are shipped to your address.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Is my payment information secure?</h3>
                        <p class="text-gray-600">Yes, we use industry-standard encryption and secure payment gateways to protect all your financial information.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>