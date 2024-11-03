@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <x-wrapper title="Profile">
                <section>
                    <img onerror="this.src='{{ asset('public/assets/images/no-image.webp') }}'" style="height: 200px; width: 200px" src="{{ asset('public/storage/profile/' . $user->image) }}" alt="">
                </section>
            </x-wrapper>
        </div>
        <div class="col-lg-8">
            <x-wrapper title="">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="about-tab" data-bs-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">About</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="photo-tab" data-bs-toggle="tab" href="#photo" role="tab" aria-controls="photo" aria-selected="false">Edit photo</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Edit password</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="myTabContent">
                    <div class="tab-pane fade active show text-black" id="about" role="tabpanel" aria-labelledby="about-tab">
                        <section class="d-flex flex-column gap-2">
                            <section class="d-flex justify-content-between">
                                <h5>Name:</h5>
                                <h5>{{ $user->admin->first_name . ' ' . $user->admin->last_name }}</h5>
                            </section>
                            <section class="d-flex justify-content-between">
                                <h5>Department:</h5>
                                <h5>{{ $user->admin->department->name }}</h5>
                            </section>
                            <section class="d-flex justify-content-between">
                                <h5>Email:</h5>
                                <h5>{{ $user->email }}</h5>
                            </section>
                            <section class="d-flex justify-content-between">
                                <h5>Phone:</h5>
                                <h5>{{ $user->admin->phone }}</h5>
                            </section>
                        </section>

                    </div>
                    <div class="tab-pane fade text-black" id="photo" role="tabpanel" aria-labelledby="photo-tab">
                        <form method="POST" action="{{ route('superAdmin.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            <label class="form-label" for="image">Upload Image</label>
                            <input name="image" class="form-control" type="file">
                            <button type="submit" class="zBtn-one mt-2">Save</button>
                        </form>
                    </div>
                    <div class="tab-pane fade text-black" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form method="POST" action="{{ route('superAdmin.profile.password') }}">
                            @csrf
                            <label class="form-label" for="previous_password">Previous Password</label>
                            <input name="previous_password" class="form-control" type="password" required>

                            <label class="form-label mt-2" for="new_password">New Password</label>
                            <input name="new_password" class="form-control" type="password" required>

                            <label class="form-label mt-2" for="new_password_confirmation">Rewrite New Password</label>
                            <input name="new_password_confirmation" class="form-control" type="password" required>

                            <button type="submit" class="zBtn-one mt-2">Save</button>
                        </form>
                    </div>
                </div>
            </x-wrapper>
        </div>
    </div>
@endsection
