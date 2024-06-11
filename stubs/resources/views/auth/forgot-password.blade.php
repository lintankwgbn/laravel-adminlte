        <x-guest-layout>
            <x-auth.authentication-card>
                <x-slot name="logo"><b>Admin</b>LTE</x-slot>
                <div class="card-body">
                    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                    <form action="/forgot-password" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off" />
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope fa-fw"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                            </div>
                        </div>
                    </form>
                    <p class="mt-3 mb-1">
                        <a href="/login" class="text-center">I already have a membership</a>
                    </p>
                </div>
            </x-auth.authentication-card>
        </x-guest-layout>
