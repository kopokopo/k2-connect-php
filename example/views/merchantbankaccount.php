<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/merchantbankaccount", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="bankBranchRef")> Bank Branch Reference</label>
            <div class="col-sm-7">
                <input class="form-control" name="bankBranchRef"  type='text' placeholder='Enter Bank Branch Reference' required/>
                <div class="small form-text text-muted">
                    Enter the Bank Branch Reference
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="accountName")> Account Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="accountName"  type='text' placeholder='Enter account name' required/>
                <div class="small form-text text-muted">
                    Enter the account name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="accountNumber")> Account Number </label>
            <div class="col-sm-7">
                <input class="form-control" name="accountNumber"  type='text' placeholder='Enter account number' required/>
                <div class="small form-text text-muted">
                    Enter the account number
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="settlementMethod")> Settlement Method</label>
            <div class="col-sm-7">
                <input class="form-control" name="settlementMethod"  type='text' placeholder='Enter settlement method' required/>
                <div class="small form-text text-muted">
                    Enter the settlement method
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Create Merchant Bank Account
            </div>
        </div>
    </form>
</div> 

