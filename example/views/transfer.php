<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/transfer", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="destinationType")> Destination Type </label>
            <div class="col-sm-7">
                <select name = "destinationType">
                    <option value="merchant_bank_account"> Merchant Bank Account </option>
                    <option value="merchant_wallet">Merchant Wallet</option>
                    <option value="">Blind Settlement</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="destinationReference")> Destination Reference</label>
            <div class="col-sm-7">
                <input class="form-control" name="destinationReference"  type='text' placeholder='Enter destination reference'/>
                <div class="small form-text text-muted">
                    Enter the destination reference
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="amount")> Amount </label>
            <div class="col-sm-7">
                <input class="form-control" name="amount"  type='text' placeholder='Enter amount'/>
                <div class="small form-text text-muted">
                    Enter the amount
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Settle Funds
            </div>
        </div>
    </form>
</div> 
