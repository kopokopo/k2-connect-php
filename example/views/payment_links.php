<?php
    include "layout.php";
?>
<div class="container">
    <form id="paymentLink" action="/payment_links" method="post">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="tillNumber">Till Number</label>
            <div class="col-sm-7">
                <input class="form-control" name="tillNumber" type="text" placeholder="Enter till number" required/>
                <div class="small form-text text-muted">Enter the till number</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="amount">Amount</label>
            <div class="col-sm-7">
                <input class="form-control" name="amount" type="text" placeholder="Enter the amount" required/>
                <div class="small form-text text-muted">Enter the amount</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="paymentReference">Payment Reference</label>
            <div class="col-sm-7">
                <input class="form-control" name="paymentReference" type="text" placeholder="Merchant payment reference"/>
                <div class="small form-text text-muted">Enter your payment reference</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="note">Note</label>
            <div class="col-sm-7">
                <input class="form-control" name="note" type="text" placeholder="Customer note"/>
                <div class="small form-text text-muted">Enter note for your customer</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
            <div class="col-sm-7">
                <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                <div class="small form-text text-muted">Enter the callback url</div>
            </div>
        </div>

        <br/>
        <div class="form-group row">
            <div class="col-sm-7">
                <button class="btn btn-success" type="submit">Create Payment Link</button>
            </div>
        </div>
    </form>
</div>
