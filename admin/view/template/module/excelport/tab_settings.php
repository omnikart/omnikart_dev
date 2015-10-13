<div class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-2">
            <?php echo $text_export_entries_number; ?>
        </label>
        <div class="col-sm-10">
            <input type="number" min="50" max="800" name="ExcelPort[Settings][ExportLimit]" value="<?php echo !empty($data['ExcelPort']['Settings']['ExportLimit']) ? $data['ExcelPort']['Settings']['ExportLimit'] : '500'; ?>" class="form-control" />
        </div> 
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">
        <?php echo $text_import_limit; ?>
        </label>
        <div class="col-sm-10">
            <input type="number" min="10" max="800" name="ExcelPort[Settings][ImportLimit]" value="<?php echo !empty($data['ExcelPort']['Settings']['ImportLimit']) ? $data['ExcelPort']['Settings']['ImportLimit'] : '100'; ?>" class="form-control" />
        </div> 
    </div>
</div>