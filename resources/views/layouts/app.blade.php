    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css">
        <title>@yield('title', 'My Application')</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                margin: 0;
            }

            footer {
                background-color: #333;
                color: white;
                text-align: center;
                padding: 10px;
                position: relative;
                bottom: 0;
                width: 100%;
            }

            .sidebar {
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 100;
                height: 100%;
                padding: 48px 0 0;
                background-color: #f8f9fa;
                width: 200px;
                text-align: center;
            }

            .main-content {
                margin-left: 250px;
                padding: 15px;
                flex-grow: 1;
                overflow-y: auto;
                height: calc(100vh - 50px);
            }

            .logout-btn {
                background-color: red;
                color: white;
                padding: 5px 10px;
                border: none;
                cursor: pointer;
            }

            .user-info {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }

            .user-info i {
                margin-right: 10px;
            }

            .user-info .user-name {
                margin-right: 20px;
            }
        </style>
    </head>

    <body>
        <header class="bg-dark text-white p-3 d-flex justify-content-between">
            <h1>My Application</h1>
            <div class="user-info dropdown">
                @if(Auth::check())
                <a href="#" style="text-decoration: none;" class="dropdown-toggle text-white" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user-circle"></i>
                    <span class="user-name">{{ Auth::user()->first_name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id) }}">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('password.update.form') }}">Change Password</a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button class="dropdown-item" type="submit">Logout</button>
                    </form>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endif
            </div>


        </header>

        <div class="d-flex flex-grow-1">
            <nav class="sidebar">
                <ul class="nav flex-column">
                @if(Auth::user()->role == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="home">Home</a>
                    </li>
                @endif
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                    </li> -->
                    @if(Auth::user()->role != 1)
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                    </li>
                    @endif
                </ul>
            </nav>

            <main class="main-content container">
                @yield('content')
            </main>
        </div>

        <footer>
            <p>&copy; 2024 My Application. All rights reserved.</p>
        </footer>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
    </body>

    </html>