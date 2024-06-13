        <x-guest-layout>
            <x-auth.card>
                <x-auth.card-header />
                <x-auth.card-body>
                    <x-slot name="msg">Sign in to start your session</x-slot>
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
                    <p class="mt-3 mb-1">
                        <a href="/forgot-password">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="/register" class="text-center">Register a new membership</a>
                    </p>
                </x-auth.card-body>
            </x-auth.card>
        </x-guest-layout>
