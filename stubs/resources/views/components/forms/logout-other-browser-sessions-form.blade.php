                                                <h5 class="text-bold">{{ __('Manage and log out your active sessions on other browsers and devices.') }}</h5>
                                                <p>{{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}</p>
                                                <div class="mt-4">
                                                    <!-- Other Browser Sessions -->
                                                    <div class="d-flex justify-items-center">
                                                        <div>
@if ($agent->isDesktop())
                                                            <x-images.svg-desktop />
@else
                                                            <x-images.svg-mobile />
@endif
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm">
                                                                {{ $agent->platform() ? $agent->platform() : __('Unknown') }} - {{ $agent->browser() ? $agent->browser() : __('Unknown') }}
                                                            </div>
                                                            <div>
                                                                <div class="text-xs">
                                                                    {{ $request->getClientIp() }},
                                                                    <span class="text-green font-semibold">{{ __('This device') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <form action="/logout" method="post">
                                                            <x-forms.csrf-token />
                                                            <x-forms.button class="btn-danger">{{ __('Log Out Sessions') }}</x-forms.button>
                                                        </form>
                                                    </div>
                                                </div>
