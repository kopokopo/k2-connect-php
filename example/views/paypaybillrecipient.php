<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/paypaybillrecipient", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="paybillName")> Paybill Name </label>
            <div class="col-sm-7">
                <input class="form-control" name="paybillName"  type='text' placeholder='Enter paybill name' required/>
                <div class="small form-text text-muted">
                    Enter paybill name
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="paybillNumber")> Paybill Number </label>
            <div class="col-sm-7">
                <input class="form-control" name="paybillNumber"  type='text' placeholder='Enter paybill number' required/>
                <div class="small form-text text-muted">
                    Enter the paybill number
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="paybillAccountNumber")> Paybill Account Number </label>
            <div class="col-sm-7">
                <input class="form-control" name="paybillAccountNumber"  type='text' placeholder='Enter paybill account number' required/>
                <div class="small form-text text-muted">
                    Enter the paybill account number
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Create a paybill pay recipient
            </div>
        </div>
    </form>
</div> 
