<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1>Contact Us</h1>
        <p class="lead">Get in touch with our team</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Contact Information</h3>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">📧 Email</h5>
                        <p class="card-text">support@ite311-diga.com</p>
                        
                        <h5 class="card-title">📞 Phone</h5>
                        <p class="card-text">+1 (555) 123-4567</p>
                        
                        <h5 class="card-title">📍 Address</h5>
                        <p class="card-text">
                            123 Digital Arts Street<br>
                            Tech City, TC 12345<br>
                            Philippines
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <h3>Send us a Message</h3>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
