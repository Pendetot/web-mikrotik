<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; {{ config('app.name') }}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>

              <div class="card-body">
                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                  @csrf
                  
                  <div class="form-group">
                    <label for="login">Email or WhatsApp</label>
                    <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" 
                           name="login" value="{{ old('login') }}" tabindex="1" required autofocus>
                    @error('login')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @else
                      <div class="invalid-feedback">
                        Please fill in your email or WhatsApp number
                      </div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Password</label>
                      @if(Route::has('password.request'))
                      <div class="float-right">
                        <a href="{{ route('password.request') }}" class="text-small">
                          Forgot Password?
                        </a>
                      </div>
                      @endif
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" tabindex="2" required>
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @else
                      <div class="invalid-feedback">
                        Please fill in your password
                      </div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
                
                @if(config('services.facebook.client_id') || config('services.google.client_id'))
                <div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">Login With Social</div>
                </div>
                <div class="row sm-gutters">
                  @if(config('services.facebook.client_id'))
                  <div class="col-6">
                    <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-block btn-social btn-facebook">
                      <span class="fab fa-facebook"></span> Facebook
                    </a>
                  </div>
                  @endif
                  @if(config('services.google.client_id'))
                  <div class="col-6">
                    <a href="{{ route('social.redirect', 'google') }}" class="btn btn-block btn-social btn-google">
                      <span class="fab fa-google"></span> Google
                    </a>                                
                  </div>
                  @endif
                </div>
                @endif
              </div>
            </div>

            <div class="mt-5 text-muted text-center">
              Don't have an account? <a href="{{ route('register') }}">Create One</a>
            </div>
          </div>
        </div>

        <!-- Package Section -->
        @if(isset($packages) && $packages->count() > 0)
        <div class="row mt-5">
          <div class="col-12">
            <div class="text-center mb-4">
              <h2 class="section-title">Pilih Paket Langganan</h2>
              <p class="section-lead">Dapatkan akses penuh dengan berlangganan paket terbaik untuk Anda</p>
            </div>
          </div>
        </div>

        <div class="row justify-content-center">
          @foreach($packages->take(3) as $package)
          <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="pricing {{ $package->featured ? 'pricing-highlight' : '' }}">
              <div class="pricing-title">
                {{ $package->name }}
                @if($package->featured)
                <div class="popular-badge">
                  <i class="fas fa-star"></i> Popular
                </div>
                @endif
              </div>
              <div class="pricing-padding">
                <div class="pricing-price">
                  <div>{{ $package->formatted_price }}</div>
                  <div>{{ $package->duration_text }}</div>
                  @if($package->original_price && $package->discount_percentage > 0)
                  <div class="pricing-discount">
                    <s>{{ $package->formatted_original_price }}</s>
                    <span class="badge badge-danger">{{ $package->discount_percentage }}% OFF</span>
                  </div>
                  @endif
                </div>
                @if($package->description)
                <div class="pricing-details">
                  <div class="text-muted small">
                    {{ $package->description }}
                  </div>
                </div>
                @endif
              </div>
              <div class="pricing-cta">
                <a href="{{ route('register') }}?package={{ $package->id }}" class="btn btn-{{ $package->featured ? 'primary' : 'outline-primary' }} btn-block">
                  Pilih Paket <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        @if($packages->count() > 3)
        <div class="row">
          <div class="col-12 text-center mt-3">
            <a href="{{ route('packages') }}" class="btn btn-outline-primary">
              Lihat Semua Paket <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        </div>
        @endif
        @endif

        <div class="row">
          <div class="col-12">
            <div class="simple-footer text-center mt-5">
              Copyright &copy; {{ config('app.name') }} {{ date('Y') }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/modules/popper.js') }}"></script>
  <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <style>
    .pricing-discount {
      font-size: 0.8rem;
      margin-top: 0.5rem;
    }
    
    .popular-badge {
      display: inline-block;
      background: linear-gradient(135deg, #ffc107, #ff9800);
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-top: 8px;
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .popular-badge i {
      margin-right: 4px;
      font-size: 0.7rem;
    }
    
    .pricing-title {
      text-align: center;
      padding-bottom: 10px;
    }
    
    .pricing {
      position: relative;
      transition: transform 0.3s ease;
    }
    
    .pricing:hover {
      transform: translateY(-5px);
    }
    
    .pricing-highlight {
      border: 2px solid #ffc107;
      box-shadow: 0 4px 20px rgba(255, 193, 7, 0.2);
    }
  </style>
</body>
</html>