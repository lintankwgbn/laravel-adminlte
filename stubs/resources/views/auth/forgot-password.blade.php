        <x-guest-layout>
            <x-auth.card>
                <x-auth.card-header />
                <x-auth.card-body>
                    <x-slot name="msg">You forgot your password? Here you can easily retrieve a new password.</x-slot>
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
                    <p class="mt-3 mb-0">
                        <a href="/login" class="text-center">I already have a membership</a>
                    </p>
                </x-auth.card-body>
            </x-auth.card>
        </x-guest-layout>
