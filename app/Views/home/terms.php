<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Terms & Conditions</h1>
            <p class="text-xl text-gray-600">Please read our terms carefully</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Terms of Service</h2>
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-700 mb-4">By using our Lucky Draw System, you agree to these terms and conditions.</p>
                <p class="text-gray-700 mb-4">All draws are conducted fairly and transparently. Entry fees are non-refundable.</p>
            </div>
        </div>

        <div class="text-center">
            <a href="/" class="btn-primary text-lg px-8 py-3">Back to Home</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
