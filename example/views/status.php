<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/status", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="location")> Location URL </label>
            <div class="col-sm-7">
                <input class="form-control" name="location"  type='text' placeholder='Enter location url'/>
                <div class="small form-text text-muted">
                    Enter the location url
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Get Status
            </div>
        </div>
    </form>
</div> 
