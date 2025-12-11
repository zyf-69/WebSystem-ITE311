<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Login</h3>
            </div>
            <div class="card-body p-4">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($validation) && is_object($validation) && method_exists($validation, 'hasErrors') && $validation->hasErrors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            <?php if(method_exists($validation, 'getErrors')): ?>
                                <?php foreach($validation->getErrors() as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('login') ?>" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input type="email" 
                               class="form-control <?= !empty($validation) && is_object($validation) && method_exists($validation, 'hasError') && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               value="<?= old('email') ?>" 
                               placeholder="Enter your email"
                               required>
                        <?php if(!empty($validation) && is_object($validation) && method_exists($validation, 'hasError') && $validation->hasError('email')): ?>
                            <div class="invalid-feedback">
                                <?= method_exists($validation, 'getError') ? $validation->getError('email') : '' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input type="password" 
                               class="form-control <?= !empty($validation) && is_object($validation) && method_exists($validation, 'hasError') && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                        <?php if(!empty($validation) && is_object($validation) && method_exists($validation, 'hasError') && $validation->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= method_exists($validation, 'getError') ? $validation->getError('password') : '' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">
                        Don't have an account? 
                        <a href="<?= base_url('register') ?>">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
