@php
use App\Helpers\ImagePathHelper;
use App\Helpers\S3Helper;

	$isAdminRoute = request()->is('admin/*');
	$isSellerRoute = request()->is('seller/*');
    $user = Auth::user();
    if (!is_null($user)) {
        if (ImagePathHelper::checkImagePath($user->profileImage) == 's3') {
            $filePath = $user->profileImage;

            // Make sure $filePath is not null before passing it
            $preSignedUrl = $filePath ? S3Helper::generatePreSignedUrl($filePath) : "";

            // Assign only if not empty
            $user->profileImagePreSignedURL = $preSignedUrl !== '' ? $preSignedUrl : '';
        } else {
            $user->profileImagePreSignedURL = $user->profileImage;
        }
    }
@endphp
<nav class="navbar navbar-expand-lg bg-light sticky-top top-navbar  m-0">
	<div class="container">
		<a class="navbar-brand" href="{{ URL('/') }}"><i class="fa-solid fa-bag-shopping"></i></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
			aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		@if (!$isAdminRoute && !$isSellerRoute)
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
				<div class="navbar-nav">
					<a class="nav-link active" aria-current="page" href="{{ route('dashboard') }}">Home</a>
					<a class="nav-link" href="#">Features</a>
					<a class="nav-link" href="{{ route('productsList') }}">Products</a>
				</div>
			</div>
		@endif
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav ms-auto d-flex align-items-center">
				@guest
					<li class="nav-item">
						<a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
							href="{{ route('login') }}">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link {{ request()->is('register') ? 'active' : '' }}"
							href="{{ route('register') }}">Register</a>
					</li>
				@else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('cart.index') ? 'active' : '' }} mx-2" href="{{ route('cart.index') }}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNotifications" aria-controls="offcanvasNotifications">
                            <i class="fa-solid fa-bell fs-4"></i>
                            @if ($user->unreadNotifications()->count())
                            <span class="position-absolute top-25 start-75 translate-middle badge rounded-pill bg-danger" id="notification_count">
                                {{$user->unreadNotifications()->count()}}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                            @endif
                        </a>
                    </li>
					<li class="nav-item">
						<a class="nav-link {{ request()->is('cart.index') ? 'active' : '' }} mx-2"
							href="{{ route('cart.index') }}">
							<i class="fa-solid fa-cart-shopping fs-4"></i>
							@if ($user->carts()->count())
							<span class="position-absolute top-25 start-75 translate-middle badge rounded-pill bg-danger">
								{{$user->carts()->count()}}
								<span class="visually-hidden">unread messages</span>
							</span>
							@endif
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
							aria-expanded="false">
							<img src="{{ $user->profileImagePreSignedURL ? $user->profileImagePreSignedURL : $user->getProfileAvatar(50) }}"
								class="object-fit-cover rounded-circle" width=50 height=50
								alt="{{ $user->name }}" />
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="{{ route('logout') }}"
									onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">Logout</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST">
									@csrf
								</form>
							</li>
							<li><a class="dropdown-item" href="{{ route('profile.show') }}">
									Profile
								</a>

							</li>
						</ul>
					</li>
				@endguest
			</ul>
		</div>
	</div>
</nav>
