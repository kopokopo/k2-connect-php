<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Kopokopo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Webhooks
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/webhook/subscribe">Webhook Subscribe</a>
                    <a class="dropdown-item" href="/webhook/resource">Buy Goods Received Resource</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    STK
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/stk">STK Push</a>
                    <a class="dropdown-item" href="/stk/result">Process Payment Request Result</a>
                    <a class="dropdown-item" href="/status">Query Payment Status</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pay
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/pay">Create a Payment</a>
                    <a class="dropdown-item" href="/paymobilerecipient">Add Mobile PAY recipient</a>
                    <a class="dropdown-item" href="/paybankrecipient">Add Bank PAY recipient</a>
                    <a class="dropdown-item" href="/paytillrecipient">Add Till PAY recipient</a>
                    <a class="dropdown-item" href="/paypaybillrecipient">Add Paybill PAY recipient</a>
                    <a class="dropdown-item" href="/status">Payment status</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Settlement
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/transfer">Initiate a transfer</a>
                    <a class="dropdown-item" href="/merchantwallet">Create a Merchant Wallet</a>
                    <a class="dropdown-item" href="/merchantbankaccount">Create a Merchant Bank Account</a>
                    <a class="dropdown-item" href="/status">Query Transfer status</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Polling
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/polling">Poll Transactions</a>
                    <a class="dropdown-item" href="/status">Query Polling status</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sms Notification
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/smsnotification">Send sms Notifications</a>
                    <a class="dropdown-item" href="/status">Query Sms Notification status</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
