            <x-app-layout>
                <x-slot name="title">User Profile</x-slot>
                <!-- Content Header -->
                <x-app.contents.content-header title="User Profile" />
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle" src="/storage/dist/img/user2-160x160.jpg" alt="User profile picture" />
                                        </div>
                                        <h3 class="profile-username text-center">Alexander Pierce</h3>
                                        <p class="text-muted text-center">Web Developer</p>
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item"><b>Followers</b> <a class="float-right">1,322</a></li>
                                            <li class="list-group-item"><b>Following</b> <a class="float-right">543</a></li>
                                            <li class="list-group-item"><b>Friends</b> <a class="float-right">13,287</a></li>
                                        </ul>
                                        <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- About Me Box -->
                                {{-- <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">About Me</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <strong><i class="fas fa-book mr-1"></i> Education</strong>

                                        <p class="text-muted">
                                            B.S. in Computer Science from the University of Tennessee at Knoxville
                                        </p>

                                        <hr />

                                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                        <p class="text-muted">Malibu, California</p>

                                        <hr />

                                        <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                                        <p class="text-muted">
                                            <span class="tag tag-danger">UI Design</span>
                                            <span class="tag tag-success">Coding</span>
                                            <span class="tag tag-info">Javascript</span>
                                            <span class="tag tag-warning">PHP</span>
                                            <span class="tag tag-primary">Node.js</span>
                                        </p>

                                        <hr />

                                        <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                                    </div>
                                    <!-- /.card-body -->
                                </div> --}}
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#logout-other-browser-sessions" data-toggle="tab">{{ __('Browser Sessions') }}</a>
                                            </li>
@if (Laravel\Fortify\Features::canUpdateProfileInformation())
                                            <li class="nav-item">
                                                <a class="nav-link" href="#update-profile-information" data-toggle="tab">{{ __('Profile Information') }}</a>
                                            </li>
@endif
@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                            <li class="nav-item">
                                                <a class="nav-link" href="#update-password" data-toggle="tab">{{ __('Update Password') }}</a>
                                            </li>
@endif
@if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                            <li class="nav-item">
                                                <a class="nav-link" href="#two-factor-authentication" data-toggle="tab">{{ __('Two Factor') }}</a>
                                            </li>
@endif
@if (Lintankwgbn\Adminlte\Adminlte::hasAccountDeletionFeatures())
                                            <li class="nav-item">
                                                <a class="nav-link" href="#delete-user" data-toggle="tab">{{ __('Delete Account') }}</a>
                                            </li>
@endif
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="logout-other-browser-sessions">
                                                <x-forms.logout-other-browser-sessions-form :agent="$agent" :request="$request" />
                                            </div>
@if (Laravel\Fortify\Features::canUpdateProfileInformation())
                                            <div class="tab-pane" id="update-profile-information">
                                                <x-forms.update-profile-information-form />
                                            </div>
@endif
@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                            <div class="tab-pane" id="update-password">
                                                <x-forms.update-password-form />
                                            </div>
@endif
@if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                            <div class="tab-pane" id="two-factor-authentication">
                                                <x-forms.two-factor-authentication-form />
                                            </div>
@endif
@if (Lintankwgbn\Adminlte\Adminlte::hasAccountDeletionFeatures())
                                            <div class="tab-pane" id="delete-user">
                                                <x-forms.delete-user-form />
                                            </div>
@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </x-app-layout>
