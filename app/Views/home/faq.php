<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h1>
            <p class="text-xl text-gray-600">Find answers to common questions</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">How do I join a lucky draw?</h3>
                    <p class="text-gray-700">Simply register an account, choose a draw, and pay the entry fee.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Are the draws fair?</h3>
                    <p class="text-gray-700">Yes, all draws are conducted using secure random selection algorithms.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">When do I receive my prize?</h3>
                    <p class="text-gray-700">Cash prizes are transferred instantly, products are shipped within 48 hours.</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="/" class="btn-primary text-lg px-8 py-3">Back to Home</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
