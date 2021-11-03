<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/pay", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="destinationType")> Destination Type </label>
            <div class="col-sm-7">
                <select name = "destinationType" required>
                    <option value="bank_account"> Bank Account </option>
                    <option value="mobile_wallet">Mobile Wallet</option>
                    <option value="till">Till</option>
                    <option value="paybill">Paybill</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="destinationReference")> Destination Reference</label>
            <div class="col-sm-7">
                <input class="form-control" name="destinationReference"  type='text' placeholder='Enter destination reference' required/>
                <div class="small form-text text-muted">
                    Enter the destination reference
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="amount")> Amount </label>
            <div class="col-sm-7">
                <input class="form-control" name="amount"  type='text' placeholder='Enter amount' required/>
                <div class="small form-text text-muted">
                    Enter the amount
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="amount")> Description </label>
            <div class="col-sm-7">
                <input class="form-control" name="description"  type='text' placeholder='Enter description' required/>
                <div class="small form-text text-muted">
                    Enter the description
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Pay
            </div>
        </div>
    </form>
</div> 

