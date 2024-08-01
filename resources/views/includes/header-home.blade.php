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
    <div class="user" >
        <div class="info" id="dropDown">
            <i class="uil uil-bars"></i>
            <span>{{session('user')->lastName}}</span>
            <span>{{session('user')->matricule}}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                <ul class="dropDown" >
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
    </div>
    <img src="https://mycareer.africarice.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fafricarice.b93af9cc.webp&w=1080&q=75" alt="Logo" class="logo">
</div>
