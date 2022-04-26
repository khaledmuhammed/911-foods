<form class="mt-4"  method="POST" action="/register">
    @csrf
    @if (session('status'))
        <div class="alert alert-danger">
            @lang(session('status'))
        </div>
    @endif
    {{-- description --}}
        <div class="text-center my-2 text-black text-md"> 
            {{ __('Fill the following fields to Sign in') }}
        </div>
    {{-- Name filed --}}
        <div class="mb-3 flex flex-row items-center form-control @error('name')is-invalid  @enderror " >
            <label for="name" class="form-label w-1/12 flex flex-row items-center">
                <i class="fas fa-user text-gray-500 mt-2 text-xl @error('name')text-red-500 @enderror"></i>
            </label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus
                autocomplete="name" 
                placeholder="{{__('Enter your full name')}}"
                class="outline-none  placeholder-gray-500  flex-1 h-8" 
                >
        </div>
        @error('name')
            <div class="text-red-500 text-sm -mt-2 mb-2">{{ $message }}</div>
        @enderror

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

    {{-- password filed --}}
        <div class="mb-3 flex flex-row items-center form-control @error('password')is-invalid  @enderror " >
            <label for="password" class="form-label w-1/12 flex flex-row items-center">
                <i class="fas fa-lock text-gray-500 mt-2 text-xl @error('password')text-red-500 @enderror"></i>
            </label>
            <input 
                id="password" 
                type="password" 
                name="password"
                required 
                placeholder="{{ __('Enter your Password') }} "
                class="outline-none placeholder-gray-500 flex-1 h-8"
            >   
        </div>
        @error('password')
            <div class="text-red-500 text-sm -mt-2 mb-2">{{ $message }}</div>
        @enderror

    {{-- password_confirmation filed --}}
        <div class="mb-3 flex flex-row items-center form-control @error('password')is-invalid  @enderror " >
            <label for="password_confirmation" class="form-label w-1/12 flex flex-row items-center">
                <i class="fas fa-lock text-gray-500 mt-2 text-xl @error('password')text-red-500 @enderror"></i>
            </label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation"
                required 
                placeholder="{{ __('Confirme your password') }} "
                class="outline-none placeholder-gray-500 flex-1 h-8"
            >   
        </div>
    {{-- Terms and Conditions --}}
        <div class="mb-1 text-sm form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="terms">
            <label class="form-check-label text-black" for="exampleCheck1">{{__('Terms and Conditions')}}</label>
        </div>    
    {{-- Submit Button  --}}
        <div class="flex flex-row-reverse">
            <button type="submit" class="btn bg-green mb-1 px-5 py-2.5 text-white">
                {{__('Submit')}}
            </button>
        </div>
    {{-- Login with socialite --}}
        {{-- <div class="text-center flex justify-between items-center"> 
            <div class="flex-1 h-0.5 bg-gray-300 ml-10 mr-5"></div>
            <div>{{__('Or')}} </div> 
            <div class="mr-10 ml-5 flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div class="flex flex-col mt-1">
            <a href="/auth/google/register" class="flex-1 flex-row px-2 py-2.5 text-center bg-red-500  my-1 text-white rounded h-14 items-center"><i class="fab fa-google"></i>{{__('Sign in with google')}} </a>
            <a href="/auth/facebook/register" class="flex-1 flex-row px-2 py-2.5 text-center bg-blue-600 my-1 text-white rounded h-14 items-center"><i class="fab fa-facebook"></i> <span class="text-sm md:text-base">{{__('Sign in with Facebook')}}</span></a>
        </div> --}}
    
</form>