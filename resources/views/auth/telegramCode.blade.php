<x-guest-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('telegram.login.auth', ['phone' => $phone]) }}">
                        @csrf

                        <div class="row mb-3">
                            <x-input-label for="phone" :value="__('Код')" />

                            <div class="col-md-6">
                                <input id="email" type="code" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autofocus>

                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-3">
                                Войти
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
