        <x-guest-layout>
            <x-auth.card>
                <x-auth.card-header />
                <x-auth.card-body>
                    <x-slot name="msg">You are only one step a way from your new password, recover your password now.</x-slot>
                    <form action="/reset-password" method="post">
                        <input type="hidden" name="email" value="{{ $request->query('email') }}" />
                        <input type="hidden" name="token" value="{{ $request->route('token') }}" autocomplete="off" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off" />
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock fa-fw"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock fa-fw"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Change password</button>
                            </div>
                        </div>
                    </form>
                    <p class="mt-3 mb-0">
                        <a href="/login" class="text-center">I already have a membership</a>
                    </p>
                </x-auth.card-body>
            </x-auth.card>
        </x-guest-layout>
