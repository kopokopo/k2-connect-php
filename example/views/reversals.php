<?php
    include "layout.php";
?>
<div class="container">
    <form id="reversals" action="/reversals" method="post">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="transactionReference">Transaction Reference</label>
            <div class="col-sm-7">
                <input class="form-control" name="transactionReference" type="text" placeholder="Enter transaction reference" required/>
                <div class="small form-text text-muted">Enter the transaction reference</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="reason">Reversal Reason</label>
            <div class="col-sm-7">
                <input class="form-control" name="reason" type="text" placeholder="Enter reason for reversal" required/>
                <div class="small form-text text-muted">Enter the reason for reversal</div>
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
                <button class="btn btn-success" type="submit">Reverse Transaction</button>
            </div>
        </div>
    </form>
</div>
