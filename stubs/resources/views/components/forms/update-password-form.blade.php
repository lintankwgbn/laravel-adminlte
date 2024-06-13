                                                <h5 class="text-bold mb-4">{{ __('Ensure your account is using a long, random password to stay secure.') }}</h5>
                                                <form class="form-horizontal" method="post" action="/user/password">
                                                    <x-forms.csrf-token />
                                                    <input type="hidden" name="_method" value="PUT" />
                                                    <div class="form-group row">
                                                        <label for="current_password" class="col-sm-3 col-form-label">{{ __('Current Password') }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="{{ __('Current Password') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="password" class="col-sm-3 col-form-label">{{ __('New Password') }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('New Password') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="password_confirmation" class="col-sm-3 col-form-label">{{ __('Confirm Password') }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="{{ __('Confirm Password') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-3 col-sm-9">
                                                            <button type="submit" class="btn btn-danger">{{ __('Save') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
