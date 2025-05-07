

<link rel="stylesheet" href="{{ asset('css/creat.css') }}">
<div class="container">
    <h1>Create Your Account</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('signup.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group floating-label">
            <input type="email" name="email" id="email" class="form-control" placeholder=" " required>
            <label for="email">Email Address</label>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <div class="password-strength">
                <div class="password-strength-bar"></div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        
        <div class="form-group floating-label">
            <input type="text" name="nom" id="nom" class="form-control" placeholder=" " required>
            <label for="nom">Last Name</label>
        </div>
        
        <div class="form-group floating-label">
            <input type="text" name="prenom" id="prenom" class="form-control" placeholder=" " required>
            <label for="prenom">First Name</label>
        </div>
        
        <div class="form-group">
            <label>I am a:</label>
            <div class="role-toggle">
                <input type="radio" name="role" id="role-client" value="client" checked>
                <label for="role-client">Client</label>
                <input type="radio" name="role" id="role-freelancer" value="freelancer">
                <label for="role-freelancer">Freelancer</label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="photo">Profile Photo</label>
            <div class="file-upload">
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                <div class="file-upload-label">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                    <span>Choose an image or drag it here</span>
                </div>
                <div class="file-name"></div>
            </div>
        </div>
        
        <button type="submit" class="btn-primary">Create Account</button>
    </form>
</div>

<script>
    // File upload display
    document.getElementById('photo').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'No file selected';
        const fileNameDisplay = document.querySelector('.file-name');
        fileNameDisplay.textContent = fileName;
        fileNameDisplay.style.display = 'block';
    });

    // Password strength meter
    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthBar = document.querySelector('.password-strength-bar');
        
        // Simple password strength calculation
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^A-Za-z0-9]/)) strength++;
        
        strengthBar.className = 'password-strength-bar strength-' + strength;
    });
</script>