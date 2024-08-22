<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="party_id" id="party_id" value="<?=$party_id?>">
            <input type="hidden" name="lead_stage" id="lead_stage" value="2">

            <div class="col-md-6 form-group">
                <label for="ref_date">Date</label>
                <input type="date" name="ref_date" id="ref_date" class="form-control req" min="<?=date("Y-m-d")?>" value="<?=date("Y-m-d")?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="ref_time">Time</label>
                <input type="time" name="reminder_time" id="reminder_time" class="form-control req" min="<?=date("H:i:s")?>" value="<?=date("H:i:s")?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="mode">Mode</label>
                <select name="mode" id="mode" class="form-control selectBox1">
                    <?php
                        foreach($this->reminderModes as $mode):
                            echo '<option value="'.$mode.'">'.$mode.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Notes</label>
                <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
            </div>
        </div> 
    </div>
</form>