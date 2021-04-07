<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/paymerchantrecipient", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="aliasName")> Alias Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="aliasName"  type='text' placeholder='Enter alias name' required/>
                <div class="small form-text text-muted">
                    Enter alias name
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
                <button class="btn btn-success"(type='submit')> Create a merchant pay recipient
            </div>
        </div>
    </form>
</div> 
