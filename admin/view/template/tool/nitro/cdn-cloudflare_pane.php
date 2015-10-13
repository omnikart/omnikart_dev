<div class="row">
	<div class="col-md-8">
    <div class="box-heading"><h1>CloudFlare CDN Service</h1></div>
    <div class="box-content">
      <table class="form cdnpanetable">
        <tr>
          <td>CloudFlare Service<span class="help">Enable this if you have configured CloudFlare for your store. This setting will make your OpenCart regard the HTTP_CF_CONNECTING_IP header by CloudFlare.</span></td>
          <td>
          <select name="Nitro[CDNCloudFlare][Enabled]" class="form-control NitroCDNCloudFlare">
              <option value="no" <?php echo (empty($data['Nitro']['CDNCloudFlare']['Enabled']) || $data['Nitro']['CDNCloudFlare']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
              <option value="yes" <?php echo( (!empty($data['Nitro']['CDNCloudFlare']['Enabled']) && $data['Nitro']['CDNCloudFlare']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
          </select>
          </td>
        </tr>
        <!--tr>
          <td>Your Account Email</td>
          <td>
           <input class="form-control" type="text" name="Nitro[CDNCloudFlare][Email]" value="<?php echo(!empty($data['Nitro']['CDNCloudFlare']['Email'])) ? $data['Nitro']['CDNCloudFlare']['Email'] : ''?>" />
          </td>
        </tr>
        <tr>
          <td>Your API Key</td>
          <td>
           <input class="form-control" type="text" name="Nitro[CDNCloudFlare][APIKey]" value="<?php echo(!empty($data['Nitro']['CDNCloudFlare']['APIKey'])) ? $data['Nitro']['CDNCloudFlare']['APIKey'] : ''?>" />
          </td>
        </tr-->
      </table>
    </div>
  </div>
  <div class="col-md-4">
        <div class="box-heading"><h1><i class="icon-info-sign"></i>What is CloudFlare?</h1></div>
        <div class="box-content" style="min-height:150px; line-height:20px;">
        <p>CloudFlare is an online platform which protects and accelerates your site. CloudFlare gives you the chance to use its fast CDN network to distribute your content across an intelligent global network. This gives your visitors fast page load times and the top performance.</p><p> 
Another great advantage of using CloudFlare is that their platform will also block threats and limit abusive bots and crawlers from wasting your bandwidth and server resources.</p>
        <button class="btn btn-default btn-small" type="button" onclick="window.open('https://www.cloudflare.com/sign-up')">Sign Up</button>
        or 
        <button class="btn btn-default btn-small" type="button" onclick="window.open('https://www.cloudflare.com/login')">Login</button> in CloudFlare
        </div>
    </div>
</div>
