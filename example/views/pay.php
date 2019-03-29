<?php
    include 'layout.php';
?>
<div class="container">
    <form id="bulkSmsForm"(action="/pay", method="post")>
    <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="destination")> Destination </label>
            <div class="col-sm-7">
                <input class="form-control" name="destination"  type='text' placeholder='Enter destination' required/>
                <div class="small form-text text-muted">
                    Enter the destination
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
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Pay
            </div>
        </div>
    </form>
</div> 

