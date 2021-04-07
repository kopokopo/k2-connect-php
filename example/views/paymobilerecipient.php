<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/paymobilerecipient", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="firstName")> First Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="firstName"  type='text' placeholder='Enter first name' required/>
                <div class="small form-text text-muted">
                    Enter first name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="lastName")> Last Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="lastName"  type='text' placeholder='Enter last name' required/>
                <div class="small form-text text-muted">
                    Enter last name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="phoneNumber")> Phone number </label>
            <div class="col-sm-7">
                <input class="form-control" name="phoneNumber"  type='text' placeholder='Enter phone number' required/>
                <div class="small form-text text-muted">
                    Enter the phone number
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="network")> Network </label>
            <div class="col-sm-7">
                <input class="form-control" name="network"  type='text' placeholder='Enter network' required/>
                <div class="small form-text text-muted">
                    Enter the network
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Create a mobile pay recipient
            </div>
        </div>
    </form>
</div> 
