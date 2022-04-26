<form  class="mt-8" method="POST" action="/login">
    @csrf
    @if (session('status'))
        <div class="alert alert-danger">
            @lang(session('status'))
        </div>
    @endif
    {{-- description --}}
        <div class="text-center my-3 text-black text-md"> 
            {{ __('Fill the following fields to Login') }}
        </div>
    {{-- Email filed --}}
        <div class="mb-3 flex flex-row items-center form-control @error('email')is-invalid  @enderror " >
            <label for="email" class="form-label w-1/12 flex flex-row items-center">
                <i class="fas fa-at text-gray-500 mt-2 text-xl @error('email')text-red-500 @enderror"></i>
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="email" 
                placeholder="{{ __('Enter your Email') }} "
                class="outline-none placeholder-gray-500 flex-1 h-8"
            >   
        </div>
        @error('email')
            <div class="text-red-500 text-sm -mt-2 mb-2">{{ $message }}</div>
        @enderror
    {{-- Password filed --}}
        <div class="mb-1 flex flex-row items-center form-control  @error('password')is-invalid  @enderror" >
            <label for="password" class="form-label w-1/12 flex flex-row items-center">
                <i class="fas fa-lock text-gray-500 mt-2 text-xl @error('password')text-red-500 @enderror "></i>
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="{{__('Enter your Password') }}"
                class="outline-none placeholder-gray-500 flex-1 h-8" 
            >
        </div>
        @error('password')
            <div class="text-red-500 text-sm -mt-1 mb-2">{{ $message }}</div>
        @enderror
    {{-- Forgot Your Password --}}
        @if (Route::has('password.request'))
            <div class="my-3">
                <a  href="{{ route('password.request') }}" 
                    class="text-green text-sm no-underline hover:underline hover:text-gray-400">
                    {{__('Forgot Your Password?') }}
                </a>
            </div>
        @endif
    {{-- Remember Me --}}
        <div class="mb-3 form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                name="remember" 
                id="remember" 
                {{ old('remember') ? 'checked' : '' }}
            >
            <label class="form-check-label text-black" for="remember">
                {{__('Remember Me') }}
            </label>
        </div>
    {{-- Submit Button --}}
        <div class="flex flex-row-reverse">
            <button type="submit" class="btn bg-green mb-1 px-5 py-2.5 text-white">
                {{__('Submit')}}
            </button>
        </div>

    {{-- Login with socialite --}}
        {{-- <div class="text-center flex justify-between items-center"> 
            <div class="flex-1 h-0.5 bg-gray-300 ml-10 mr-5"></div>
            <div>{{__('Or')}}</div> 
            <div class="mr-10 ml-5 flex-1 h-0.5 bg-gray-300"></div></div>
        <div class="flex flex-col  my-3">
            <a href="/auth/google/login" class="flex-1 flex-row px-2 py-2.5 text-center bg-red-500  my-1 text-white rounded h-14 items-center"><i class="fab fa-google"></i>
                {{__('Login with google')}} 
            </a>
            <a href="/auth/facebook/login" class="flex-1 flex-row px-2 py-2.5 text-center bg-blue-600 my-1 text-white rounded h-14 items-center"><i class="fab fa-facebook"></i> <span class="text-sm md:text-base"> 
                {{__('Login with Facebook ')}}
            </span></a>
        </div> --}}
    
</form>