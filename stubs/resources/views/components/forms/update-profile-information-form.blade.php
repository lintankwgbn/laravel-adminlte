                                                <h5 class="text-bold mb-4">{{ __('Update your account\'s profile information and email address.') }}</h5>
                                                <form class="form-horizontal">
                                                    <x-forms.csrf-token />
                                                    <input type="hidden" name="_method" value="PUT" />
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">{{ __('Name') }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="email" class="form-control" name="name" id="name" placeholder="{{ __('Name') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="email" class="col-sm-3 col-form-label">{{ __('Email') }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('Email') }}" />
@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                                                            <p class="text-sm mt-2">
                                                                {{ __('Your email address is unverified.') }}

                                                                <button type="button" class="underline text-sm">
                                                                    {{ __('Click here to re-send the verification email.') }}
                                                                </button>
                                                            </p>
{{-- @if ($this->verificationLinkSent) --}}
                                                            {{-- <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"> --}}
                                                                {{-- {{ __('A new verification link has been sent to your email address.') }} --}}
                                                            {{-- </p> --}}
{{-- @endif --}}
@endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-3 col-sm-9">
                                                            <button type="submit" class="btn btn-danger">{{ __('Save') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
