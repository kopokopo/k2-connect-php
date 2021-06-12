<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/polling", method="post")>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="from_time")> From Time</label>
            <div class="col-sm-7">
                <input class="form-control" name="from_time"  type='datetime-local' placeholder='Enter from time'/>
                <div class="small form-text text-muted">
                    Enter the From Time
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="to_time")> To Time</label>
            <div class="col-sm-7">
                <input class="form-control" name="to_time"  type='datetime-local' placeholder='Enter to time'/>
                <div class="small form-text text-muted">
                    Enter the To Time
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="scope")> Scope </label>
            <div class="col-sm-7">
                <select name = "scope" required>
                    <option value="company"> Company </option>
                    <option value="till"> Till </option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="scope_ref")> Scope Reference(Till Number) </label>
            <div class="col-sm-7">
                <input class="form-control" name="scope_ref"  type='text' placeholder='Enter scope reference'/>
                <div class="small form-text text-muted">
                    Enter the scope reference(Leave null for company scope)
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Poll
            </div>
        </div>
    </form>
</div> 

