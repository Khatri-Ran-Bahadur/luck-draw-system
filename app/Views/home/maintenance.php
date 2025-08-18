<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Under Maintenance</h1>
            <p class="text-xl text-gray-600">We'll be back soon!</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="text-center">
                <p class="text-gray-700">Our system is currently undergoing maintenance. Please check back later.</p>
            </div>
        </div>

        <div class="text-center">
            <a href="/" class="btn-primary text-lg px-8 py-3">Back to Home</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
