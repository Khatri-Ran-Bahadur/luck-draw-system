<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Privacy Policy</h1>
            <p class="text-xl text-gray-600">How we protect your data</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Data Protection</h2>
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-700 mb-4">We take your privacy seriously and protect all personal information.</p>
                <p class="text-gray-700 mb-4">Your data is encrypted and never shared with third parties.</p>
            </div>
        </div>

        <div class="text-center">
            <a href="/" class="btn-primary text-lg px-8 py-3">Back to Home</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
