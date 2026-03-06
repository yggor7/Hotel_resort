    <!-- Newsletter Section -->
    <section class="newsletter-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h3 class="newsletter-title mb-3">Subscribe to Our Newsletter</h3>
                    <p class="text-muted mb-4">Sign up for our newsletter and receive exclusive offers, news, and updates.</p>
                    <form class="newsletter-form" action="<?= SITE_URL ?>/process-newsletter.php" method="POST">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email address">
                            <button class="btn btn-newsletter" type="submit">Subscribe</button>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="privacyCheck">
                            <label class="form-check-label text-muted small" for="privacyCheck">I agree to the privacy policy</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid px-5">
            <div class="row py-5">
                <div class="col-lg-3 mb-4">
                    <div class="footer-logo mb-3">
                        <h2 class="logo-text text-white">COZYSTAY</h2>
                        <div class="logo-stars text-white">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-white-50 small">Experience luxury in the heart of the Pacific Islands. Your private paradise awaits with world-class amenities and breathtaking views.</p>
                    <div class="social-links mt-4">
                        <?php if (isset($contactInfo) && $contactInfo): ?>
                            <?php if ($contactInfo['facebook']): ?>
                            <a href="<?= e($contactInfo['facebook']) ?>" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if ($contactInfo['instagram']): ?>
                            <a href="<?= e($contactInfo['instagram']) ?>" class="social-link"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            <?php if ($contactInfo['twitter']): ?>
                            <a href="<?= e($contactInfo['twitter']) ?>" class="social-link"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if ($contactInfo['youtube']): ?>
                            <a href="<?= e($contactInfo['youtube']) ?>" class="social-link"><i class="fab fa-youtube"></i></a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title text-white mb-4">Explore</h5>
                    <ul class="footer-links list-unstyled">
                        <li><a href="<?= SITE_URL ?>/">Home</a></li>
                        <li><a href="<?= SITE_URL ?>/rooms.php">Rooms & Suites</a></li>
                        <li><a href="<?= SITE_URL ?>/services.php">Services</a></li>
                        <li><a href="<?= SITE_URL ?>/about.php">About Us</a></li>
                        <li><a href="<?= SITE_URL ?>/contact.php">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title text-white mb-4">Services</h5>
                    <ul class="footer-links list-unstyled">
                        <li><a href="<?= SITE_URL ?>/services.php">Spa & Wellness</a></li>
                        <li><a href="<?= SITE_URL ?>/services.php">Island Activities</a></li>
                        <li><a href="<?= SITE_URL ?>/services.php">Gastronomic Dining</a></li>
                        <li><a href="<?= SITE_URL ?>/services.php">Airport Pick-up</a></li>
                        <li><a href="<?= SITE_URL ?>/services.php">Concierge</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title text-white mb-4">Contact</h5>
                    <ul class="footer-contact list-unstyled">
                        <?php if (isset($contactInfo) && $contactInfo): ?>
                        <li><i class="fas fa-phone me-2"></i> <?= e($contactInfo['phone']) ?></li>
                        <li><i class="fas fa-envelope me-2"></i> <?= e($contactInfo['email']) ?></li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> <?= e($contactInfo['address']) ?></li>
                        <?php else: ?>
                        <li><i class="fas fa-phone me-2"></i> +41 22 345 67 88</li>
                        <li><i class="fas fa-envelope me-2"></i> reservation@cozystay.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 73120 Courchevel 1850, France</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="col-lg-3 mb-4">
                    <h5 class="footer-title text-white mb-4">Check In / Out</h5>
                    <ul class="footer-hours list-unstyled">
                        <li><span class="text-white-50">Check-in:</span> <span class="text-white">3:00 PM</span></li>
                        <li><span class="text-white-50">Check-out:</span> <span class="text-white">11:00 AM</span></li>
                        <li><span class="text-white-50">Front Desk:</span> <span class="text-white">24/7</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom py-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="text-white-50 small mb-0">&copy; <?= date('Y') ?> CozyStay Resort. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="footer-bottom-links list-inline mb-0">
                            <li class="list-inline-item"><a href="<?= SITE_URL ?>/privacy-policy.php">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="<?= SITE_URL ?>/terms.php">Terms of Use</a></li>
                            <li class="list-inline-item"><a href="<?= SITE_URL ?>/sitemap.php">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Custom JS -->
    <script src="<?= SITE_URL ?>/assets/js/main.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            const topBar = document.querySelector('.top-bar');
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
                if (topBar) topBar.style.display = 'none';
            } else {
                navbar.classList.remove('navbar-scrolled');
                if (topBar && window.innerWidth >= 992) topBar.style.display = 'block';
            }
        });
    </script>

    <?php if (isset($extraJs)) echo $extraJs; ?>
</body>
</html>
