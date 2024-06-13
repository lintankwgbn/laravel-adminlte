                                                <h5 class="text-bold">{{ __('Permanently delete your account.') }}</h5>
                                                <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
                                                <div class="mt-4">
                                                    <form action="/user/delete-account" method="post">
                                                        <x-forms.csrf-token />
                                                        <x-forms.button class="btn-danger">{{ __('Delete Account') }}</x-forms.button>
                                                    </form>
                                                </div>
