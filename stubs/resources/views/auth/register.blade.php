        <x-guest-layout>
            <x-auth.card>
                <x-auth.card-header />
                <x-auth.card-body>
                    <x-slot name="msg">Register a new membership</x-slot>
                    <form action="/register" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off" />
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Full name" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user fa-fw"></span></div>
                            </div>
                        </div>
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
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Retype password" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock fa-fw"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="agreeTerms" name="terms" value="agree" />
                                    <label for="agreeTerms"> I agree to the <a href="#">terms</a> </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </div>
                    </form>
                    <p class="mt-3 mb-0">
                        <a href="/login" class="text-center">I already have a membership</a>
                    </p>
                </x-auth.card-body>
            </x-auth.card>
        </x-guest-layout>
