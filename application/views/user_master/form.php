<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="user_role" id="user_role" value="<?=(!empty($dataRow->user_role))?$dataRow->user_role:1?>">

            <div class="col-md-12 form-group">
                <label for="user_name">User Name</label>
                <input type="text" name="user_name" id="user_name" class="form-control req" value="<?=(!empty($dataRow->user_name))?$dataRow->user_name:""?>">
            </div>
			
			<div class="col-md-6 form-group">
                <label for="user_code">User Code</label><small>(Login ID)</small>
                <input type="text" name="user_code" id="user_code" class="form-control req" value="<?=(!empty($dataRow->user_code))?$dataRow->user_code:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="contact_no">Mobile No.</label><small>(Login ID)</small>
                <input type="text" name="contact_no" id="contact_no" class="form-control req numericOnly" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>">
            </div>

            <?php if(empty($dataRow->id)): ?>
            <div class="col-md-6 form-group">
                <label for="user_psw">Password</label>
                <input type="password" name="user_psw" id="user_psw" class="form-control" value="<?=(!empty($dataRow->user_psw))?$dataRow->user_psw:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="user_psc">Confirm Password</label>
                <input type="text" name="user_psc" id="user_psc" class="form-control" value="<?=(!empty($dataRow->user_psc))?$dataRow->user_psc:""?>">
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>