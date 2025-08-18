<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="glass-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                    Edit User
                </h2>
                <p class="text-gray-600 mt-1">Update user information and account settings</p>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center px-4 py-2 glass rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Users
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="glass-card">
        <form action="<?= base_url('admin/users/edit/' . $user['id']) ?>" method="post" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- User Information Section -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        User Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Basic user account details</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            <i class="fas fa-id-card text-gray-400 mr-1"></i>
                            Full Name
                        </label>
                        <input type="text" name="full_name" id="full_name" value="<?= esc($user['full_name']) ?>" required
                            class="input-glass" placeholder="Enter full name">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                            Email Address
                        </label>
                        <input type="email" name="email" id="email" value="<?= esc($user['email']) ?>" required
                            class="input-glass" placeholder="Enter email address">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone text-gray-400 mr-1"></i>
                            Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone" value="<?= esc($user['phone'] ?? '') ?>"
                            class="input-glass" placeholder="Enter phone number (optional)">
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">
                            <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                            Account Status
                        </label>
                        <select name="status" id="status" required class="input-glass">
                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>
                                <i class="fas fa-check-circle"></i> Active
                            </option>
                            <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>
                                <i class="fas fa-pause-circle"></i> Inactive
                            </option>
                            <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>
                                <i class="fas fa-ban"></i> Suspended
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Account Statistics Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Account Statistics
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="text-sm text-gray-600">User ID</div>
                        <div class="text-lg font-semibold text-gray-900">#<?= $user['id'] ?></div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="text-sm text-gray-600">Joined Date</div>
                        <div class="text-lg font-semibold text-gray-900"><?= date('M d, Y', strtotime($user['created_at'])) ?></div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="text-sm text-gray-600">Last Updated</div>
                        <div class="text-lg font-semibold text-gray-900">
                            <?= $user['updated_at'] ? date('M d, Y', strtotime($user['updated_at'])) : 'Never' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="<?= base_url('admin/users') ?>"
                    class="px-6 py-3 glass rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .input-glass {
        width: 100%;
        padding: 14px 16px;
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(226, 232, 240, 0.8);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        transition: all 0.2s ease;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .input-glass::placeholder {
        color: #9ca3af;
        font-weight: 400;
    }

    .input-glass:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(59, 130, 246, 0.6);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.15);
        transform: translateY(-1px);
    }

    select.input-glass {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 44px;
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>