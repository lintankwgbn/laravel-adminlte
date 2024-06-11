        <x-guest-layout >
            <x-auth.authentication-card>
                <x-slot name="logo"><b>Admin</b>LTE</x-slot>
                <div class="card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form action="/login" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off" />
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope fa-fw"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock fa-fw"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" name="remember" />
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                    <div class="social-auth-links text-center mt-2 mb-3">
                        <a href="#" class="btn btn-block btn-primary"> <i class="fab fa-facebook mr-2"></i> Sign in using Facebook </a>
                        <a href="#" class="btn btn-block btn-danger"> <i class="fab fa-google-plus mr-2"></i> Sign in using Google+ </a>
                    </div>
                    <p class="mb-1">
                        <a href="/forgot-password">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="/register" class="text-center">Register a new membership</a>
                    </p>
                </div>
            </x-auth.authentication-card>
        </x-guest-layout>
