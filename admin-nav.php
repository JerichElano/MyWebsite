<style>
    .account-box {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
    }
    .container-log-btn {
        float: right;
        display: flex;
        align-items: center;
        height: 8vh;
        width: 80%;
        flex-wrap: wrap;
        padding-top: 10px;
    }

    .log-btn {
        font-size: 15px;
        line-height: 1.5;
        color: #fff;
        text-transform: uppercase;
        height: 50px;
        border-radius: 25px;
        background: #dd3157;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 25px;
        text-decoration: none;
    
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }
    
    .login-btn:hover {
        background: #333333;
    }
</style>

<header class="header">
    <div class="logo">
        <a href="#" class="logo-link">
            <img src="assets/img/bscs.png" alt="logo">
        </a>
        <a href="#" class="logo-name">Nexus</a>

        <div class="navbar">
            <a href="admin-page.php#dashboard">Dashboard</a>
            <a href="admin-page.php#products">Products</a>
            <a href="admin-page.php#orders">Orders</a>
            <a href="admin-page.php#accounts">Accounts</a>
        </div>
    </div>
    <div class="navmenu">
        <a href="#" id="account-icon"><img src="assets/img/account.svg" alt="Account Icon"></a>
    </div>
    <div class="account-box" style="display: none;">
        <p>Admin name : <span><?php echo $_SESSION['admin_name']; ?></span></p>
        <p>Email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
        <a href="logout.php" class="btn">Logout</a>
        <p style="font-size: 12px;">Want to <a href="admin-signup.php" style="text-decoration: none; color: red;">register</a> new admin account?</p>
    </div>
</header>

<script src="assets/js/btn-on-clk.js"></script>