<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h3 class="mb-0"><i class="bi bi-person-plus"></i> Register</h3>
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
                
                <?php if(isset($validation) && $validation->hasErrors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            <?php foreach($validation->getErrors() as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('register') ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person"></i> Full Name
                        </label>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>" 
                               id="name" 
                               name="name" 
                               value="<?= old('name') ?>" 
                               placeholder="Enter your full name (letters, spaces, hyphens, apostrophes only)"
                               pattern="[a-zA-Z\s\-\']+"
                               required>
                        <div class="form-text">Only letters, spaces, hyphens (-), and apostrophes (') are allowed. No special characters.</div>
                        <?php if(isset($validation) && $validation->hasError('name')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('name') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input type="email" 
                               class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               value="<?= old('email') ?>" 
                               placeholder="Enter your email (e.g., example@gmail.com)"
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                               required>
                        <div class="form-text">Only letters, numbers, dots (.), underscores (_), hyphens (-), and @ symbol are allowed.</div>
                        <?php if(isset($validation) && $validation->hasError('email')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input type="password" 
                               class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password (min. 6 characters)"
                               required>
                        <div class="form-text">Password must be at least 6 characters long.</div>
                        <?php if(isset($validation) && $validation->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">
                            <i class="bi bi-lock-fill"></i> Confirm Password
                        </label>
                        <input type="password" 
                               class="form-control <?= isset($validation) && $validation->hasError('password_confirm') ? 'is-invalid' : '' ?>" 
                               id="password_confirm" 
                               name="password_confirm" 
                               placeholder="Confirm your password"
                               required>
                        <?php if(isset($validation) && $validation->hasError('password_confirm')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password_confirm') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-plus"></i> Register
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">
                        Already have an account? 
                        <a href="<?= base_url('login') ?>">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
