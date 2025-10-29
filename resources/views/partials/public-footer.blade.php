{{-- resources/views/partials/public-footer.blade.php --}}
<footer class="hm-footer">
  <div class="wrap">
    <div class="hm-footgrid">
      <div class="hm-footbrand">
        <div class="brand">
          <span class="hm-logo lg" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M12 21s-6.5-4.35-9.33-7.18C.84 12 .5 9.27 2.34 7.43a5.1 5.1 0 0 1 7.22 0l.44.44.44-.44a5.1 5.1 0 0 1 7.22 0c1.84 1.84 1.5 4.57-.33 6.39C18.5 16.65 12 21 12 21Z" fill="white"/>
              <path d="M6.5 12.5h2.7l1.5-3 2.3 6 1.3-3h3.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="name">HealthMiles</span>
        </div>
        <p>Your trusted partner in healthcare booking and management.</p>
        <div class="social">
          <a href="#" aria-label="Facebook">f</a>
          <a href="#" aria-label="Twitter">t</a>
          <a href="#" aria-label="LinkedIn">in</a>
        </div>
      </div>

      <div>
        <h5>Quick Links</h5>
        <ul class="hm-footlinks">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </div>

      <div>
        <h5>Services</h5>
        <ul class="hm-footlinks">
          <li><a href="#">General Consultation</a></li>
          <li><a href="#">Specialist Care</a></li>
          <li><a href="#">Telemedicine</a></li>
          <li><a href="#">Emergency Care</a></li>
        </ul>
      </div>

      <div>
        <h5>Contact Info</h5>
        <ul class="hm-footlinks">
          <li>📞 +1 (555) 123-4567</li>
          <li>✉️ hello@healthmiles.com</li>
          <li>📍 123 Healthcare Ave<br>Medical District, NY 10001</li>
        </ul>
      </div>
    </div>

    <div class="hm-footbottom">
      <div>© {{ date('Y') }} HealthMiles. All rights reserved.</div>
      <div class="policies">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
      </div>
    </div>
  </div>
</footer>
