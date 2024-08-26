{{-- <div class="header">
    <img src="https://mycareer.africarice.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fafricarice.b93af9cc.webp&w=1080&q=75" alt="Logo" class="logo">
@if (session('user'))
    <div class="user">
        <div class="name">{{ session('user')->lastName }}</div> <!-- Afficher le nom de l'utilisateur -->
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link nav-link">Logout</button>
        </form>
    </div>
@endif
</div> --}}

<div class="header-home">

    <img src="https://mycareer.africarice.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fafricarice.b93af9cc.webp&w=1080&q=75" alt="Logo" class="logo">
     <div class="user" >
       @if (session('user'))
        <div class="info" id="dropDown">
            <span>{{session('user')->lastName}}</span>
            <span>{{session('user')->matricule}}</span>
            <i class="uil uil-bars"></i>
            <form action="{{ route('logout') }}" method="GET" class="d-inline">
                @csrf
                <ul class="dropDown" >
                    @if (session('user')->role == 'staff')
                        <li>
                            <a href="{{route('home')}}">
                                <span>My requests</span>
                                <i class="uil uil-arrow-up-right"></i>
                            </a>
                        </li>
                    @endif
                    @if (session('user')->matricule == 'A10865' || session('user')->matricule == 'A10825')
                    <li>
                        <a href="{{route('request.approve')}}">
                            <span>Requests list approuved</span>
                            <i class="uil uil-arrow-up-right"></i>
                        </a>
                    </li>
                    @endif
                    @if (session('user')->role == 'admin')
                        <li>
                            <a href="{{route('liste')}}">
                                <span>Requests list</span>
                                <i class="uil uil-arrow-up-right"></i>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('service.email')}}">
                                <span>Config email</span>
                                <i class="uil uil-arrow-up-right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('setting')}}">
                                <span>Setting</span>
                                <i class="uil uil-arrow-up-right"></i>
                            </a>
                        </li>
                    @endif

                    <li>
                        <span>Change password</span>
                        <i class="uil uil-arrow-up-right"></i>
                    </li>
                    <li>
                        <button type="submit">Logout
                            <i class="uil uil-arrow-up-right"></i>
                        </button>
                    </li>
                </ul>
            </form>
        </div>

       @endif
    </div>
</div>
