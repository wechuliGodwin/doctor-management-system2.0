<div class="navbar">
    <div class="navbar-brand">
        <a href="{{ route('staff.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Kijabe Hospital" class="logo"> 
           	
        </a>
    </div>
    <ul class="navbar-nav">
        <li><a href="{{ route('staff.dashboard') }}" class="active">Home</a></li>
        <li><a href="{{ route('staff.bulk-emails') }}">Bulk Emails</a></li>
 	<li><a href="{{ route('staff.blog.index') }}">View Blog Posts</a></li> 
 	<li><a href="{{ route('staff.blog.create') }}">Create New Post</a></li>
	
    	
        <li><a href="{{ route('staff.logout') }}">Logout</a></li>
    </ul>
</div>

<style>

    .navbar {
    background-color: #159ed5; /* Main blue color */
    color: #fff;
    padding: 15px 30px; /* Increased padding for more breathing room */
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.navbar-brand {
    display: flex;
    align-items: center;
}

.navbar-brand .logo {
    height: 30px; /* Adjust as needed */
    margin-right: 10px;
}

.navbar-brand h1 {
    margin: 0;
    font-size: 20px; /* Slightly smaller font size */
    font-weight: 600; /* Bolder font */
}

.navbar-nav {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar-nav li {
    margin-left: 30px; /* Increased spacing between items */
}

.navbar-nav li a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s ease; /* Smoother transition on hover */
    padding: 8px 12px; /* Added padding for a button-like feel */
    border-radius: 5px; /* Slightly rounded corners */
}

.navbar-nav li a:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Subtle background on hover */
    color: #fff; /* Keep text white on hover */
}

.navbar-nav li a.active {
    background-color: rgba(255, 255, 255, 0.2); /* Highlight the active link */
    font-weight: 600;
}

</style>