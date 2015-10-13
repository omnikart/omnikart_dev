<?php

/*

  1) First collect files with cdn_prepare_files(). cdn 1 = amazon, cdn 2 = rackspace, cdn 3 = ftp
  2) Map $response to $this->request->post['last']
  3) If it is true that (!empty($files) && $response['step'] == 'upload'), then upload the files one by one. Otherwise the necessary data will be saved to $this->request->post['last']

*/

class ModelToolNitroRackspace extends ModelToolNitro {

  public function upload() {
    // $last = !empty($this->request->post['last']) ? $this->request->post['last'] : array(
    //     'step' => 'list',
    //     'continue_from' => ''
    //   );

    $response = &$this->request->post['last'];

    $valid_extensions = $this->get_valid_extensions(array(
      'css' => getNitroPersistence('CDNRackspace.SyncCSS'),
      'js' => getNitroPersistence('CDNRackspace.SyncJavaScript'),
      'images' => getNitroPersistence('CDNRackspace.SyncImages')
    ));

    if (empty($valid_extensions)) {
      throw new Exception("No file types selected for upload.");
    }

    $files = $this->cdn_prepare_files(array(
      'valid_extensions' => $valid_extensions,
      'cdn' => '2'
    ));

    if (!empty($files) && $response['step'] == 'upload') {
      require_once(NITRO_LIB_FOLDER . 'rackspace/php-opencloud.php');
    
      if (phpversion() >= '5.3.0') {
        require_once NITRO_INCLUDE_FOLDER . 'rackspace_init.php';
      } else {
        throw new Exception("Your PHP version is " . phpversion() . '. Rackspace CDN can be used on PHP 5.3.0+');
      }

      $data = array(
        'rackspace' => &$objstore
      );

      foreach ($files as $file) {
        $data['file'] = $file;
        $this->rackspace_upload_file($data);
        $this->cdn_after_upload($file);
      }
    } else if (empty($files) && $response['step'] == 'upload') {
      $persistence = getNitroPersistence();
      $persistence['Nitro']['CDNRackspace']['LastUpload'] = date($this->language->get('date_format_long') . ' ' . $this->language->get('time_format'));
      setNitroPersistence($persistence);
    }
  }

  private function rackspace_upload_file($data) {
    $response = &$this->request->post['last'];
    $rackspace = &$data['rackspace'];

    $containers = array(
      'js' => NULL,
      'image' => NULL,
      'css' => NULL
    );

    $mimeTypes = $this->generateUpToDateMimeArray('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');

    $source = $data['file']['realpath'];
    $destination = $data['file']['file'];

    $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    $container = null;

    if (getNitroPersistence('CDNRackspace.SyncCSS') && in_array($ext, unserialize(NITRO_EXTENSIONS_CSS))) {
      $container = $rackspace->Container(getNitroPersistence('CDNRackspace.CSSContainer'));
    }

    if (getNitroPersistence('CDNRackspace.SyncJavaScript') && in_array($ext, unserialize(NITRO_EXTENSIONS_JS))) {
      $container = $rackspace->Container(getNitroPersistence('CDNRackspace.JavaScriptContainer'));
    }

    if (getNitroPersistence('CDNRackspace.SyncImages') && in_array($ext, unserialize(NITRO_EXTENSIONS_IMG))) {
      $container = $rackspace->Container(getNitroPersistence('CDNRackspace.ImagesContainer'));
    }

    if (empty($container)) return;

    $obj = $container->DataObject();

    $upload_response = $obj->Create(array('name' => $destination, 'content_type' => $mimeTypes[$ext]), $source);
      
    if ($upload_response->errno() != 0) {
      throw new Exception('Could not upload ' . $destination);
    }

  }

}