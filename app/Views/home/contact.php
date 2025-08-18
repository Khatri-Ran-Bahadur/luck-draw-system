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
                                <p class="mt-1 text-gray-600">support@luckydraw.com</p>
                                <p class="mt-1 text-gray-600">info@luckydraw.com</p>
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
                                <p class="mt-1 text-gray-600">+92 300 1234567</p>
                                <p class="mt-1 text-gray-600">Monday to Friday, 9am to 6pm</p>
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
                                <p class="mt-1 text-gray-600">123 Main Street</p>
                                <p class="mt-1 text-gray-600">Lahore, Pakistan</p>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <i class="fab fa-facebook text-2xl"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                    <i class="fab fa-twitter text-2xl"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-pink-500 transition-colors">
                                    <i class="fab fa-instagram text-2xl"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-700 transition-colors">
                                    <i class="fab fa-linkedin text-2xl"></i>
                                </a>
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

                    <form action="<?= base_url('contact') ?>" method="post" class="space-y-6">
                        <?= csrf_field() ?>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" required
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    value="<?= old('name') ?>">
                            </div>
                            <?php if (isset($validation) && $validation->hasError('name')): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $validation->getError('name') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" required
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    value="<?= old('email') ?>">
                            </div>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $validation->getError('email') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="tel" name="phone" id="phone"
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    value="<?= old('phone') ?>">
                            </div>
                            <?php if (isset($validation) && $validation->hasError('phone')): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $validation->getError('phone') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="subject" id="subject" required
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    value="<?= old('subject') ?>">
                            </div>
                            <?php if (isset($validation) && $validation->hasError('subject')): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $validation->getError('subject') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <div class="mt-1">
                                <textarea name="message" id="message" rows="4" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= old('message') ?></textarea>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('message')): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $validation->getError('message') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3401.5331187029444!2d74.3023514!3d31.4815856!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzHCsDI4JzUzLjciTiA3NMKwMTgnMDguNCJF!5e0!3m2!1sen!2s!4v1625136體"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Frequently Asked Questions</h2>
                <p class="mt-4 text-lg text-gray-600">Find quick answers to common questions</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">How can I participate in lucky draws?</h3>
                    <p class="text-gray-600">Sign up, add funds to your wallet, and choose your preferred lucky draw to participate.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">What payment methods are accepted?</h3>
                    <p class="text-gray-600">We accept PayPal and EasyPaisa for convenient and secure transactions.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">How are winners selected?</h3>
                    <p class="text-gray-600">Winners are selected through a transparent random selection process.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">How do I claim my prize?</h3>
                    <p class="text-gray-600">Prizes are automatically credited to your wallet or shipped to your address.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>