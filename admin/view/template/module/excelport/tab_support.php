<div class="row-fluid">
  <div class="col-md-4">
    <div class="box-heading">
    </div>
    <div class=""></div>
    <?php if (empty($data['ExcelPort']['LicensedOn'])): ?>
    <?php 
    $hostname = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;
    $hostname = (strstr($hostname,'http://') === false) ? 'http://'.$hostname: $hostname;
  ?>
    <?php endif; ?>
    <?php if (!empty($data['ExcelPort']['LicensedOn'])): ?>
    <input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="<?php echo base64_encode(json_encode($data['ExcelPort']['License'])); ?>" />
    <input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="<?php echo $data['ExcelPort']['LicensedOn']; ?>" />
    <table class="form licensedTable">
      <tr>
        <td>
      License Holder
        </td>
        <td>
      <?php echo $data['ExcelPort']['License']['customerName']; ?>
        </td>
      </tr>
      <tr>
        <td>
      Registered domains
        </td>
        <td>
          <ul>
      <?php foreach ($data['ExcelPort']['License']['licenseDomainsUsed'] as $domain): ?>
              <li><i class="fa fa-check"></i> <?php echo $domain; ?></li>
            <?php endforeach; ?>
            </ul>
        </td>
      </tr>
      <tr>
        <td>
      License Expires on
        </td>
        <td>
      <?php echo date("F j, Y",strtotime($data['ExcelPort']['License']['licenseExpireDate'])); ?>
        </td>
      </tr>
  </table>
    <?php endif; ?>
  </div>
  <div class="col-md-8">
    <div class="box-heading">
      <h1><i class="fa fa-users"></i> Get Support</h1>
    </div>
    <div class="row thumbnails supportThumbs">
      <div class="col-md-4">
        <div class="thumbnail">
          <img data-src="holder.js/300x200" alt="Community support" style="width: 300px;" src="view/image/excelport/community.png">
          <div class="caption" style="text-align:center;padding-top:0px;">
            <h3>Community</h3>
            <p>Ask the community about your issue on the iSenseLabs forum. </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="thumbnail">
          <img data-src="holder.js/300x200" alt="Ticket support" style="width: 300px;" src="view/image/excelport/tickets.png">
          <div class="caption" style="text-align:center;padding-top:0px;">
            <h3>Tickets</h3>
            <p>Want to comminicate one-to-one with our tech people? Then open a support ticket.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="thumbnail">
          <img data-src="holder.js/300x200" alt="Pre-sale support" style="width: 300px;" src="view/image/excelport/pre-sale.png">
          <div class="caption" style="text-align:center;padding-top:0px;">
            <h3>Pre-sale</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>