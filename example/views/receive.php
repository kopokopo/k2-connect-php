<?php
    include 'layout.php';
?>
<div class="container">
    <form id="bulkSmsForm"(action="/stk", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="first_name")> First Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="first_name"  type='text' placeholder='Enter first name' required/>
                <div class="small form-text text-muted">
                    Enter first name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="last_name")> Last Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="last_name"  type='text' placeholder='Enter last name' required/>
                <div class="small form-text text-muted">
                    Enter last name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="url")> Phone number </label>
            <div class="col-sm-7">
                <input class="form-control" name="phone"  type='text' placeholder='Enter phone number' required/>
                <div class="small form-text text-muted">
                    Enter the phone number
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="url")> Amount </label>
            <div class="col-sm-7">
                <input class="form-control" name="amount"  type='text' placeholder='Enter amount' required/>
                <div class="small form-text text-muted">
                    Enter the amount
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Stk Payment Request
            </div>
        </div>
    </form>
</div> 

