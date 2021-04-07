<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/paytillrecipient", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="tillName")> Till Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="tillName"  type='text' placeholder='Enter till name' required/>
                <div class="small form-text text-muted">
                    Enter till name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="tillNumber")> Till number </label>
            <div class="col-sm-7">
                <input class="form-control" name="tillNumber"  type='text' placeholder='Enter till number' required/>
                <div class="small form-text text-muted">
                    Enter the till number
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Create an external till pay recipient
            </div>
        </div>
    </form>
</div> 
